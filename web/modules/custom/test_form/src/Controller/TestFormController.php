<?php

namespace Drupal\test_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;


class TestFormController extends ControllerBase{

  public function list() {
    $limitOffset = 5;
    $conn  = Database::getConnection()->select('test_form','a');
    $results = $conn->fields('a',array('id','name','surname','email','subs_confirm'))
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit($limitOffset)
      ->execute()->fetchAll();

    $header = [
      $this->t('Name'),
      $this->t('Surname'),
      $this->t('Email'),
      $this->t('Status'),
      $this->t('Process')
    ];

    $data = [];
    foreach ($results as $res)
      $data[] = [
        'name' => $res->name,
        'surname' => $res->surname,
        'email' => $res->email,
        'status' => $res->subs_confirm == 1 ? 'Aktif' : 'Pasif',
        'process' => $this->t("<a href='/test_form/edit/$res->id'>Edit</a>"),
      ];

    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $data,
    ];

    $build['pager'] = [
      '#type' => 'pager',
    ];

    return array(
      $build
    );

  }

  public function edit() {

  }

  public function delete() {

  }

}