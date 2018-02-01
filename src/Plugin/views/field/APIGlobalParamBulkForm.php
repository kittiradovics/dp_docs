<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API Global Parameter operations bulk form element.
 *
 * @ViewsField("api_global_param_bulk_form")
 */
class APIGlobalParamBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API Global Parameter selected.');
  }

}
