<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Defines the storage handler class for API Contacts.
 *
 * This extends the base storage class, adding required special handling for
 * API Contacts.
 *
 * @ingroup dp_docs
 */
class APIContactStorage extends SqlContentEntityStorage implements APIContactStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(APIContactInterface $entity) {
    return $this->database->select('api_contact_revision', 'acr')
      ->fields('acr', ['vid'])
      ->condition('id', $entity->id())
      ->orderBy('vid')
      ->execute()
      ->fetchCol();
  }

}
