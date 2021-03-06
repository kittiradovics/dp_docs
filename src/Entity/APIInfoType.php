<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIInfoTypeInterface;

/**
 * Defines the API Info type entity.
 *
 * @ConfigEntityType(
 *   id = "api_info_type",
 *   label = @Translation("API Info type"),
 *   label_collection = @Translation("API Info types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIInfoTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIInfoTypeForm",
 *       "add" = "Drupal\dp_docs\APIInfoTypeForm",
 *       "edit" = "Drupal\dp_docs\APIInfoTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api doc types",
 *   config_prefix = "api_info_type",
 *   bundle_of = "api_info",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_info/add",
 *     "edit-form" = "/admin/structure/api_info/manage/{api_info_type}",
 *     "delete-form" = "/admin/structure/api_info/manage/{api_info_type}/delete",
 *     "collection" = "/admin/structure/api_info"
 *   },
 * )
 */
class APIInfoType extends ConfigEntityBundleBase implements APIInfoTypeInterface {

  /**
   * The API Info type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Info type label.
   *
   * @var string
   */
  protected $label;

}
