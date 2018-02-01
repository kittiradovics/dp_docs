<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\APIDocRefInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;
use Drupal\dp_docs\Traits\VendorExtensionInterface;

interface APIGlobalResponseInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, APIDocRefInterface, APIRefRefInterface, APIVersionTagRefInterface, VendorExtensionInterface {

  /**
   * Gets the API Global Response name.
   *
   * @return string
   *   The API Global Response name.
   */
  public function getName();

  /**
   * Sets the API Global Response name.
   *
   * @param $name
   *   The API Global Response name.
   * @return \Drupal\dp_docs\APIGlobalResponseInterface
   *   The called API Global Response entity.
   */
  public function setName($name);

  /**
   * Gets the API Global Response description.
   *
   * @return string
   *   The API Global Response description.
   */
  public function getDescription();

  /**
   * Sets the API Global Response description.
   *
   * @param $description
   *   The API Global Response description.
   * @return \Drupal\dp_docs\APIGlobalResponseInterface
   *   The called API Global Response entity.
   */
  public function setDescription($description);

}
