<?php

namespace Drupal\dp_docs\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the VendorExtensionRequiredParts constraint.
 */
class VendorExtensionRequiredPartsConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    /** @var \Drupal\dp_docs\Plugin\Field\FieldType\VendorExtension $value */
    if (isset($value)) {
      $value_value = $value->getValue();
      // Disallow having empty parts.
      if (empty($value_value['name']) || empty($value_value['value'])) {
        $this->context->addViolation($constraint->message);
      }
    }
  }

}
