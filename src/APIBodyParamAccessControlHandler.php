<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the APIBodyParam entity.
 *
 * @see \Drupal\dp_docs\Entity\APIBodyParam.
 */
class APIBodyParamAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dp_docs\APIBodyParamInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view api body params');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit api body params');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete api body params');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add api body params');
  }

}
