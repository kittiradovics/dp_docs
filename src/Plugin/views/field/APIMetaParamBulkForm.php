<?php

namespace Drupal\dp_docs\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * Defines an API Meta Parameter operations bulk form element.
 *
 * @ViewsField("api_meta_param_bulk_form")
 */
class APIMetaParamBulkForm extends BulkForm {

  /**
   * {@inheritdoc}
   */
  protected function emptySelectedMessage() {
    return $this->t('No API Meta Parameter selected.');
  }

}
