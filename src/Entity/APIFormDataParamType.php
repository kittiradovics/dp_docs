<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dp_docs\APIFormDataParamTypeInterface;

/**
 * Defines the API Form Data Param type entity.
 *
 * @ConfigEntityType(
 *   id = "api_form_data_param_type",
 *   label = @Translation("API HTTP Method Form Data Parameter type"),
 *   label_collection = @Translation("API HTTP Method Form Data Parameter types"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIFormDataParamTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\APIFormDataParamTypeForm",
 *       "add" = "Drupal\dp_docs\APIFormDataParamTypeForm",
 *       "edit" = "Drupal\dp_docs\APIFormDataParamTypeForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer api form data param types",
 *   config_prefix = "api_form_data_param_type",
 *   bundle_of = "api_form_data_param",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/api_form_data_param/add",
 *     "edit-form" = "/admin/structure/api_form_data_param/manage/{api_form_data_param_type}",
 *     "delete-form" = "/admin/structure/api_form_data_param/manage/{api_form_data_param_type}/delete",
 *     "collection" = "/admin/structure/api_form_data_param"
 *   },
 * )
 */
class APIFormDataParamType extends ConfigEntityBundleBase implements APIFormDataParamTypeInterface {

  /**
   * The API Form Data Param type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The API Form Data Param type label.
   *
   * @var string
   */
  protected $label;

}
