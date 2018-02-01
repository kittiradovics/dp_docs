<?php

namespace Drupal\dp_docs\Plugin\Action;

use Drupal\Core\Field\FieldUpdateActionBase;
use Drupal\dp_docs\APIDocInterface;

/**
 * Publishes an API Documentation.
 *
 * @Action(
 *   id = "api_doc_publish_action",
 *   label = @Translation("Publish selected API Documentation"),
 *   type = "api_doc"
 * )
 */
class PublishAPIDoc extends FieldUpdateActionBase {

  /**
   * {@inheritdoc}
   */
  protected function getFieldsToUpdate() {
    return ['status' => APIDocInterface::PUBLISHED];
  }

}
