<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API Body Param operations bulk form element.
 *
 * @ViewsField("api_body_param_bulk_form")
 */
class APIBodyParamBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API HTTP Method Body Parameter selected.');
  }

}
