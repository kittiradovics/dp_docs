<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIRefTypeInterface;

/**
 * Defines the API Reference type entity.
 *
 * @ConfigEntityType(
 *   id = "api_ref_type",
 *   label = @Translation("API Reference type"),
 *   label_collection = @Translation("API Reference types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIRefTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIRefTypeForm",
 *       "add" = "Drupal\dp_docs\APIRefTypeForm",
 *       "edit" = "Drupal\dp_docs\APIRefTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api ref types",
 *   config_prefix = "api_ref_type",
 *   bundle_of = "api_ref",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "filtered_extensions",
 *     "common_extensions",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_ref/add",
 *     "edit-form" = "/admin/structure/api_ref/manage/{api_ref_type}",
 *     "delete-form" = "/admin/structure/api_ref/manage/{api_ref_type}/delete",
 *     "collection" = "/admin/structure/api_ref"
 *   },
 * )
 */
class APIRefType extends ConfigEntityBundleBase implements APIRefTypeInterface {

  /**
   * Machine name.
   *
   * @var string
   */
  public $id;

  /**
   * Human-readable label.
   *
   * @var string
   */
  public $label;

  /**
   * Human-readable description.
   *
   * @var string
   */
  public $description;

  /**
   * Restricted extension list.
   *
   * @var string[]
   */
  public $filtered_extensions;

  /**
   * Full extension list.
   *
   * @var string[]
   */
  public $common_extensions;

}
