<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Global Parameters.
 *
 * This extends the base storage class, adding required special handling for
 * API Global Parameters.
 *
 * @ingroup dp_docs
 */
interface APIGlobalParamStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Global Parameter revision IDs for a specific API Global Parameter.
   *
   * @param \Drupal\dp_docs\APIGlobalParamInterface $entity
   *   The API Global Parameter entity.
   *
   * @return int[]
   *   API Global Parameter revision IDs (in ascending order).
   */
  public function revisionIds(APIGlobalParamInterface $entity);

}
