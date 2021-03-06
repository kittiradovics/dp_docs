<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API Form Data Param operations bulk form element.
 *
 * @ViewsField("api_form_data_param_bulk_form")
 */
class APIFormDataParamBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API HTTP Method Form Data Parameter selected.');
  }

}
