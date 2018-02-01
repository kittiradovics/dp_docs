<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\VendorExtensionInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;

interface APIExtDocInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, VendorExtensionInterface, APIRefRefInterface, APIVersionTagRefInterface {

  /**
   * Gets the API Ext Doc url.
   *
   * @return string
   *   The API Ext Doc url.
   */
  public function getURL();

  /**
   * Sets the API Ext Doc url.
   *
   * @param $url
   *   The API Ext Doc url.
   * @return \Drupal\dp_docs\APIExtDocInterface
   *   The called API Ext Doc entity.
   */
  public function setURL($url);

  /**
   * Gets the API Ext Doc description.
   *
   * @return string
   *   The API Ext Doc description.
   */
  public function getDescription();

  /**
   * Sets the API Ext Doc description.
   *
   * @param $description
   *   The API Ext Doc description.
   * @return \Drupal\dp_docs\APIExtDocInterface
   *   The called API Ext Doc entity.
   */
  public function setDescription($description);

}
