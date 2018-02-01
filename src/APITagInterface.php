<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\APIDocRefInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\APIExtDocRefInterface;
use Drupal\dp_docs\Traits\VendorExtensionInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;

interface APITagInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, VendorExtensionInterface, APIExtDocRefInterface, APIDocRefInterface, APIRefRefInterface, APIVersionTagRefInterface {

  /**
   * Gets the API Tag name.
   *
   * @return string
   *   The API Tag name.
   */
  public function getName();

  /**
   * Sets the API Tag name.
   *
   * @param $name
   *   The API Tag name.
   * @return \Drupal\dp_docs\APITagInterface
   *   The called API Tag entity.
   */
  public function setName($name);

  /**
   * Gets the API Tag description.
   *
   * @return string
   *   The API Tag description.
   */
  public function getDescription();

  /**
   * Sets the API Tag description.
   *
   * @param $description
   *   The API Tag description.
   * @return \Drupal\dp_docs\APITagInterface
   *   The called API Tag entity.
   */
  public function setDescription($description);

}
