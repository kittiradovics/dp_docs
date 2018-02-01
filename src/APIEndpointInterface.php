<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\APIParamRefInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\VendorExtensionInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;

interface APIEndpointInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, VendorExtensionInterface, APIRefRefInterface, APIParamRefInterface, APIVersionTagRefInterface {

  /**
   * Gets the API Endpoint uri.
   *
   * @return string
   *   The API Endpoint uri.
   */
  public function getUri();

  /**
   * Sets the API Endpoint uri.
   *
   * @param $uri
   *   The API Endpoint uri.
   * @return \Drupal\dp_docs\APIEndpointInterface
   *   The called API Endpoint entity.
   */
  public function setUri($uri);

}
