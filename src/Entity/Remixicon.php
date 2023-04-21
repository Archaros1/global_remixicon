<?php

namespace Drupal\global_remixicon\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Form\FormStateInterface;
use Drupal\global_remixicon\RemixiconInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the remixicon entity class.
 *
 * @ContentEntityType(
 *   id = "remixicon",
 *   label = @Translation("RemixIcon"),
 *   label_collection = @Translation("RemixIcon"),
 *   label_singular = @Translation("remixicon"),
 *   label_plural = @Translation("remixicons"),
 *   label_count = @PluralTranslation(
 *     singular = "@count remixicon",
 *     plural = "@count remixicons",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\global_remixicon\RemixiconListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\global_remixicon\RemixiconAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\global_remixicon\Form\RemixiconForm",
 *       "edit" = "Drupal\global_remixicon\Form\RemixiconForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\global_remixicon\Routing\RemixiconHtmlRouteProvider",
 *     },
 *     "translation" = "Drupal\global_remixicon\RemixiconTranslationHandler"
 *   },
 *   base_table = "remixicon",
 *   data_table = "remixicon_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer remixicon",
 *   entity_keys = {
 *     "id" = "id",
 *     "class" = "class",
 *     "langcode" = "langcode",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid"
 *   },
 *   links = {
 *     "add-form" = "/remixicon/add",
 *     "canonical" = "/remixicon/{remixicon}",
 *     "edit-form" = "/remixicon/{remixicon}",
 *     "delete-form" = "/remixicon/{remixicon}/delete",
 *   },
 * )
 */
class Remixicon extends ContentEntityBase implements RemixiconInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setTranslatable(TRUE)
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['class'] = BaseFieldDefinition::create('string')
      ->setLabel(t("Classe de l'icône"))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

      $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setTranslatable(TRUE)
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(static::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the remixicon was created.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the remixicon was last edited.'));

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setName('langcode')
      ->setDefaultValue('x-default')// x-default is the sites default language.
      ->setStorageRequired(TRUE)
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code.'))
      ->setDisplayOptions('form', [
        'type' => 'language_select',
        'weight' => 90,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setTranslatable(TRUE);

    $fields['default_langcode'] = BaseFieldDefinition::create('boolean')
      ->setName('default_langcode')
      ->setLabel(t('Default Language code'))
      ->setDescription(t('Indicates if this is the default language.'))
      ->setDefaultValue(1) // Default this to 1.
      ->setTargetEntityTypeId('remixicon')
      ->setTargetBundle(NULL)
      ->setStorageRequired(TRUE)
      // ->setDisplayOptions('form', [
      //   'type' => 'boolean_checkbox',
      //   'settings' => [
      //     'display_label' => FALSE,
      //   ],
      //   'weight' => 91,
      // ])
      // ->setDisplayConfigurable('form', TRUE)
      ->setTranslatable(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', t('Enabled'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 100,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }
}
