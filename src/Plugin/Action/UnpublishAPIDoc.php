<?php

namespace Drupal\dp_docs\Plugin\Action;

use Drupal\Core\Field\FieldUpdateActionBase;
use Drupal\dp_docs\APIDocInterface;

/**
 * Unpublishes an API Documentation.
 *
 * @Action(
 *   id = "api_doc_unpublish_action",
 *   label = @Translation("Unpublish selected API Documentation"),
 *   type = "api_doc"
 * )
 */
class UnpublishAPIDoc extends FieldUpdateActionBase {

  /**
   * {@inheritdoc}
   */
  protected function getFieldsToUpdate() {
    return ['status' => APIDocInterface::NOT_PUBLISHED];
  }


}
