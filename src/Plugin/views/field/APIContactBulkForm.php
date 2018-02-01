<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API Contact operations bulk form element.
 *
 * @ViewsField("api_contact_bulk_form")
 */
class APIContactBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API Contact selected.');
  }

}
