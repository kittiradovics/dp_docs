<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Global Parameters.
 *
 * This extends the base storage class, adding required special handling for
 * API Global Parameters.
 *
 * @ingroup dp_docs
 */
class APIGlobalParamStorage extends SqlContentEntityStorage implements APIGlobalParamStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIGlobalParamInterface $entity) {
    return $this->database->select('api_global_param_revision', 'agpr')
      ->fields('agpr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
