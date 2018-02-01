<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIGlobalResponseTypeInterface;

/**
 * Defines the API Global Response type entity.
 *
 * @ConfigEntityType(
 *   id = "api_global_response_type",
 *   label = @Translation("API Global Response type"),
 *   label_collection = @Translation("API Global Response types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIGlobalResponseTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIGlobalResponseTypeForm",
 *       "add" = "Drupal\dp_docs\APIGlobalResponseTypeForm",
 *       "edit" = "Drupal\dp_docs\APIGlobalResponseTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api global response types",
 *   config_prefix = "api_global_response_type",
 *   bundle_of = "api_global_response",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_global_response/add",
 *     "edit-form" = "/admin/structure/api_global_response/manage/{api_global_response_type}",
 *     "delete-form" = "/admin/structure/api_global_response/manage/{api_global_response_type}/delete",
 *     "collection" = "/admin/structure/api_global_response"
 *   },
 * )
 */
class APIGlobalResponseType extends ConfigEntityBundleBase implements APIGlobalResponseTypeInterface {

  /**
   * The API Global Response type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Global Response type label.
   *
   * @var string
   */
  protected $label;

}
