<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIParamItemTypeInterface;

/**
 * Defines the API Param Item type entity.
 *
 * @ConfigEntityType(
 *   id = "api_param_item_type",
 *   label = @Translation("API HTTP Method Parameter Item type"),
 *   label_collection = @Translation("API HTTP Method Parameter Item types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIParamItemTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIParamItemTypeForm",
 *       "add" = "Drupal\dp_docs\APIParamItemTypeForm",
 *       "edit" = "Drupal\dp_docs\APIParamItemTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api param item types",
 *   config_prefix = "api_param_item_type",
 *   bundle_of = "api_param_item",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_param_item/add",
 *     "edit-form" = "/admin/structure/api_param_item/manage/{api_param_item_type}",
 *     "delete-form" = "/admin/structure/api_param_item/manage/{api_param_item_type}/delete",
 *     "collection" = "/admin/structure/api_param_item"
 *   },
 * )
 */
class APIParamItemType extends ConfigEntityBundleBase implements APIParamItemTypeInterface {

  /**
   * The API Param Item type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Param Item type label.
   *
   * @var string
   */
  protected $label;

}
