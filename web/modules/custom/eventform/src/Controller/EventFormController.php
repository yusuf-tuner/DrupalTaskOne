<?php

namespace Drupal\eventform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class EventFormController extends ControllerBase{


  public function index()
  {
    $query = \Drupal::database()->select('eventforms','a');
    $query->fields('a',['event_form_name']);
    $lists = $query->execute()->fetchAll();

    print_r($lists);
    exit();
    $content = [];

    $content['message'] = [

      '#markup' => t('Bellow is a list of all Event RSVPs including username,

      email adress and the name of the event they will be attending.'),

    ];
    $headers = [ // Haaders de la tabla DRUPAL

      t('Mail'),

      t('Created'),

    ];

    $content['table'] = [

      '#type' => 'table',

      '#header' => $headers,

      '#rows' => $lists,

      '#empty' => t('No entries available'),

    ];


    $content['#cache']['max-age'] = 0; // No lo mete en cache para que nos de los datos actualizados siempre

    return $content;
  }
}
