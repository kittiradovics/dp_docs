<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Response Sets.
 *
 * This extends the base storage class, adding required special handling for
 * API Response Sets.
 *
 * @ingroup dp_docs
 */
class APIResponseSetStorage extends SqlContentEntityStorage implements APIResponseSetStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIResponseSetInterface $entity) {
    return $this->database->select('api_response_set_revision', 'arsr')
      ->fields('arsr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
