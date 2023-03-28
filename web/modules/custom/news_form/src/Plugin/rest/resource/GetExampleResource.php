<?php

namespace Drupal\news_form\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Class GetExampleResource
 * @package Drupal\news_form\Plugin\rest\resource
 *
 * @RestResource(
 *   id = "news_form_resource",
 *   label = @Translation("Custom Web Service news form"),
 *   uri_paths = {
 *    "canonical" = "/news_form/actions"
 *   }
 * )
 */

class GetExampleResource extends ResourceBase {

  public function get()
  {
    $response = [];
    $entityArr = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => "news_example"]);

    foreach ($entityArr as $index => $entity) {
      $response[] = [
        'title'        => $entity->field_title->value,
        'author'       => $entity->field_author->value,
        'description'  => $entity->field_description->value,
        'content'      => $entity->field_content->value,
        'url'          => $entity->field_url->value,
        'urlToImage'   => $entity->field_url_to_image->value,
        'published_At' => $entity->field_published_at->value,
      ];
    }


    return new ResourceResponse($response);
  }

}