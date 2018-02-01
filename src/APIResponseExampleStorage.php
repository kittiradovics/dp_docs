<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Response Examples.
 *
 * This extends the base storage class, adding required special handling for
 * API Response Examples.
 *
 * @ingroup dp_docs
 */
class APIResponseExampleStorage extends SqlContentEntityStorage implements APIResponseExampleStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIResponseExampleInterface $entity) {
    return $this->database->select('api_response_example_revision', 'arer')
      ->fields('arer', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
