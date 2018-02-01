<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\ItemInterface;
use Drupal\dp_docs\Traits\APIResponseRefInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;
use Drupal\dp_docs\Traits\VendorExtensionInterface;

interface APIResponseHeaderInterface extends ContentEntityInterface, EntityChangedInterface, VendorExtensionInterface, RevisionLogInterface, AutoLabelInterface, ItemInterface, APIResponseRefInterface, APIRefRefInterface, APIVersionTagRefInterface {

  /**
   * Gets the API Response description.
   *
   * @return string
   *   The API Response description.
   */
  public function getDescription();

  /**
   * Sets the API Response description.
   *
   * @param $description
   *   The API Response description.
   * @return \Drupal\dp_docs\APIResponseInterface
   *   The called API Response entity.
   */
  public function setDescription($description);

}
