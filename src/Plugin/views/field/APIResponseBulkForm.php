<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API Response operations bulk form element.
 *
 * @ViewsField("api_response_bulk_form")
 */
class APIResponseBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API Response selected.');
  }

}
