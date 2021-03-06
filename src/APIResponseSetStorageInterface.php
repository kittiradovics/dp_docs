<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Response Sets.
 *
 * This extends the base storage class, adding required special handling for
 * API Response Sets.
 *
 * @ingroup dp_docs
 */
interface APIResponseSetStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Response Set revision IDs for a specific API Response Set.
   *
   * @param \Drupal\dp_docs\APIResponseSetInterface $entity
   *   The API Response Set entity.
   *
   * @return int[]
   *   API Response Set revision IDs (in ascending order).
   */
  public function revisionIds(APIResponseSetInterface $entity);

}
