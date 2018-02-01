<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Global Schemas.
 *
 * This extends the base storage class, adding required special handling for
 * API Global Schemas.
 *
 * @ingroup dp_docs
 */
class APIGlobalSchemaStorage extends SqlContentEntityStorage implements APIGlobalSchemaStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIGlobalSchemaInterface $entity) {
    return $this->database->select('api_global_schema_revision', 'agsr')
      ->fields('agsr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
