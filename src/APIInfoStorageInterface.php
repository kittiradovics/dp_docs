<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Infos.
 *
 * This extends the base storage class, adding required special handling for
 * API Infos.
 *
 * @ingroup dp_docs
 */
interface APIInfoStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Info revision IDs for a specific API Info.
   *
   * @param \Drupal\dp_docs\APIInfoInterface $entity
   *   The API Info entity.
   *
   * @return int[]
   *   API Info revision IDs (in ascending order).
   */
  public function revisionIds(APIInfoInterface $entity);

}
