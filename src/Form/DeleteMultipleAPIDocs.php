<?php

namespace Drupal\dp_docs\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides an APIDoc deletion confirmation form.
 */
class DeleteMultipleAPIDocs extends ConfirmFormBase {

  /**
   * The array of APIDocs to delete.
   *
   * @var string[][]
   */
  protected $apiDocInfo = [];

  /**
   * The tempstore factory.
   *
   * @var \Drupal\user\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The APIDoc storage.
   *
   * @var \Drupal\dp_docs\APIDocStorageInterface
   */
  protected $storage;

  /**
   * Constructs a DeleteMultiple form object.
   *
   * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $manager
   *   The entity manager.
   * @param \Drupal\Core\Session\AccountProxyInterface
   *   The current user.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityTypeManagerInterface $manager, AccountProxyInterface $current_user) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->storage = $manager->getStorage('api_doc');
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    /** @var PrivateTempStoreFactory $temp_store_factory */
    $temp_store_factory = $container->get('user.private_tempstore');
    /** @var EntityTypeManagerInterface $manager */
    $manager = $container->get('entity_type.manager');
    /** @var AccountProxyInterface $current_user */
    $current_user = $container->get('current_user');
    return new static(
      $temp_store_factory,
      $manager,
      $current_user
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'api_doc_multiple_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->formatPlural(count($this->apiDocInfo), 'Are you sure you want to delete this item?', 'Are you sure you want to delete these items?');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.api_doc.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->apiDocInfo = $this->tempStoreFactory->get('api_doc_multiple_delete_confirm')->get(\Drupal::currentUser()->id());
    if (empty($this->apiDocInfo)) {
      return new RedirectResponse($this->getCancelUrl()->setAbsolute()->toString());
    }
    /** @var \Drupal\dp_docs\APIDocInterface[] $api_docs */
    $api_docs = $this->storage->loadMultiple(array_keys($this->apiDocInfo));

    $items = [];
    foreach ($this->apiDocInfo as $id => $langcodes) {
      foreach ($langcodes as $langcode) {
        $api_doc = $api_docs[$id]->getTranslation($langcode);
        $key = $id . ':' . $langcode;
        $default_key = $id . ':' . $api_doc->getUntranslated()->language()->getId();

        // If we have a translated entity we build a nested list of translations
        // that will be deleted.
        $languages = $api_doc->getTranslationLanguages();
        if (count($languages) > 1 && $api_doc->isDefaultTranslation()) {
          $names = [];
          foreach ($languages as $translation_langcode => $language) {
            $names[] = $language->getName();
            unset($items[$id . ':' . $translation_langcode]);
          }
          $items[$default_key] = [
            'label' => [
              '#markup' => $this->t('@label (Original translation) - <em>The following API Documentation translations will be deleted:</em>', ['@label' => $api_doc->label()]),
            ],
            'deleted_translations' => [
              '#theme' => 'item_list',
              '#items' => $names,
            ],
          ];
        }
        elseif (!isset($items[$default_key])) {
          $items[$key] = $api_doc->label();
        }
      }
    }

    $form['api_docs'] = [
      '#theme' => 'item_list',
      '#items' => $items,
    ];
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('confirm') && !empty($this->apiDocInfo)) {
      $total_count = 0;
      $delete_api_docs = [];
      /** @var \Drupal\Core\Entity\ContentEntityInterface[][] $delete_translations */
      $delete_translations = [];
      /** @var \Drupal\dp_docs\APIDocInterface[] $api_docs */
      $api_docs = $this->storage->loadMultiple(array_keys($this->apiDocInfo));

      foreach ($this->apiDocInfo as $id => $langcodes) {
        foreach ($langcodes as $langcode) {
          $api_doc = $api_docs[$id]->getTranslation($langcode);
          if ($api_doc->isDefaultTranslation()) {
            $delete_api_docs[$id] = $api_doc;
            unset($delete_translations[$id]);
            $total_count += count($api_doc->getTranslationLanguages());
          }
          elseif (!isset($delete_api_docs[$id])) {
            $delete_translations[$id][] = $api_doc;
          }
        }
      }

      if ($delete_api_docs) {
        $this->storage->delete($delete_api_docs);
        $this->logger('api_doc')->notice('Deleted @count API Documentations.', ['@count' => count($delete_api_docs)]);
      }

      if ($delete_translations) {
        $count = 0;
        foreach ($delete_translations as $id => $translations) {
          $api_doc = $api_docs[$id]->getUntranslated();
          /** @var \Drupal\Core\Entity\ContentEntityInterface $translation */
          foreach ($translations as $translation) {
            $api_doc->removeTranslation($translation->language()->getId());
          }
          $api_doc->save();
          $count += count($translations);
        }
        if ($count) {
          $total_count += $count;
          $this->logger('api_doc')->notice('Deleted @count API Documentation translations.', ['@count' => $count]);
        }
      }

      if ($total_count) {
        drupal_set_message($this->formatPlural($total_count, 'Deleted 1 API Documentation.', 'Deleted @count API Documentations.'));
      }

      $this->tempStoreFactory->get('api_doc_multiple_delete_confirm')->delete($this->currentUser->id());
    }

    $form_state->setRedirect('entity.api_doc.collection');
  }

}
