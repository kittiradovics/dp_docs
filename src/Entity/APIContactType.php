<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIContactTypeInterface;

/**
 * Defines the API Contact type entity.
 *
 * @ConfigEntityType(
 *   id = "api_contact_type",
 *   label = @Translation("API Contact type"),
 *   label_collection = @Translation("API Contact types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIContactTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIContactTypeForm",
 *       "add" = "Drupal\dp_docs\APIContactTypeForm",
 *       "edit" = "Drupal\dp_docs\APIContactTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api contact types",
 *   config_prefix = "api_contact_type",
 *   bundle_of = "api_contact",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_contact/add",
 *     "edit-form" = "/admin/structure/api_contact/manage/{api_contact_type}",
 *     "delete-form" = "/admin/structure/api_contact/manage/{api_contact_type}/delete",
 *     "collection" = "/admin/structure/api_contact"
 *   },
 * )
 */
class APIContactType extends ConfigEntityBundleBase implements APIContactTypeInterface {

  /**
   * The API Contact type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Contact type label.
   *
   * @var string
   */
  protected $label;

}
