<?php

namespace app\controllers;

use yii\web\Controller;

class ProductController extends Controller {

  public function actionIndex() {
    $this->layout = "layout_navi_bar";
    return $this->render("index");
  }

  public function actionDetail() {
    $this->layout = "layout_navi_bar"; 
    return $this->render("detail");
  }

}
