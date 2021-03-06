<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API Method operations bulk form element.
 *
 * @ViewsField("api_method_bulk_form")
 */
class APIMethodBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API HTTP Method selected.');
  }

}
