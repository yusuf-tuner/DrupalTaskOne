<?php

namespace Drupal\news_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\news_form\Models\NewsApiModel;

class NewsController extends ControllerBase
{

  public function getNewsData() : array
  {
    $wsData = \Drupal::service('wsdata');
    $result = $wsData->call('get_news_api_data');

    return $result;
  }

  /**
   * @param array $newsModel
   */
  public function importNews($news)
  {
    try
    {
      $entityNode = \Drupal::entityTypeManager()->getStorage('node');

      foreach ($news as $key => $new)
      {
        $entity = $entityNode->loadByProperties(['type' => 'news_example' ,'title' => $new->getTitle()]);

        if (isset($entity) && !empty($entity))
        {
          $firstEntity = current($entity);
          if (count($entity) > 1)
          {
            unset($entity[$firstEntity->id()]);

            foreach ($entity as $item)
            {
              $item->delete();
            }
          }
        }
        else{
          // first create step start
          $node = $entityNode->create([
            'type' => 'news_example',
            'title' => $new->getTitle(),
            'field_author' => $new->getAuthor(),
            'field_name' => $new->getName(),
            'field_title' => $new->getTitle(),
            'field_description' => substr($new->getDescription(),0,100),
            'field_content' => $new->getContent(),
            'field_published_at' => $new->getPublishedAt(),
            'field_url' => substr($new->getUrl(),0,100),
            'field_url_to_image' => substr($new->getUrlToImage(),0,200)
          ]);

          $node->save();
          // first create step end
        }

      }

      return true;
    }catch (\Exception $e)
    {
      \Drupal::logger('test_log')->alert($e->getMessage());

      return false;
    }

  }

}