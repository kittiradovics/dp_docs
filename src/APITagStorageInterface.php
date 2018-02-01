<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Tags.
 *
 * This extends the base storage class, adding required special handling for
 * API Tags.
 *
 * @ingroup dp_docs
 */
interface APITagStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Tag revision IDs for a specific API Tag.
   *
   * @param \Drupal\dp_docs\APITagInterface $entity
   *   The API Tag entity.
   *
   * @return int[]
   *   API Tag revision IDs (in ascending order).
   */
  public function revisionIds(APITagInterface $entity);

}
