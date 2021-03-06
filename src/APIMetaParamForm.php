<?php

namespace Drupal\dp_docs;

use Drupal\Core\Form\FormStateInterface;
use Drupal\dp_docs\Form\DPDocsContentEntityForm;

/**
 * Form controller for the API Meta Parameter forms.
 */
class APIMetaParamForm extends DPDocsContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    // Only display the appropriate ER field, depending on the In value.
    $form['api_path_param']['#states'] = [
      'visible' => [':input[name="param_in"]' => ['value' => 'path']],
    ];
    $form['api_body_param']['#states'] = [
      'visible' => [':input[name="param_in"]' => ['value' => 'body']],
    ];
    $form['api_query_param']['#states'] = [
      'visible' => [':input[name="param_in"]' => ['value' => 'query']],
    ];
    $form['api_header_param']['#states'] = [
      'visible' => [':input[name="param_in"]' => ['value' => 'header']],
    ];
    $form['api_form_data_param']['#states'] = [
      'visible' => [':input[name="param_in"]' => ['value' => 'formData']],
    ];

    return $form;
  }

}
