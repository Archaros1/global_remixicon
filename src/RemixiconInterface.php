<?php

namespace Drupal\global_remixicon;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a remixicon entity type.
 */
interface RemixiconInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
