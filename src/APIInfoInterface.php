<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\VendorExtensionInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;

interface APIInfoInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, VendorExtensionInterface, APIRefRefInterface, APIVersionTagRefInterface {

  /**
   * Gets the API Info title.
   *
   * @return string
   *   The API Info title.
   */
  public function getTitle();

  /**
   * Sets the API Info title.
   *
   * @param $title
   *   The API Info title.
   * @return \Drupal\dp_docs\APIInfoInterface
   *   The called API Info entity.
   */
  public function setTitle($title);

  /**
   * Gets the API Info description.
   *
   * @return string
   *   The API Info description.
   */
  public function getDescription();

  /**
   * Sets the API Info description.
   *
   * @param $description
   *   The API Info description.
   * @return \Drupal\dp_docs\APIInfoInterface
   *   The called API Info entity.
   */
  public function setDescription($description);

  /**
   * Gets the API Info terms of service.
   *
   * @return string
   *   The API Info terms of service.
   */
  public function getTermsOfService();

  /**
   * Sets the API Info terms of service.
   *
   * @param $terms_of_service
   *   The API Info terms of service.
   * @return \Drupal\dp_docs\APIInfoInterface
   *   The called API Info entity.
   */
  public function setTermsOfService($terms_of_service);

  /**
   * Gets the API Info version.
   *
   * @return string
   *   The API Info version.
   */
  public function getVersion();

  /**
   * Sets the API Info version.
   *
   * @param $version
   *   The API Info version.
   * @return \Drupal\dp_docs\APIInfoInterface
   *   The called API Info entity.
   */
  public function setVersion($version);

}
