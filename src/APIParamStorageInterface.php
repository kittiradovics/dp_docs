<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Parameters.
 *
 * This extends the base storage class, adding required special handling for
 * API Parameters.
 *
 * @ingroup dp_docs
 */
interface APIParamStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Parameter revision IDs for a specific API Parameter.
   *
   * @param \Drupal\dp_docs\APIParamInterface $entity
   *   The API Parameter entity.
   *
   * @return int[]
   *   API Parameter revision IDs (in ascending order).
   */
  public function revisionIds(APIParamInterface $entity);

}
