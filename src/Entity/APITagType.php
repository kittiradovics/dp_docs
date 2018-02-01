<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APITagTypeInterface;

/**
 * Defines the API Tag type entity.
 *
 * @ConfigEntityType(
 *   id = "api_tag_type",
 *   label = @Translation("API Tag type"),
 *   label_collection = @Translation("API Tag types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APITagTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APITagTypeForm",
 *       "add" = "Drupal\dp_docs\APITagTypeForm",
 *       "edit" = "Drupal\dp_docs\APITagTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api tag types",
 *   config_prefix = "api_tag_type",
 *   bundle_of = "api_tag",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_tag/add",
 *     "edit-form" = "/admin/structure/api_tag/manage/{api_tag_type}",
 *     "delete-form" = "/admin/structure/api_tag/manage/{api_tag_type}/delete",
 *     "collection" = "/admin/structure/api_tag"
 *   },
 * )
 */
class APITagType extends ConfigEntityBundleBase implements APITagTypeInterface {

  /**
   * The API Tag type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Tag type label.
   *
   * @var string
   */
  protected $label;

}
