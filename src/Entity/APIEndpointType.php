<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIEndpointTypeInterface;

/**
 * Defines the API Endpoint type entity.
 *
 * @ConfigEntityType(
 *   id = "api_endpoint_type",
 *   label = @Translation("API Endpoint type"),
 *   label_collection = @Translation("API Endpoint types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIEndpointTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIEndpointTypeForm",
 *       "add" = "Drupal\dp_docs\APIEndpointTypeForm",
 *       "edit" = "Drupal\dp_docs\APIEndpointTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api endpoint types",
 *   config_prefix = "api_endpoint_type",
 *   bundle_of = "api_endpoint",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_endpoint/add",
 *     "edit-form" = "/admin/structure/api_endpoint/manage/{api_endpoint_type}",
 *     "delete-form" = "/admin/structure/api_endpoint/manage/{api_endpoint_type}/delete",
 *     "collection" = "/admin/structure/api_endpoint"
 *   },
 * )
 */
class APIEndpointType extends ConfigEntityBundleBase implements APIEndpointTypeInterface {

  /**
   * The API Endpoint type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Endpoint type label.
   *
   * @var string
   */
  protected $label;

}
