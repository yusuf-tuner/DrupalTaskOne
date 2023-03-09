<?php

namespace Drupal\eventparticipation\Controller;

use Drupal\Core\Controller\ControllerBase;

class EventParticipationController extends ControllerBase{

  public function list() {


    return [
      '#markup' => $this->t('Event Participation Forms List'),
    ];

  }

}