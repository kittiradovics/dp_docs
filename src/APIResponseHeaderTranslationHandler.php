<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\EntityInterface;
use Drupal\content_translation\ContentTranslationHandler;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the translation handler for api_response_headers.
 */
class APIResponseHeaderTranslationHandler extends ContentTranslationHandler {

  /**
   * {@inheritdoc}
   */
  protected function hasPublishedStatus() {
    // API Response Header does not have a status.
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function hasCreatedTime() {
    // API Response Header does not store creation date.
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function entityFormAlter(array &$form, FormStateInterface $form_state, EntityInterface $entity) {
    parent::entityFormAlter($form, $form_state, $entity);
    $form['actions']['submit']['#submit'][] = [$this, 'entityFormSave'];
  }

  /**
   * Form submission handler for APIResponseHeaderTranslationHandler::entityFormAlter().
   *
   * This handles the save action.
   *
   * @see \Drupal\Core\Entity\EntityForm::build()
   */
  public function entityFormSave(array $form, FormStateInterface $form_state) {
    if ($this->getSourceLangcode($form_state)) {
      /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
      $entity = $form_state->getFormObject()->getEntity();
      // We need a redirect here, otherwise we would get an access denied page
      // since the current URL would be preserved and we would try to add a
      // translation for a language that already has a translation.
      $form_state->setRedirectUrl($entity->toUrl());
    }
  }

}
