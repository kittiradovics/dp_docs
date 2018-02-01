<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIVersionTagTypeInterface;

/**
 * Defines the API Version Tag type entity.
 *
 * @ConfigEntityType(
 *   id = "api_version_tag_type",
 *   label = @Translation("API Version Tag type"),
 *   label_collection = @Translation("API Version Tag types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIVersionTagTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIVersionTagTypeForm",
 *       "add" = "Drupal\dp_docs\APIVersionTagTypeForm",
 *       "edit" = "Drupal\dp_docs\APIVersionTagTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api version tag types",
 *   config_prefix = "api_version_tag_type",
 *   bundle_of = "api_version_tag",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_version_tag/add",
 *     "edit-form" = "/admin/structure/api_version_tag/manage/{api_version_tag_type}",
 *     "delete-form" = "/admin/structure/api_version_tag/manage/{api_version_tag_type}/delete",
 *     "collection" = "/admin/structure/api_version_tag"
 *   },
 * )
 */
class APIVersionTagType extends ConfigEntityBundleBase implements APIVersionTagTypeInterface {

  /**
   * The API Version Tag type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Version Tag type label.
   *
   * @var string
   */
  protected $label;

}
