<?php

namespace Drupal\test_form\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the test_form entity.
 *
 * @ContentEntityType(
 *   id = "test_form",
 *   label = @Translation("test_form"),
 *   base_table = "test_form",
 *   entity_keys = {
 *     "id" = "id",
 *     "name" = "name",
 *     "surname" = "surname",
 *     "email" = "email",
 *     "birthday" = "birthday",
 *     "subs_confirm" = "subs_confirm",
 *   },
 * )
 */

class TestForm extends ContentEntityBase implements ContentEntityInterface{

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Advertiser entity.'))
      ->setReadOnly(TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
                    ->setLabel(t('Name'));

    $fields['surname'] = BaseFieldDefinition::create('string')
                    ->setLabel(t('Surname'));

    $fields['telephone'] = BaseFieldDefinition::create('string')
                    ->setLabel(t('Telephone'));

    $fields['email'] = BaseFieldDefinition::create('email')
                    ->setLabel(t('Email'));

    $fields['birthday'] = BaseFieldDefinition::create('datetime')
                    ->setLabel(t('Birthday'));

    $fields['subs_confirm'] = BaseFieldDefinition::create('boolean')
                    ->setLabel(t('Subs Confirm'));


    return $fields;
  }
}