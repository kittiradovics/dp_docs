<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Path Params.
 *
 * This extends the base storage class, adding required special handling for
 * API Path Params.
 *
 * @ingroup dp_docs
 */
interface APIPathParamStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Path Param revision IDs for a specific API Path Param.
   *
   * @param \Drupal\dp_docs\APIPathParamInterface $entity
   *   The API Path Param entity.
   *
   * @return int[]
   *   API Path Param revision IDs (in ascending order).
   */
  public function revisionIds(APIPathParamInterface $entity);

}
