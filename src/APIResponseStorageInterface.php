<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Responses.
 *
 * This extends the base storage class, adding required special handling for
 * API Responses.
 *
 * @ingroup dp_docs
 */
interface APIResponseStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Response revision IDs for a specific API Response.
   *
   * @param \Drupal\dp_docs\APIResponseInterface $entity
   *   The API Response entity.
   *
   * @return int[]
   *   API Response revision IDs (in ascending order).
   */
  public function revisionIds(APIResponseInterface $entity);

}
