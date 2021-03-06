<?php

/**
 * @file
 * Page callbacks for API Contact entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for API Contact templates.
 *
 * Default template: api-contact.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the API Contract information
 *     and any fields attached to it.
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_api_contact(array &$variables) {
  // Fetch API Contact Entity Object.
  /** @var \Drupal\dp_docs\Entity\APIContact $entity */
  $entity = $variables['elements']['#api_contact'];

  // Helpful classes for the wrapper.
  $variables['attributes']['class'][] = $entity->getEntityTypeId() . '--' . $entity->bundle();
  $variables['attributes']['class'][] = $entity->bundle();
  $variables['attributes']['class'][] = $entity->getEntityTypeId();

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

