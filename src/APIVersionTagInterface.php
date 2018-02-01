<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;

interface APIVersionTagInterface extends ContentEntityInterface, EntityChangedInterface, AutoLabelInterface, APIRefRefInterface {

  /**
   * Gets the API Version Tag name.
   *
   * @return string
   *   The API Version Tag name.
   */
  public function getName();

  /**
   * Sets the API Version Tag name.
   *
   * @param $name
   *   The API Version Tag name.
   * @return \Drupal\dp_docs\APIVersionTagInterface
   *   The called API Version Tag entity.
   */
  public function setName($name);

}
