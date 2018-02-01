<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Response Examples.
 *
 * This extends the base storage class, adding required special handling for
 * API Response Examples.
 *
 * @ingroup dp_docs
 */
interface APIResponseExampleStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Response Example revision IDs for a specific API Response Example.
   *
   * @param \Drupal\dp_docs\APIResponseExampleInterface $entity
   *   The API Response Example entity.
   *
   * @return int[]
   *   API Response Example revision IDs (in ascending order).
   */
  public function revisionIds(APIResponseExampleInterface $entity);

}
