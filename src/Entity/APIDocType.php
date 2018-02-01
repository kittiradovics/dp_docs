<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIDocTypeInterface;

/**
 * Defines the API Documentation type entity.
 *
 * @ConfigEntityType(
 *   id = "api_doc_type",
 *   label = @Translation("API Documentation type"),
 *   label_collection = @Translation("API Documentation types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIDocTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIDocTypeForm",
 *       "add" = "Drupal\dp_docs\APIDocTypeForm",
 *       "edit" = "Drupal\dp_docs\APIDocTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api doc types",
 *   config_prefix = "api_doc_type",
 *   bundle_of = "api_doc",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_doc/add",
 *     "edit-form" = "/admin/structure/api_doc/manage/{api_doc_type}",
 *     "delete-form" = "/admin/structure/api_doc/manage/{api_doc_type}/delete",
 *     "collection" = "/admin/structure/api_doc"
 *   },
 * )
 */
class APIDocType extends ConfigEntityBundleBase implements APIDocTypeInterface {

  /**
   * The API Doc type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Doc type label.
   *
   * @var string
   */
  protected $label;

}
