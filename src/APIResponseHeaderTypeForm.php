<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\language\Entity\ContentLanguageSettings;

/**
 * Class APIResponseHeaderTypeForm.
 *
 * @package Drupal\dp_docs\Form
 */
class APIResponseHeaderTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $api_response_header_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $api_response_header_type->label(),
      '#description' => $this->t("Label for the API Response Header type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $api_response_header_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\dp_docs\Entity\APIResponseHeaderType::load',
      ],
      '#disabled' => !$api_response_header_type->isNew(),
    ];

    // $form['langcode'] is not wrapped in an
    // if ($this->moduleHandler->moduleExists('language')) check because the
    // language_select form element works also without the language module being
    // installed. https://www.drupal.org/node/1749954 documents the new element.
    $form['langcode'] = [
      '#type' => 'language_select',
      '#title' => $this->t('API Response Header type language'),
      '#languages' => LanguageInterface::STATE_ALL,
      '#default_value' => $api_response_header_type->language()->getId(),
    ];
    if ($this->moduleHandler->moduleExists('language')) {
      $form['default_terms_language'] = [
        '#type' => 'details',
        '#title' => $this->t('API Response Headers language'),
        '#open' => TRUE,
      ];
      $form['default_terms_language']['default_language'] = [
        '#type' => 'language_configuration',
        '#entity_information' => [
          'entity_type' => 'api_response_header',
          'bundle' => $api_response_header_type->id(),
        ],
        '#default_value' => ContentLanguageSettings::loadByEntityTypeBundle('api_response_header', $api_response_header_type->id()),
      ];
    }

    switch ($this->operation) {
      case 'edit':
        $form['#title'] = $this->t('Edit API Response Header type %label', ['%label' => $api_response_header_type->label()]);
        break;

      case 'add':
        $form['#title'] = $this->t('Add API Response Header type');
        break;
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $api_response_header_type = $this->entity;
    $status = $api_response_header_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label API Response Header type.', [
          '%label' => $api_response_header_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label API Response Header type.', [
          '%label' => $api_response_header_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($api_response_header_type->toUrl('collection'));
  }

}
