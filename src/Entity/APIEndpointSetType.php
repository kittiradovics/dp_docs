<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIEndpointSetTypeInterface;

/**
 * Defines the API Endpoint Set type entity.
 *
 * @ConfigEntityType(
 *   id = "api_endpoint_set_type",
 *   label = @Translation("API Endpoint Set type"),
 *   label_collection = @Translation("API Endpoint Set types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIEndpointSetTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIEndpointSetTypeForm",
 *       "add" = "Drupal\dp_docs\APIEndpointSetTypeForm",
 *       "edit" = "Drupal\dp_docs\APIEndpointSetTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api endpoint set types",
 *   config_prefix = "api_endpoint_set_type",
 *   bundle_of = "api_endpoint_set",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_endpoint_set/add",
 *     "edit-form" = "/admin/structure/api_endpoint_set/manage/{api_endpoint_set_type}",
 *     "delete-form" = "/admin/structure/api_endpoint_set/manage/{api_endpoint_set_type}/delete",
 *     "collection" = "/admin/structure/api_endpoint_set"
 *   },
 * )
 */
class APIEndpointSetType extends ConfigEntityBundleBase implements APIEndpointSetTypeInterface {

  /**
   * The API Endpoint Set type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Endpoint Set type label.
   *
   * @var string
   */
  protected $label;

}
