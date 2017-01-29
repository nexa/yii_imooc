<?php

namespace app\controllers;

use yii\web\Controller;

class MemberController extends Controller {

  public function actionIndex() {
    $this->layout = "layout_navi_bar";
    return $this->render("index");
  }

  public function actionAuth() {
    $this->layout = "layout_navi_bar"; 
   return $this->render("auth");
  }

}
