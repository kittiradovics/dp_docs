<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\dp_docs\Traits\AutoLabelTrait;
use Drupal\dp_docs\Traits\APIRefRefTrait;
use Drupal\dp_docs\APIParamItemInterface;
use Drupal\dp_docs\Traits\ItemTrait;
use Drupal\dp_docs\Traits\APIVersionTagRefTrait;
use Drupal\dp_docs\Traits\URLRouteParametersTrait;
use Drupal\dp_docs\Traits\VendorExtensionTrait;

/**
 * Defines the API Param Item entity class.
 *
 * @ContentEntityType(
 *   id = "api_param_item",
 *   label = @Translation("API HTTP Method Parameter Item"),
 *   handlers = {
 *     "storage" = "Drupal\dp_docs\APIParamItemStorage",
 *     "list_builder" = "Drupal\dp_docs\APIParamItemListBuilder",
 *     "view_builder" = "Drupal\dp_docs\DPDocsContentEntityViewBuilder",
 *     "views_data" = "Drupal\dp_docs\APIParamItemViewsData",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\Form\DPDocsContentEntityForm",
 *       "add" = "Drupal\dp_docs\Form\DPDocsContentEntityForm",
 *       "edit" = "Drupal\dp_docs\Form\DPDocsContentEntityForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsContentEntityDeleteForm",
 *     },
 *     "inline_form" = "Drupal\dp_docs\Form\DPDocsInlineForm",
 *     "route_provider" = {
 *       "html" = "Drupal\dp_docs\APIParamItemHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\dp_docs\APIParamItemAccessControlHandler",
 *     "translation" = "Drupal\dp_docs\APIParamItemTranslationHandler",
 *   },
 *   admin_permission = "administer api param items",
 *   fieldable = TRUE,
 *   base_table = "api_param_item",
 *   data_table = "api_param_item_field_data",
 *   field_ui_base_route = "entity.api_param_item_type.edit_form",
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
 *     "item" = {
 *       "type" = "param_type",
 *       "format" = "format",
 *       "items" = "api_param_item",
 *       "collection_format" = "collection_format",
 *       "default" = "param_default",
 *     },
 *     "vendor_extension" = "extensions",
 *     "api_ref" = "api_ref",
 *     "api_version_tag" = "api_version_tag",
 *   },
 *   bundle_entity_type = "api_param_item_type",
 *   bundle_label = @Translation("API HTTP Method Parameter Item type"),
 *   revision_table = "api_param_item_revision",
 *   revision_data_table = "api_param_item_field_revision",
 *   show_revision_ui = TRUE,
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   links = {
 *     "canonical" = "/api_param_item/{api_param_item}",
 *     "add-page" = "/api_param_item/add",
 *     "add-form" = "/api_param_item/add/{api_param_item_type}",
 *     "edit-form" = "/api_param_item/{api_param_item}/edit",
 *     "delete-form" = "/api_param_item/{api_param_item}/delete",
 *     "collection" = "/admin/content/api_param_item",
 *     "version-history" = "/api_param_item/{api_param_item}/revisions",
 *     "revision" = "/api_param_item/{api_param_item}/revisions/{api_param_item_revision}/view",
 *     "revision_revert" = "/api_param_item/{api_param_item}/revisions/{api_param_item_revision}/revert",
 *     "revision_delete" = "/api_param_item/{api_param_item}/revisions/{api_param_item_revision}/delete",
 *     "multiple_delete_confirm" = "/admin/content/api_param_item/delete",
 *     "translation_revert" = "/api_param_item/{api_param_item}/revisions/{api_param_item_revision}/revert/{langcode}",
 *   },
 *   translatable = TRUE,
 * )
 */
class APIParamItem extends RevisionableContentEntityBase implements APIParamItemInterface {

  use EntityChangedTrait;
  use AutoLabelTrait;
  use APIRefRefTrait;
  use ItemTrait;
  use APIVersionTagRefTrait;
  use URLRouteParametersTrait;
  use VendorExtensionTrait;

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
      // If we are updating an existing APIParamItem without adding a new
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
    return $this->get('param_type')->value;
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

    // Add the API Reference field.
    $fields += static::apiRefBaseFieldDefinitions($entity_type);
    $fields['api_ref']->setDescription(t('API Reference referenced from API Parameter Item.'));

    // Add the item fields.
    $fields += static::itemBaseFieldDefinitions($entity_type);
    $fields['api_param_item']->setDescription(t('API Parameter Item referenced from API Parameter Item.'));

    // Add the Version Tag field.
    $fields += static::apiVersionTagBaseFieldDefinitions($entity_type);
    $fields['api_version_tag']->setDescription(t('API Version Tag referenced from API Parameter Item.'));

    $fields['id']->setDescription(t('The API HTTP Method Parameter Item ID.'));

    $fields['uuid']->setDescription(t('The API HTTP Method Parameter Item UUID.'));

    $fields['vid']->setDescription(t('The API HTTP Method Parameter Item revision ID.'));

    $fields['langcode']->setDescription(t('The API HTTP Method Parameter Item language code.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the API HTTP Method Parameter Item was last edited.'))
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