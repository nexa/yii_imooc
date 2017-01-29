<?php

namespace app\modules\controllers;

use Yii;
use yii\web\Controller;
use app\modules\models\Admin;

class PublicController extends Controller {

  public function actionLogin() {
    $this->layout = false;
    $model = new Admin;
    if (Yii::$app->request->isPost) {
      $post = Yii::$app->request->post();
      if ($model->login($post)) {
        $this->redirect(['default/index']);
        Yii::$app->end();
      }
    }
    return $this->render("login", ['model' => $model]);
  }

  public function actionLogout() {
    Yii::$app->session->removeAll();
    if (!isset(Yii::$app->session['admin']['isLogin'])) {
      $this->redirect(['public/login']);
      Yii::$app->end();
    }
    return $this->goback();
  }

  public function actionSeekpass() {
    $this->layout = false;
    $model = new Admin;
    if (Yii::$app->request->isPost) {
      $post = Yii::$app->request->post();
      if ($model->seekPass($post)) {
        Yii::$app->session->setFlash('info', 'Seekpass email has been send.');
      }
    }
    return $this->render('seekpass', ['model' => $model]);   
  }
}
