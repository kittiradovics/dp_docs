<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API Response Header operations bulk form element.
 *
 * @ViewsField("api_response_header_bulk_form")
 */
class APIResponseHeaderBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API Response Header selected.');
  }

}
