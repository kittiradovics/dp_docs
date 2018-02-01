<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

class APIResponseListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = t('Label');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = Link::createFromRoute($entity->label(), 'entity.api_response.canonical', ['api_response' => $entity->id()]);
    return $row + parent::buildRow($entity);
  }

}
