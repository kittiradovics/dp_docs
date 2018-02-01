<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Methods.
 *
 * This extends the base storage class, adding required special handling for
 * API Methods.
 *
 * @ingroup dp_docs
 */
class APIMethodStorage extends SqlContentEntityStorage implements APIMethodStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIMethodInterface $entity) {
    return $this->database->select('api_method_revision', 'amr')
      ->fields('amr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
