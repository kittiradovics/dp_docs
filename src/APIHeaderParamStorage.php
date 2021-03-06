<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Header Params.
 *
 * This extends the base storage class, adding required special handling for
 * API Header Params.
 *
 * @ingroup dp_docs
 */
class APIHeaderParamStorage extends SqlContentEntityStorage implements APIHeaderParamStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIHeaderParamInterface $entity) {
    return $this->database->select('api_header_param_revision', 'ahpr')
      ->fields('ahpr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
