<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIPathParamTypeInterface;

/**
 * Defines the API Path Param type entity.
 *
 * @ConfigEntityType(
 *   id = "api_path_param_type",
 *   label = @Translation("API HTTP Method Path Parameter type"),
 *   label_collection = @Translation("API HTTP Method Path Parameter types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIPathParamTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIPathParamTypeForm",
 *       "add" = "Drupal\dp_docs\APIPathParamTypeForm",
 *       "edit" = "Drupal\dp_docs\APIPathParamTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api path param types",
 *   config_prefix = "api_path_param_type",
 *   bundle_of = "api_path_param",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_path_param/add",
 *     "edit-form" = "/admin/structure/api_path_param/manage/{api_path_param_type}",
 *     "delete-form" = "/admin/structure/api_path_param/manage/{api_path_param_type}/delete",
 *     "collection" = "/admin/structure/api_path_param"
 *   },
 * )
 */
class APIPathParamType extends ConfigEntityBundleBase implements APIPathParamTypeInterface {

  /**
   * The API Path Param type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Path Param type label.
   *
   * @var string
   */
  protected $label;

}
