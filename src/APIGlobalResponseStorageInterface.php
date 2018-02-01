<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Global Responses.
 *
 * This extends the base storage class, adding required special handling for
 * API Global Responses.
 *
 * @ingroup dp_docs
 */
interface APIGlobalResponseStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Global Response revision IDs for a specific API Global Response.
   *
   * @param \Drupal\dp_docs\APIGlobalResponseInterface $entity
   *   The API Global Response entity.
   *
   * @return int[]
   *   API Global Response revision IDs (in ascending order).
   */
  public function revisionIds(APIGlobalResponseInterface $entity);

}
