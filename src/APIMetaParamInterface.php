<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;
use Drupal\dp_docs\Traits\VendorExtensionInterface;

interface APIMetaParamInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, APIRefRefInterface, APIVersionTagRefInterface, VendorExtensionInterface {

  /**
   * Gets the API Meta Parameter name.
   *
   * @return string
   *   The API Meta Parameter name.
   */
  public function getName();

  /**
   * Sets the API Meta Parameter name.
   *
   * @param $name
   *   The API Meta Parameter name.
   * @return \Drupal\dp_docs\APIMetaParamInterface
   *   The called API Meta Parameter entity.
   */
  public function setName($name);

}
