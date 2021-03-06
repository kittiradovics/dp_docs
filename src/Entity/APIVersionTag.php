<?php

namespace Drupal\dp_docs\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\dp_docs\Traits\APIRefRefTrait;
use Drupal\dp_docs\Traits\AutoLabelTrait;
use Drupal\dp_docs\APIVersionTagInterface;

/**
 * Defines the API Version Tag entity class.
 *
 * @ContentEntityType(
 *   id = "api_version_tag",
 *   label = @Translation("API Version Tag"),
 *   handlers = {
 *     "list_builder" = "Drupal\dp_docs\APIVersionTagListBuilder",
 *     "view_builder" = "Drupal\dp_docs\DPDocsContentEntityViewBuilder",
 *     "views_data" = "Drupal\dp_docs\APIVersionTagViewsData",
 *     "form" = {
 *       "default" = "Drupal\dp_docs\Form\APIVersionTagForm",
 *       "add" = "Drupal\dp_docs\Form\APIVersionTagForm",
 *       "edit" = "Drupal\dp_docs\Form\APIVersionTagForm",
 *       "delete" = "Drupal\dp_docs\Form\DPDocsContentEntityDeleteForm",
 *     },
 *     "inline_form" = "Drupal\dp_docs\Form\DPDocsInlineForm",
 *     "route_provider" = {
 *       "html" = "Drupal\dp_docs\APIVersionTagHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\dp_docs\APIVersionTagAccessControlHandler",
 *     "translation" = "Drupal\dp_docs\APIVersionTagTranslationHandler",
 *   },
 *   admin_permission = "administer api version tags",
 *   fieldable = TRUE,
 *   base_table = "api_version_tag",
 *   data_table = "api_version_tag_field_data",
 *   field_ui_base_route = "entity.api_version_tag_type.edit_form",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "label" = "auto_label",
 *   },
 *   api_extra_info = {
 *     "auto_label" = {
 *       "auto_label" = "auto_label",
 *       "autogenerated_label" = "autogenerated_label",
 *     },
 *     "auto_label_source" = "name",
 *     "api_ref" = "api_ref",
 *   },
 *   bundle_entity_type = "api_version_tag_type",
 *   bundle_label = @Translation("API Version Tag type"),
 *   links = {
 *     "canonical" = "/api_version_tag/{api_version_tag}",
 *     "add-page" = "/api_version_tag/add",
 *     "add-form" = "/api_version_tag/add/{api_version_tag_type}",
 *     "edit-form" = "/api_version_tag/{api_version_tag}/edit",
 *     "delete-form" = "/api_version_tag/{api_version_tag}/delete",
 *     "collection" = "/admin/content/api_version_tag",
 *     "multiple_delete_confirm" = "/admin/content/api_version_tag/delete",
 *   },
 *   translatable = TRUE,
 * )
 */
class APIVersionTag extends ContentEntityBase implements APIVersionTagInterface {

  use EntityChangedTrait;
  use AutoLabelTrait;
  use APIRefRefTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
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
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the auto label field.
    $fields += static::autoLabelBaseFieldDefinitions($entity_type);

    // Add the API Reference field.
    $fields += static::apiRefBaseFieldDefinitions($entity_type);
    $fields['api_ref']->setDescription(t('API Reference referenced from API Version Tag.'));

    $fields['id']->setDescription(t('The API Version Tag ID.'));

    $fields['uuid']->setDescription(t('The API Version Tag UUID.'));

    $fields['langcode']->setDescription(t('The API Version Tag language code.'));

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the API Version Tag was last edited.'))
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
