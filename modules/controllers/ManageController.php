<?php

namespace app\modules\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use app\modules\models\Admin;

class ManageController extends Controller {

  public function actionMailchangepass() {
    $time = Yii::$app->request->get("timestamp");
    $adminuser = Yii::$app->request->get("adminuser");
    $token = Yii::$app->request->get("token");
    $model = new Admin;
    $tokenT = $model->createToken($adminuser, $time);
    if ($token != $tokenT) {
      $this->redirect(['public/login']);
      Yii::$app->end();
    }
    if (time() - $time > 300) {
      $this->redirect(['public/login']);
      Yii::$app->end();
    }
    $this->layout = false;
    if (Yii::$app->request->isPost) {
      $post = Yii::$app->request->post();
      if ($model->mailChangepass($post)) {
        Yii::$app->session->setFlash('info', 'Changed pass succeed!');
      } else {
        Yii::$app->session->setFlash('info', 'Changed pass failed!');
      }
    }
    $model->adminuser = $adminuser;
    return $this->render('mailchangepass', ['model' => $model]);
  }

  public function actionManagers() {
    $this->layout = 'layout';
    $model = Admin::find();
    $count = $model->count();
    $pageSize = Yii::$app->params['pageSize']['manage'];
    $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
    $managers = $model->offset($pager->offset)->limit($pager->limit)->all();
    return $this->render('managers', ['managers' => $managers, 'pager' => $pager]);
  }

  public function actionReg() {
    $this->layout = 'layout';
    $model = new Admin;
    if (Yii::$app->request->isPost) {
      $post = Yii::$app->request->post();
      if ($model->reg($post)) {
        Yii::$app->session->setFlash('info', 'Create Admin Succeed!');
      } else {
        Yii::$app->session->setFlash('info', 'Create Admin Failed!');
      }        
    }
    $model->adminpass = '';
    $model->repass = '';
    return $this->render('reg', ['model' => $model]);
  }
  
  public function actionDel() {
    $adminid = (int)Yii::$app->request->get('adminid');
    if (empty($adminid)) {
      $this->redirect(['manage/managers']);
    }
    $model = new Admin;
    if ($model->deleteAll('adminid = :id', [':id' => $adminid])) {
      Yii::$app->session->setFlash('info', 'Delete Admin Succeed!');
      $this->redirect(['manage/managers']);
    }
  } 

  public function actionChangeemail() {
    $model = new Admin;
    if (Yii::$app->request->isPost) {
      $post = Yii::$app->request->post();
      if ($model->changeemail($post)) {
        Yii::$app->session->setFlash('info', 'Admin Changeemail Succeed!');
      } else {
        Yii::$app->session->setFlash('info', 'Admin Changeemail failed!');
      }
    }
    $model->adminpass = '';
    $model->adminemail = '';
    $this->layout = 'layout';
    return $this->render('changeemail', ['model' => $model]);
  }

  public function actionChangepass() {
    $model = new Admin;
    if (Yii::$app->request->isPost) {
      $post = Yii::$app->request->post();
      if ($model->changepass($post)) {
        Yii::$app->session->setFlash('info', 'Admin Changepass succeed!');
      } else {
        Yii::$app->session->setFlash('info', 'Admin Changepass failed!');
      }
    }
    $model->adminpass = '';
    $model->newpass = '';
    $model->renewpass = '';
    $this->layout = 'layout';
    return $this->render('changepass', ['model' => $model]);
  }
}
