<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\ItemInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;
use Drupal\dp_docs\Traits\VendorExtensionInterface;

interface APIParamItemInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, APIRefRefInterface, ItemInterface, APIVersionTagRefInterface, VendorExtensionInterface {

}
