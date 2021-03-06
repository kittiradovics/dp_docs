<?php

namespace Drupal\dp_docs;

use Drupal\Core\Form\FormStateInterface;
use Drupal\dp_docs\Form\DPDocsContentEntityForm;

/**
 * Form controller for the API Documentation forms.
 */
class APIDocForm extends DPDocsContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    if (isset($form['uid'])) {
      // Display the uid field under advanced settings.
      $form['author'] = [
        '#type' => 'details',
        '#title' => $this->t('Authoring information'),
        '#group' => 'advanced',
        '#weight' => 90,
        '#optional' => TRUE,
      ];
      $form['uid']['#group'] = 'author';
    }

    return $form;
  }

}
