<?php

namespace Drupal\dp_docs\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dp_docs\Entity\APIRef;
use Drupal\dp_docs\Entity\APIRefType;
use Drupal\dp_docs\Plugin\MigrationConfigDeriver;
use Drupal\file\Entity\File;
use Drupal\dp_migrate_batch\Batch\MigrateBatch;

/**
 * Entity form for APIRef.
 */
class APIRefForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Store the current source file reference internally to be able to copy it
    // to the new API Reference revision if no new source file will be selected.
    // @see APIRefForm::save()
    $form['source_old'] = [
      '#type' => 'value',
      '#value' => $this->entity->get('source')->getValue(),
    ];
    // Set the source property to NULL so the user can upload a new file.
    $this->entity->set('source', NULL);

    $form = parent::buildForm($form, $form_state);

    switch ($this->operation) {
      case 'edit':
        $form['#title'] = $this->t('Edit API Reference %label', [
          '%label' => $this->entity->label(),
        ]);
        break;

      case 'add':
        $api_ref_type = APIRefType::load($this->entity->bundle());
        $form['#title'] = $this->t('Add %bundle', [
          '%bundle' => $api_ref_type ? $api_ref_type->label() : 'API Reference']
        );
        break;
    }

    // Hide author field since the author is always the current user.
    // @see APIRef::preCreate()
    $form['uid']['#access'] = FALSE;
    // Hide revision information field, we don't want to display it.
    $form['revision_information']['#access'] = FALSE;

    $previous_files = $this->previousFiles();
    if ($previous_files) {
      $form['previous_files'] = [
        '#type' => 'details',
        '#weight' => -2,
        '#title' => $this->t('Previously uploaded files'),
        '#open' => TRUE,
      ];

      foreach ($previous_files as $file) {
        $version = MigrationConfigDeriver::getVersion($this->getEntity()->getType(), $file->getFileUri());
        $form['previous_files'][] = [
          '#theme' => 'file_link',
          '#file' => $file,
          '#description' => Html::escape("{$file->getFilename()} ({$version})"),
          '#cache' => [
            'tags' => $file->getCacheTags(),
          ],
        ];
      }
    }

    return $form;
  }

  /**
   * Collects previously referenced file entities.
   *
   * @return File[]
   */
  protected function previousFiles() {
    $entity = $this->getEntity();
    if ($entity->isNew()) {
      return [];
    }

    $file_ids = $entity->getAllSources();
    /** @var File[] $files */
    $files = $this->entityTypeManager->getStorage('file')->loadMultiple($file_ids);

    return $files;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var APIRef $api_ref */
    $api_ref = $this->entity;
    $api_ref->setNewRevision();

    $source_file = $form_state->getValue('source');
    $source_file_is_empty = empty($source_file[0]['fids']);
    /** @var \Drupal\file\Plugin\Field\FieldType\FileFieldItemList $source_old */
    $source_old = $form_state->getValue('source_old');
    // In case when no new source file was uploaded restore the old one so the
    // new API Reference revision will have the same source file as the previous
    // revision has.
    if ($source_file_is_empty && !empty($source_old[0]['target_id'])) {
      $api_ref->set('source', $source_old);
    }

    $status = parent::save($form, $form_state);

    if ($status === SAVED_UPDATED) {
      drupal_set_message($this->t('API Reference %api_ref has been updated.', [
        '%api_ref' => $api_ref->toLink()->toString(),
      ]));
    }
    else {
      drupal_set_message($this->t('API Reference %api_ref has been added.', [
        '%api_ref' => $api_ref->toLink()->toString(),
      ]));
    }

    $form_state->setRedirectUrl($this->entity->toUrl('collection'));

    // Start a new migration if a new source file was uploaded.
    if (!$source_file_is_empty) {
      /** @var APIRef $entity */
      $entity = $this->entity;
      MigrateBatch::set($entity);
    }

    return $status;
  }

  /**
   * {@inheritdoc}
   *
   * @return APIRef
   */
  public function getEntity() {
    /** @var APIRef $entity */
    $entity = parent::getEntity();
    return $entity;
  }

}
