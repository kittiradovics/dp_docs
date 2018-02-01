<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\APIDocRefInterface;
use Drupal\dp_docs\Traits\APIExtDocRefInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;

interface APIGlobalSchemaInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, APIDocRefInterface, APIRefRefInterface, APIExtDocRefInterface, APIVersionTagRefInterface {

  /**
   * Gets the API Global Schema name.
   *
   * @return string
   *   The API Global Schema name.
   */
  public function getName();

  /**
   * Sets the API Global Schema name.
   *
   * @param $name
   *   The API Global Schema name.
   * @return \Drupal\dp_docs\APIGlobalSchemaInterface
   *   The called API Global Schema entity.
   */
  public function setName($name);

  /**
   * Gets the API Global Schema value.
   *
   * @return string
   *   The API Global Schema value (as a serialized JSON blob).
   */
  public function getValue();

  /**
   * Sets the API Global Schema value.
   *
   * @param $value
   *   The API Global Schema value (as a serialized JSON blob).
   * @return \Drupal\dp_docs\APIGlobalSchemaInterface
   *   The called API Global Schema entity.
   */
  public function setValue($value);

}
