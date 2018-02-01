<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityStorageInterface;

/**
 * Defines the storage handler class for API Licenses.
 *
 * This extends the base storage class, adding required special handling for
 * API Licenses.
 *
 * @ingroup dp_docs
 */
interface APILicenseStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of API License revision IDs for a specific API License.
   *
   * @param \Drupal\dp_docs\APILicenseInterface $entity
   *   The API License entity.
   *
   * @return int[]
   *   API License revision IDs (in ascending order).
   */
  public function revisionIds(APILicenseInterface $entity);

}
