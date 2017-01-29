<?php

namespace app\controllers;

use yii\web\Controller;

class OrderController extends Controller {

  public function actionIndex() {
    $this->layout = "layout_navi_bar";
    return $this->render("index");
  }

  public function actionCheck() {
    $this->layout = "layout";
    return $this->render("check");
  }
}
