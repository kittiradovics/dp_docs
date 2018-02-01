<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\dp_docs\APIExtDocInterface;
use Drupal\dp_docs\Traits\APIRefRefTrait;
use Drupal\dp_docs\Traits\AutoLabelTrait;
use Drupal\dp_docs\Traits\URLRouteParametersTrait;
use Drupal\dp_docs\Traits\VendorExtensionTrait;
use Drupal\link\Plugin\Field\FieldType\LinkItem;
use Drupal\dp_docs\Traits\APIVersionTagRefTrait;

/**
 * Defines the API Ext Doc entity class.
 *
 * @ContentEntityType(
 *   id = "api_ext_doc",
 *   label = @Translation("API External Documentation"),
 *   handlers = {
 *     "storage" = "Drupal\dp_docs\APIExtDocStorage",
 *     "list_builder" = "Drupal\dp_docs\APIExtDocListBuilder",
 *     "view_builder" = "Drupal\dp_docs\DPDocsContentEntityViewBuilder",
 *     "views_data" = "Drupal\dp_docs\APIExtDocViewsData",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\Form\DPDocsContentEntityForm",
 *       "add" = "Drupal\dp_docs\Form\DPDocsContentEntityForm",
 *       "edit" = "Drupal\dp_docs\Form\DPDocsContentEntityForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsContentEntityDeleteForm",
 *     },
 *     "inline_form" = "Drupal\dp_docs\Form\DPDocsInlineForm",
 *     "route_provider" = {
 *       "html" = "Drupal\dp_docs\APIExtDocHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\dp_docs\APIExtDocAccessControlHandler",
 *     "translation" = "Drupal\dp_docs\APIExtDocTranslationHandler",
 *   },
 *   admin_permission = "administer api ext docs",
 *   fieldable = TRUE,
 *   base_table = "api_ext_doc",
 *   data_table = "api_ext_doc_field_data",
 *   field_ui_base_route = "entity.api_ext_doc_type.edit_form",
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
 *     "api_ref" = "api_ref",
 *     "api_version_tag" = "api_version_tag",
 *   },
 *   bundle_entity_type = "api_ext_doc_type",
 *   bundle_label = @Translation("API External Documentation type"),
 *   revision_table = "api_ext_doc_revision",
 *   revision_data_table = "api_ext_doc_field_revision",
 *   show_revision_ui = TRUE,
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   links = {
 *     "canonical" = "/api_ext_doc/{api_ext_doc}",
 *     "add-page" = "/api_ext_doc/add",
 *     "add-form" = "/api_ext_doc/add/{api_ext_doc_type}",
 *     "edit-form" = "/api_ext_doc/{api_ext_doc}/edit",
 *     "delete-form" = "/api_ext_doc/{api_ext_doc}/delete",
 *     "collection" = "/admin/content/api_ext_doc",
 *     "version-history" = "/api_ext_doc/{api_ext_doc}/revisions",
 *     "revision" = "/api_ext_doc/{api_ext_doc}/revisions/{api_ext_doc_revision}/view",
 *     "revision_revert" = "/api_ext_doc/{api_ext_doc}/revisions/{api_ext_doc_revision}/revert",
 *     "revision_delete" = "/api_ext_doc/{api_ext_doc}/revisions/{api_ext_doc_revision}/delete",
 *     "multiple_delete_confirm" = "/admin/content/api_ext_doc/delete",
 *     "translation_revert" = "/api_ext_doc/{api_ext_doc}/revisions/{api_ext_doc_revision}/revert/{langcode}",
 *   },
 *   translatable = TRUE,
 * )
 */
class APIExtDoc extends RevisionableContentEntityBase implements APIExtDocInterface {

  use EntityChangedTrait;
  use AutoLabelTrait;
  use VendorExtensionTrait;
  use APIRefRefTrait;
  use APIVersionTagRefTrait;
  use URLRouteParametersTrait;

  /**
   * {@inheritdoc}
   */
  public function getURL() {
    return $this->get('url')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setURL($url) {
    $this->set('url', $url);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->get('description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->set('description', $description);
    return $this;
  }

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
      // If we are updating an existing APIExtDoc without adding a new revision,
      // we need to make sure $entity->revision_log is reset whenever it is
      // empty. Therefore, this code allows us to avoid clobbering an existing
      // log entry with an empty one.
      $record->revision_log = $this->original->revision_log->value;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateAutoLabel() {
    if ($this->get('description')->isEmpty()) {
      // @TODO Better to use a required field as the auto_label_source.
      return 'PLACEHOLDER';
    }
    return $this->get('description')->value;
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

    // Add the API Reference fields.
    $fields += static::apiRefBaseFieldDefinitions($entity_type);
    $fields['api_ref']->setDescription(t('API Reference referenced from API External Documentation.'));

    // Add the API Version Tag fields.
    $fields += static::apiVersionTagBaseFieldDefinitions($entity_type);
    $fields['api_version_tag']->setDescription(t('API Version Tag referenced from API External Documentation.'));

    $fields['id']->setDescription(t('The API External Documentation ID.'));

    $fields['uuid']->setDescription(t('The API External Documentation UUID.'));

    $fields['vid']->setDescription(t('The API External Documentation revision ID.'));

    $fields['langcode']->setDescription(t('The API External Documentation language code.'));

    $fields['url'] = BaseFieldDefinition::create('link')
      ->setLabel(t('URL'))
      ->setRequired(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
      ->setSettings([
        'link_type' => LinkItem::LINK_EXTERNAL,
        'title' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'link_default',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'link',
        'weight' => -5,
        'settings' => [
          'trim_length' => 80,
          'url_only' => TRUE,
          'url_plain' => FALSE,
          'rel' => 'nofollow',
          'target' => '_blank',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'text_default',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the API External Documentation was last edited.'))
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
