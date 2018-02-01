<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Query Params.
 *
 * This extends the base storage class, adding required special handling for
 * API Query Params.
 *
 * @ingroup dp_docs
 */
interface APIQueryParamStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Query Param revision IDs for a specific API Query Param.
   *
   * @param \Drupal\dp_docs\APIQueryParamInterface $entity
   *   The API Query Param entity.
   *
   * @return int[]
   *   API Query Param revision IDs (in ascending order).
   */
  public function revisionIds(APIQueryParamInterface $entity);

}
