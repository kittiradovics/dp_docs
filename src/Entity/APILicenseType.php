<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APILicenseTypeInterface;

/**
 * Defines the API License type entity.
 *
 * @ConfigEntityType(
 *   id = "api_license_type",
 *   label = @Translation("API License type"),
 *   label_collection = @Translation("API License types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APILicenseTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APILicenseTypeForm",
 *       "add" = "Drupal\dp_docs\APILicenseTypeForm",
 *       "edit" = "Drupal\dp_docs\APILicenseTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api license types",
 *   config_prefix = "api_license_type",
 *   bundle_of = "api_license",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_license/add",
 *     "edit-form" = "/admin/structure/api_license/manage/{api_license_type}",
 *     "delete-form" = "/admin/structure/api_license/manage/{api_license_type}/delete",
 *     "collection" = "/admin/structure/api_license"
 *   },
 * )
 */
class APILicenseType extends ConfigEntityBundleBase implements APILicenseTypeInterface {

  /**
   * The API License type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API License type label.
   *
   * @var string
   */
  protected $label;

}
