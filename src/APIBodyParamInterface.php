<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_docs\Traits\APIRefRefInterface;
use Drupal\dp_docs\Traits\AutoLabelInterface;
use Drupal\dp_docs\Traits\MethodParameterInterface;
use Drupal\dp_docs\Traits\APIVersionTagRefInterface;

interface APIBodyParamInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, AutoLabelInterface, MethodParameterInterface, APIRefRefInterface, APIVersionTagRefInterface {

}
