<?php

namespace Drupal\global_remixicon\Controller;

use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class RemixIconController
 * @package Drupal\mymodule\Controller
 */
class RemixIconController extends ControllerBase {

  public function redirectToView()
  {
    return $this->redirect('view.remixicons.page_1');
  }

}
