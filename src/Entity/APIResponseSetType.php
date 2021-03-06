<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIResponseSetTypeInterface;

/**
 * Defines the API Response Set type entity.
 *
 * @ConfigEntityType(
 *   id = "api_response_set_type",
 *   label = @Translation("API Response Set type"),
 *   label_collection = @Translation("API Response Set types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIResponseSetTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIResponseSetTypeForm",
 *       "add" = "Drupal\dp_docs\APIResponseSetTypeForm",
 *       "edit" = "Drupal\dp_docs\APIResponseSetTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api response set types",
 *   config_prefix = "api_response_set_type",
 *   bundle_of = "api_response_set",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_response_set/add",
 *     "edit-form" = "/admin/structure/api_response_set/manage/{api_response_set_type}",
 *     "delete-form" = "/admin/structure/api_response_set/manage/{api_response_set_type}/delete",
 *     "collection" = "/admin/structure/api_response_set"
 *   },
 * )
 */
class APIResponseSetType extends ConfigEntityBundleBase implements APIResponseSetTypeInterface {

  /**
   * The API Response Set type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Response Set type label.
   *
   * @var string
   */
  protected $label;

}
