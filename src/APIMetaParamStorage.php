<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Meta Parameters.
 *
 * This extends the base storage class, adding required special handling for
 * API Meta Parameters.
 *
 * @ingroup dp_docs
 */
class APIMetaParamStorage extends SqlContentEntityStorage implements APIMetaParamStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIMetaParamInterface $entity) {
    return $this->database->select('api_meta_param_revision', 'agpr')
      ->fields('agpr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
