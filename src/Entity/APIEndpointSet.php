<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\dp_docs\Traits\APIDocRefTrait;
use Drupal\dp_docs\Traits\APIRefRefTrait;
use Drupal\dp_docs\Traits\AutoLabelTrait;
use Drupal\dp_docs\APIEndpointSetInterface;
use Drupal\dp_docs\Traits\URLRouteParametersTrait;
use Drupal\dp_docs\Traits\VendorExtensionTrait;
use Drupal\dp_docs\Traits\APIVersionTagRefTrait;

/**
 * Defines the API Endpoint Set entity class.
 *
 * @ContentEntityType(
 *   id = "api_endpoint_set",
 *   label = @Translation("API Endpoint Set"),
 *   handlers = {
 *     "storage" = "Drupal\dp_docs\APIEndpointSetStorage",
 *     "list_builder" = "Drupal\dp_docs\APIEndpointSetListBuilder",
 *     "view_builder" = "Drupal\dp_docs\DPDocsContentEntityViewBuilder",
 *     "views_data" = "Drupal\dp_docs\APIEndpointSetViewsData",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\Form\DPDocsContentEntityForm",
 *       "add" = "Drupal\dp_docs\Form\DPDocsContentEntityForm",
 *       "edit" = "Drupal\dp_docs\Form\DPDocsContentEntityForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsContentEntityDeleteForm",
 *     },
 *     "inline_form" = "Drupal\dp_docs\Form\DPDocsInlineForm",
 *     "route_provider" = {
 *       "html" = "Drupal\dp_docs\APIEndpointSetHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\dp_docs\APIEndpointSetAccessControlHandler",
 *     "translation" = "Drupal\dp_docs\APIEndpointSetTranslationHandler",
 *   },
 *   admin_permission = "administer api endpoint sets",
 *   fieldable = TRUE,
 *   base_table = "api_endpoint_set",
 *   data_table = "api_endpoint_set_field_data",
 *   field_ui_base_route = "entity.api_endpoint_set_type.edit_form",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "uuid" = "uuid",
 *     "revision" = "vid",
 *     "langcode" = "langcode",
 *     "label" = "auto_label",
 *   },
 *   api_extra_info = {
 *     "auto_label" = {
 *       "auto_label" = "auto_label",
 *       "autogenerated_label" = "autogenerated_label",
 *     },
 *     "vendor_extension" = "extensions",
 *     "api_doc" = "api_doc",
 *     "api_ref" = "api_ref",
 *     "api_version_tag" = "api_version_tag",
 *   },
 *   bundle_entity_type = "api_endpoint_set_type",
 *   bundle_label = @Translation("API Endpoint Set type"),
 *   revision_table = "api_endpoint_set_revision",
 *   revision_data_table = "api_endpoint_set_field_revision",
 *   show_revision_ui = TRUE,
 *   revision_metadata_keys = {
 *     "revision_created" = "revision_created",
 *     "revision_user" = "revision_user",
 *     "revision_log_message" = "revision_log",
 *   },
 *   links = {
 *     "canonical" = "/api_endpoint_set/{api_endpoint_set}",
 *     "add-page" = "/api_endpoint_set/add",
 *     "add-form" = "/api_endpoint_set/add/{api_endpoint_set_type}",
 *     "edit-form" = "/api_endpoint_set/{api_endpoint_set}/edit",
 *     "delete-form" = "/api_endpoint_set/{api_endpoint_set}/delete",
 *     "collection" = "/admin/content/api_endpoint_set",
 *     "version-history" = "/api_endpoint_set/{api_endpoint_set}/revisions",
 *     "revision" = "/api_endpoint_set/{api_endpoint_set}/revisions/{api_endpoint_set_revision}/view",
 *     "revision_revert" = "/api_endpoint_set/{api_endpoint_set}/revisions/{api_endpoint_set_revision}/revert",
 *     "revision_delete" = "/api_endpoint_set/{api_endpoint_set}/revisions/{api_endpoint_set_revision}/delete",
 *     "multiple_delete_confirm" = "/admin/content/api_endpoint_set/delete",
 *     "translation_revert" = "/api_endpoint_set/{api_endpoint_set}/revisions/{api_endpoint_set_revision}/revert/{langcode}",
 *   },
 *   translatable = TRUE,
 * )
 */
class APIEndpointSet extends RevisionableContentEntityBase implements APIEndpointSetInterface {

  use EntityChangedTrait;
  use VendorExtensionTrait;
  use AutoLabelTrait;
  use APIDocRefTrait;
  use APIRefRefTrait;
  use APIVersionTagRefTrait;
  use URLRouteParametersTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    // Generate auto label.
    $this->autoLabelPreSave();
  }

  /**
   * {@inheritdoc}
   */
  public function preSaveRevision(EntityStorageInterface $storage, \stdClass $record) {
    parent::preSaveRevision($storage, $record);

    if (!$this->isNewRevision() && isset($this->original) && (!isset($record->revision_log) || $record->revision_log === '')) {
      // If we are updating an existing APIEndpointSet without adding a new
      // revision, we need to make sure $entity->revision_log is reset whenever
      // it is empty. Therefore, this code allows us to avoid clobbering an
      // existing log entry with an empty one.
      $record->revision_log = $this->original->revision_log->value;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateAutoLabel() {
    // Get the ID of the referenced API Reference entity.
    $api_ref_id = $this->get('api_ref')->getValue()[0]['target_id'];
    // Find the API Documentation entity which refers to the same API Reference
    // entity.
    $query = \Drupal::entityQuery('api_doc')
      ->condition('api_ref', $api_ref_id);
    $api_doc_ids = $query->execute();
    /** @var \Drupal\dp_docs\Entity\APIDoc $api_doc */
    $api_doc = APIDoc::load(reset($api_doc_ids));

    return t('Endpoints of @api_doc', ['@api_doc' => $api_doc->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the auto label field.
    $fields += static::autoLabelBaseFieldDefinitions($entity_type);

    // Add the vendor extension fields.
    $fields += static::vendorExtensionBaseFieldDefinitions($entity_type);

    // Add the API Documentation field.
    $fields += static::apiDocBaseFieldDefinitions($entity_type);
    $fields['api_doc']->setDescription(t('API Documentation referenced from API Endpoint Set.'));

    // Add the API Reference field.
    $fields += static::apiRefBaseFieldDefinitions($entity_type);
    $fields['api_ref']->setDescription(t('API Reference referenced from API Endpoint Set.'));

    // Add the API Version Tag field.
    $fields += static::apiVersionTagBaseFieldDefinitions($entity_type);
    $fields['api_version_tag']->setDescription(t('API Version Tag referenced from API Endpoint Set.'));

    $fields['id']->setDescription(t('The API Endpoint Set ID.'));

    $fields['uuid']->setDescription(t('The API Endpoint Set UUID.'));

    $fields['vid']->setDescription(t('The API Endpoint Set revision ID.'));

    $fields['langcode']->setDescription(t('The API Endpoint Set language code.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the API Endpoint Set was last edited.'))
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    $label = parent::label();
    if (empty($label)) {
      $label = $this->generateAutoLabel();
    }
    return $label;
  }

}
