<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APISchemaTypeInterface;

/**
 * Defines the API Schema type entity.
 *
 * @ConfigEntityType(
 *   id = "api_schema_type",
 *   label = @Translation("API Schema type"),
 *   label_collection = @Translation("API Schema types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APISchemaTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APISchemaTypeForm",
 *       "add" = "Drupal\dp_docs\APISchemaTypeForm",
 *       "edit" = "Drupal\dp_docs\APISchemaTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api schema types",
 *   config_prefix = "api_schema_type",
 *   bundle_of = "api_schema",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_schema/add",
 *     "edit-form" = "/admin/structure/api_schema/manage/{api_schema_type}",
 *     "delete-form" = "/admin/structure/api_schema/manage/{api_schema_type}/delete",
 *     "collection" = "/admin/structure/api_schema"
 *   },
 * )
 */
class APISchemaType extends ConfigEntityBundleBase implements APISchemaTypeInterface {

  /**
   * The API Schema type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Schema type label.
   *
   * @var string
   */
  protected $label;

}
