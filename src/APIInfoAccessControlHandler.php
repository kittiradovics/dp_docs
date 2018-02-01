<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the API Info entity.
 *
 * @see \Drupal\dp_docs\Entity\APIInfo.
 */
class APIInfoAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dp_docs\APIInfoInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view api infos');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit api infos');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete api infos');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add api infos');
  }

}

