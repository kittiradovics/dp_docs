<?php

namespace Drupal\dp_docs\Traits;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Provides a trait for automatic labels.
 */
trait AutoLabelTrait {

  /**
   * Determines if the label is (to be) autogenerated.
   *
   * @return bool
   *   TRUE if the label is (to be) autogenerated, FALSE otherwise.
   */
  public function hasAutogeneratedLabel() {
    $additional = $this->getEntityType()->get('additional');
    return $this->get($additional['api_extra_info']['auto_label']['autogenerated_label'])->value;
  }

  /**
   * Changes whether the label is (to be) autogenerated or not.
   *
   * @param bool $autogenerated_label
   *   TRUE if the label is (to be) autogenerated, FALSE otherwise.
   *
   * @return $this
   */
  public function setAutogeneratedLabel($autogenerated_label) {
    $additional = $this->getEntityType()->get('additional');
    $this->set($additional['api_extra_info']['auto_label']['autogenerated_label'], $autogenerated_label);
    return $this;
  }

  /**
   * Gets the auto label.
   *
   * @return string
   *   The auto label value.
   */
  public function getAutoLabel() {
    $additional = $this->getEntityType()->get('additional');
    return $this->get($additional['api_extra_info']['auto_label']['auto_label'])->value;
  }

  /**
   * Sets the auto label.
   *
   * @param string $auto_label
   *   The value of the auto label.
   *
   * @return $this
   */
  public function setAutoLabel($auto_label) {
    $additional = $this->getEntityType()->get('additional');
    $this->set($additional['api_extra_info']['auto_label']['auto_label'], $auto_label);
    return $this;
  }

  /**
   * Provides auto label related base field definitions for an entity type.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   *   An array of base field definitions for the entity type, keyed by field
   *   name.
   *
   * @see \Drupal\Core\Entity\FieldableEntityInterface::baseFieldDefinitions()
   * @see \Drupal\dp_docs\TrAutoLabelInterface::autoLabelBaseFieldDefinitions()
   */
  public static function autoLabelBaseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = [];
    $additional = $entity_type->get('additional');
    $fields[$additional['api_extra_info']['auto_label']['autogenerated_label']] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Autogenerate label'))
      ->setDescription(t('Automatically generate the label.'))
      ->setDefaultValue(TRUE)
      ->setRevisionable(TRUE)
      ->setSettings([
        'on_label' => t('Yes'),
        'off_label' => t('No'),
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'boolean',
        'settings' => [
          'format' => 'default',
        ],
        'weight' => -10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => TRUE,
        ],
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE);
    $fields[$additional['api_extra_info']['auto_label']['auto_label']] = BaseFieldDefinition::create('string')
      ->setLabel(t('Auto label'))
      ->setDescription(t('Automatically generated label. Only set in that case if you want to override the automatic label generation.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE)
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
    return $fields;
  }

  /**
   * Acts on an entity before saving.
   *
   * This function should be called in the entity's preSave() function.
   */
  public function autoLabelPreSave() {
    if ($this->hasAutogeneratedLabel() || empty($this->getAutoLabel())) {
      $this->setAutoLabel($this->generateAutoLabel());
      $this->setAutogeneratedLabel(TRUE);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateAutoLabel() {
    $additional = $this->getEntityType()->get('additional');
    return $this->get($additional['api_extra_info']['auto_label_source'])->value;
  }

}