<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Parameters.
 *
 * This extends the base storage class, adding required special handling for
 * API Parameters.
 *
 * @ingroup dp_docs
 */
class APIParamStorage extends SqlContentEntityStorage implements APIParamStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIParamInterface $entity) {
    return $this->database->select('api_param_revision', 'apr')
      ->fields('apr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
