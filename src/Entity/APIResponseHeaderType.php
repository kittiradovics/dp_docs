<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIResponseHeaderTypeInterface;

/**
 * Defines the API Response Header type entity.
 *
 * @ConfigEntityType(
 *   id = "api_response_header_type",
 *   label = @Translation("API Response Header type"),
 *   label_collection = @Translation("API Response Header types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIResponseHeaderTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIResponseHeaderTypeForm",
 *       "add" = "Drupal\dp_docs\APIResponseHeaderTypeForm",
 *       "edit" = "Drupal\dp_docs\APIResponseHeaderTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api response header types",
 *   config_prefix = "api_response_header_type",
 *   bundle_of = "api_response_header",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_response_header/add",
 *     "edit-form" = "/admin/structure/api_response_header/manage/{api_response_header_type}",
 *     "delete-form" = "/admin/structure/api_response_header/manage/{api_response_header_type}/delete",
 *     "collection" = "/admin/structure/api_response_header"
 *   },
 * )
 */
class APIResponseHeaderType extends ConfigEntityBundleBase implements APIResponseHeaderTypeInterface {

  /**
   * The API Response Header type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Response Header type label.
   *
   * @var string
   */
  protected $label;

}
