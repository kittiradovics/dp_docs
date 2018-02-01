<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIQueryParamTypeInterface;

/**
 * Defines the API Query Param type entity.
 *
 * @ConfigEntityType(
 *   id = "api_query_param_type",
 *   label = @Translation("API HTTP Method Query Parameter type"),
 *   label_collection = @Translation("API HTTP Method Query Parameter types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIQueryParamTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIQueryParamTypeForm",
 *       "add" = "Drupal\dp_docs\APIQueryParamTypeForm",
 *       "edit" = "Drupal\dp_docs\APIQueryParamTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api query param types",
 *   config_prefix = "api_query_param_type",
 *   bundle_of = "api_query_param",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_query_param/add",
 *     "edit-form" = "/admin/structure/api_query_param/manage/{api_query_param_type}",
 *     "delete-form" = "/admin/structure/api_query_param/manage/{api_query_param_type}/delete",
 *     "collection" = "/admin/structure/api_query_param"
 *   },
 * )
 */
class APIQueryParamType extends ConfigEntityBundleBase implements APIQueryParamTypeInterface {

  /**
   * The API Query Param type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Query Param type label.
   *
   * @var string
   */
  protected $label;

}
