<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\APIExtDocRefInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;

interface APISchemaInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, APIExtDocRefInterface, APIRefRefInterface, APIVersionTagRefInterface {

  /**
   * Gets the API Schema ID.
   *
   * @return string
   *   The API Schema ID.
   */
  public function getSchemaID();

  /**
   * Sets the API Schema ID.
   *
   * @param $schema_id
   *   The API Schema ID.
   * @return \Drupal\dp_docs\APISchemaInterface
   *   The called API Schema entity.
   */
  public function setSchemaID($schema_id);

}
