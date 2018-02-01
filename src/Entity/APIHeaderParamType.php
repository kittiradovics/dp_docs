<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIHeaderParamTypeInterface;

/**
 * Defines the API Header Param type entity.
 *
 * @ConfigEntityType(
 *   id = "api_header_param_type",
 *   label = @Translation("API HTTP Method Header Parameter type"),
 *   label_collection = @Translation("API HTTP Method Header Parameter types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIHeaderParamTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIHeaderParamTypeForm",
 *       "add" = "Drupal\dp_docs\APIHeaderParamTypeForm",
 *       "edit" = "Drupal\dp_docs\APIHeaderParamTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api header param types",
 *   config_prefix = "api_header_param_type",
 *   bundle_of = "api_header_param",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_header_param/add",
 *     "edit-form" = "/admin/structure/api_header_param/manage/{api_header_param_type}",
 *     "delete-form" = "/admin/structure/api_header_param/manage/{api_header_param_type}/delete",
 *     "collection" = "/admin/structure/api_header_param"
 *   },
 * )
 */
class APIHeaderParamType extends ConfigEntityBundleBase implements APIHeaderParamTypeInterface {

  /**
   * The API Header Param type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Header Param type label.
   *
   * @var string
   */
  protected $label;

}
