<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API References.
 *
 * This extends the base storage class, adding required special handling for
 * API References.
 *
 * @ingroup dp_docs
 */
class APIRefStorage extends SqlContentEntityStorage implements APIRefStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIRefInterface $entity) {
    return $this->database->select('api_ref_revision', 'arr')
      ->fields('arr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
