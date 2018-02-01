<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Response Headers.
 *
 * This extends the base storage class, adding required special handling for
 * API Response Headers.
 *
 * @ingroup dp_docs
 */
class APIResponseHeaderStorage extends SqlContentEntityStorage implements APIResponseHeaderStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIResponseHeaderInterface $entity) {
    return $this->database->select('api_response_header_revision', 'arhr')
      ->fields('arhr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
