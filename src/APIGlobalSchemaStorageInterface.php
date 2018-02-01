<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Global Schemas.
 *
 * This extends the base storage class, adding required special handling for
 * API Global Schemas.
 *
 * @ingroup dp_docs
 */
interface APIGlobalSchemaStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Global Schema revision IDs for a specific API Global Schema.
   *
   * @param \Drupal\dp_docs\APIGlobalSchemaInterface $entity
   *   The API Global Schema entity.
   *
   * @return int[]
   *   API Global Schema revision IDs (in ascending order).
   */
  public function revisionIds(APIGlobalSchemaInterface $entity);

}
