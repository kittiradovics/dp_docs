<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Query Params.
 *
 * This extends the base storage class, adding required special handling for
 * API Query Params.
 *
 * @ingroup dp_docs
 */
class APIQueryParamStorage extends SqlContentEntityStorage implements APIQueryParamStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIQueryParamInterface $entity) {
    return $this->database->select('api_query_param_revision', 'aqpr')
      ->fields('aqpr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
