<?php

namespace Drupal\news_form\Plugin\WSDecoder;

use Drupal\Core\Annotation\Translation;
use Drupal\news_form\Models\NewsApiModel;
use Drupal\wsdata\Annotation\WSDecoder;
use Drupal\wsdata\Plugin\WSDecoderBase;


/**
 * NewsApiDecoder
 *
 * @WSDecoder(
 *   id="NewsApiDecoder",
 *   label=@Translation("News Api Decoder", context = "WSDecoder"),
 * )
 */

class NewsApiDecoder extends WSDecoderBase{

  public function decode($data) : array
  {
    $body = json_decode($data);
    $articles = [];

    if ($body->status == "ok")
    {
      foreach ($body->articles as $index => $article)
      {
        $newsApiData = new NewsApiModel();
        $newsApiData->setSource($article->source ?? null);
        $newsApiData->setTitle($article->title ?? null);
        $newsApiData->setAuthor($article->author ?? null);
        $newsApiData->setDescription($article->description ?? null);
        $newsApiData->setUrl($article->url ?? null);
        $newsApiData->setUrlToImage($article->urlToImage ?? null);
        $newsApiData->setPublishedAt($article->publishedAt ?? null);
        $newsApiData->setContent($article->content ?? null);
        array_push($articles, $newsApiData);

      }
    }
    return $articles;
  }

}