<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API License operations bulk form element.
 *
 * @ViewsField("api_license_bulk_form")
 */
class APILicenseBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API License selected.');
  }

}
