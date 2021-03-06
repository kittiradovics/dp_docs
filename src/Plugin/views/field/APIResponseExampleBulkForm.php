<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API Response Example operations bulk form element.
 *
 * @ViewsField("api_response_example_bulk_form")
 */
class APIResponseExampleBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API Response Example selected.');
  }

}
