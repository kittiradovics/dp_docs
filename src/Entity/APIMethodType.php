<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIMethodTypeInterface;

/**
 * Defines the API Method type entity.
 *
 * @ConfigEntityType(
 *   id = "api_method_type",
 *   label = @Translation("API HTTP Method type"),
 *   label_collection = @Translation("API HTTP Method types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIMethodTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIMethodTypeForm",
 *       "add" = "Drupal\dp_docs\APIMethodTypeForm",
 *       "edit" = "Drupal\dp_docs\APIMethodTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api method types",
 *   config_prefix = "api_method_type",
 *   bundle_of = "api_method",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_method/add",
 *     "edit-form" = "/admin/structure/api_method/manage/{api_method_type}",
 *     "delete-form" = "/admin/structure/api_method/manage/{api_method_type}/delete",
 *     "collection" = "/admin/structure/api_method"
 *   },
 * )
 */
class APIMethodType extends ConfigEntityBundleBase implements APIMethodTypeInterface {

  /**
   * The API Method type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Method type label.
   *
   * @var string
   */
  protected $label;

}
