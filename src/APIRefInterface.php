<?php

namespace Drupal\dp_docs;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\dp_migrate_batch\Batch\MigrationGeneratorInterface;
use Drupal\user\EntityOwnerInterface;

interface APIRefInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, EntityOwnerInterface, MigrationGeneratorInterface {

}
