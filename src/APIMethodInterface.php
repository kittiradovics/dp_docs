<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\APIExtDocRefInterface;
use Drupal\dp_docs\Traits\APIParamRefInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\ConsumesInterface;
use Drupal\dp_docs\Traits\ProducesInterface;
use Drupal\dp_docs\Traits\VendorExtensionInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;

interface APIMethodInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, VendorExtensionInterface, ProducesInterface, ConsumesInterface, APIExtDocRefInterface, APIRefRefInterface, APIParamRefInterface, APIVersionTagRefInterface {

  /**
   * Gets the API Method HTTP method.
   *
   * @return string
   *   The API Method HTTP method.
   */
  public function getHTTPMethod();

  /**
   * Sets the API Method HTTP method.
   *
   * @param $http_method
   *   The API Method HTTP method.
   * @return \Drupal\dp_docs\APIMethodInterface
   *   The called API Method entity.
   */
  public function setHTTPMethod($http_method);

  /**
   * Gets the API Method description.
   *
   * @return string
   *   The API Method description.
   */
  public function getDescription();

  /**
   * Sets the API Method description.
   *
   * @param $description
   *   The API Method description.
   * @return \Drupal\dp_docs\APIMethodInterface
   *   The called API Method entity.
   */
  public function setDescription($description);

}
