<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Ext Docs.
 *
 * This extends the base storage class, adding required special handling for
 * API Ext Docs.
 *
 * @ingroup dp_docs
 */
interface APIExtDocStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Ext Doc revision IDs for a specific API Ext Doc.
   *
   * @param \Drupal\dp_docs\APIExtDocInterface $entity
   *   The API Ext Doc entity.
   *
   * @return int[]
   *   API Ext Doc revision IDs (in ascending order).
   */
  public function revisionIds(APIExtDocInterface $entity);

}
