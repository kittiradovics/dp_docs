<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Docs.
 *
 * This extends the base storage class, adding required special handling for
 * API Docs.
 *
 * @ingroup dp_docs
 */
interface APIDocStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Doc revision IDs for a specific API Doc.
   *
   * @param \Drupal\dp_docs\APIDocInterface $entity
   *   The API Doc entity.
   *
   * @return int[]
   *   API Doc revision IDs (in ascending order).
   */
  public function revisionIds(APIDocInterface $entity);

}
