<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Param Items.
 *
 * This extends the base storage class, adding required special handling for
 * API Param Items.
 *
 * @ingroup dp_docs
 */
interface APIParamItemStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Param Item revision IDs for a specific API Param Item.
   *
   * @param \Drupal\dp_docs\APIParamItemInterface $entity
   *   The API Param Item entity.
   *
   * @return int[]
   *   API Param Item revision IDs (in ascending order).
   */
  public function revisionIds(APIParamItemInterface $entity);

}
