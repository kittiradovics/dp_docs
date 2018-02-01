<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\APIDocRefInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;

interface APIGlobalParamInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, APIDocRefInterface, APIRefRefInterface, APIVersionTagRefInterface {

  /**
   * Gets the API Global Parameter name.
   *
   * @return string
   *   The API Global Parameter name.
   */
  public function getName();

  /**
   * Sets the API Global Parameter name.
   *
   * @param $name
   *   The API Global Parameter name.
   * @return \Drupal\dp_docs\APIGlobalParamInterface
   *   The called API Global Parameter entity.
   */
  public function setName($name);

}
