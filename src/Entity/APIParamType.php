<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIParamTypeInterface;

/**
 * Defines the API Parameter type entity.
 *
 * @ConfigEntityType(
 *   id = "api_param_type",
 *   label = @Translation("API Parameter type"),
 *   label_collection = @Translation("API Parameter types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIParamTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIParamTypeForm",
 *       "add" = "Drupal\dp_docs\APIParamTypeForm",
 *       "edit" = "Drupal\dp_docs\APIParamTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api param types",
 *   config_prefix = "api_param_type",
 *   bundle_of = "api_param",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_param/add",
 *     "edit-form" = "/admin/structure/api_param/manage/{api_param_type}",
 *     "delete-form" = "/admin/structure/api_param/manage/{api_param_type}/delete",
 *     "collection" = "/admin/structure/api_param"
 *   },
 * )
 */
class APIParamType extends ConfigEntityBundleBase implements APIParamTypeInterface {

  /**
   * The API Param type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Param type label.
   *
   * @var string
   */
  protected $label;

}
