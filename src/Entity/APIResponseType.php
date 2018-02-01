<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIResponseTypeInterface;

/**
 * Defines the API Response type entity.
 *
 * @ConfigEntityType(
 *   id = "api_response_type",
 *   label = @Translation("API Response type"),
 *   label_collection = @Translation("API Response types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIResponseTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIResponseTypeForm",
 *       "add" = "Drupal\dp_docs\APIResponseTypeForm",
 *       "edit" = "Drupal\dp_docs\APIResponseTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api response types",
 *   config_prefix = "api_response_type",
 *   bundle_of = "api_response",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_response/add",
 *     "edit-form" = "/admin/structure/api_response/manage/{api_response_type}",
 *     "delete-form" = "/admin/structure/api_response/manage/{api_response_type}/delete",
 *     "collection" = "/admin/structure/api_response"
 *   },
 * )
 */
class APIResponseType extends ConfigEntityBundleBase implements APIResponseTypeInterface {

  /**
   * The API Response type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Response type label.
   *
   * @var string
   */
  protected $label;

}
