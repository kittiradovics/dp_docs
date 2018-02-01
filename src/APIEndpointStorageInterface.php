<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Endpoints.
 *
 * This extends the base storage class, adding required special handling for
 * API Endpoints.
 *
 * @ingroup dp_docs
 */
interface APIEndpointStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API Endpoint revision IDs for a specific API Endpoint.
   *
   * @param \Drupal\dp_docs\APIEndpointInterface $entity
   *   The API Endpoint entity.
   *
   * @return int[]
   *   API Endpoint revision IDs (in ascending order).
   */
  public function revisionIds(APIEndpointInterface $entity);

}
