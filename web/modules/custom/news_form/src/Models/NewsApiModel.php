<?php

namespace Drupal\news_form\Models;


class NewsApiModel {

  public $author;
  public $name;
  public $description;
  public $publishedAt;
  public $content;
  public $url;
  public $urlToImage;
  public $title;
  public $source;

  public function getTitle() {
    return $this->title;
  }

  /**
   * @return mixed $title
   */
  public function setTitle($title) :void {
    $this->title = $title;
  }

  public function getAuthor() {
    return $this->author;
  }

  /**
   * @return mixed $author
   */
  public function setAuthor($author) : void {
    $this->author = $author;
  }

  public function getContent() {
    return $this->content;
  }

  /**
   * @return mixed $content
   */

  public function setContent($content) : void {
    $this->content = $content;
  }

  public function getDescription() {
    return $this->description;
  }

  /**
   * @return mixed $description
   */
  public function setDescription($description) : void {
    $this->description = $description;
  }

  public function getPublishedAt() {
    return $this->publishedAt;
  }

  /**
   * @return mixed $publishedAt
   */
  public function setPublishedAt($publishedAt) : void {
    $this->publishedAt = $publishedAt;
  }

  public function getUrl() {
    return $this->url;
  }

  /**
   * @return mixed $url
   */
  public function setUrl($url) : void {
    $this->url = $url;
  }

  public function getUrlToImage() {
    return $this->urlToImage;
  }

  /**
   * @returns mixed $urlToImage
   */
  public function setUrlToImage($urlToImage) : void  {
    $this->urlToImage = $urlToImage;
  }

  public function getSource() {
    return $this->source;
  }

  /**
   * @returns mixed $source
   */
  public function setSource($source) : void{
    $this->source = $source;
  }

  public function getName() {
    return $this->source->name;
  }

  /**
   * @returns mixed $name
   */
  public function setName($name) : void{
    $this->source->name = $name;
  }

  public function AllNews() : array {
    $array = [];
    $allNews = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'news_example']);

    foreach ($allNews as $index => $newsEntity)
    {
      $news = new NewsApiModel();
      $news->title        = $newsEntity->get('field_title')[0]->value ?? null;
      $news->author       = $newsEntity->get('field_author')[0]->value ?? null;
      $news->name         = $newsEntity->get('field_name')[0]->value ?? null;
      $news->description  = $newsEntity->get('field_description')[0]->value ?? null;
      $news->content      = $newsEntity->get('field_content')[0]->value ?? null;
      $news->publishedAt  = $newsEntity->get('field_published_at')[0]->value ?? null;
      $news->url          = $newsEntity->get('field_url')[0]->value ?? null;
      $news->urlToImage   = $newsEntity->get('field_url_to_image')[0]->value ?? null;
      array_push($array,$news);
    }

    return $array;
  }
}