<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the APIResponseHeader entity.
 *
 * @see \Drupal\dp_docs\Entity\APIResponseHeader.
 */
class APIResponseHeaderAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dp_docs\APIResponseHeaderInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view api response headers');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit api response headers');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete api response headers');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add api response headers');
  }

}

