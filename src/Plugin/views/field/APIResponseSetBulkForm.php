<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API Response Set operations bulk form element.
 *
 * @ViewsField("api_response_set_bulk_form")
 */
class APIResponseSetBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API Response Set selected.');
  }

}
