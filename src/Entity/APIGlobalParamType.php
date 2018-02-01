<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIGlobalParamTypeInterface;

/**
 * Defines the API Global Parameter type entity.
 *
 * @ConfigEntityType(
 *   id = "api_global_param_type",
 *   label = @Translation("API Global Parameter type"),
 *   label_collection = @Translation("API Global Parameter types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIGlobalParamTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIGlobalParamTypeForm",
 *       "add" = "Drupal\dp_docs\APIGlobalParamTypeForm",
 *       "edit" = "Drupal\dp_docs\APIGlobalParamTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer API Global Parameter types",
 *   config_prefix = "api_global_param_type",
 *   bundle_of = "api_global_param",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_global_param/add",
 *     "edit-form" = "/admin/structure/api_global_param/manage/{api_global_param_type}",
 *     "delete-form" = "/admin/structure/api_global_param/manage/{api_global_param_type}/delete",
 *     "collection" = "/admin/structure/api_global_param"
 *   },
 * )
 */
class APIGlobalParamType extends ConfigEntityBundleBase implements APIGlobalParamTypeInterface {

  /**
   * The API Global Parameter type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Global Parameter type label.
   *
   * @var string
   */
  protected $label;

}
