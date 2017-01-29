<?php

namespace app\modules\models;

use Yii;
use yii\db\ActiveRecord;

class Admin extends ActiveRecord {

  public $rememberMe = true;

  public $repass;

  public $newpass;

  public $renewpass;

  public static function tableName() {
    return '{{%admin}}';
  }

  public function attributeLabels() {
    return [
      'adminuser' => '管理员名称',
      'adminemail' => '管理员邮箱',
      'adminpass' => '管理员密码',
      'repass' => '确认密码',
      'newpass' => '新密码',
      'renewpass' => '确认新密码',
    ];
  }

  public function rules() {
    return [
      ['adminuser', 'required', 'message' => 'Admin User is required!', 'on' => ['login', 'seekpass', 'mailchangepass', 'reg', 'changeemail', 'changepass']],
      ['adminpass', 'required', 'message' => 'Admin pass is required!', 'on' => ['login', 'mailchangepass', 'reg', 'changeemail', 'changepass']],
      ['rememberMe', 'boolean', 'on' => 'login'],
      ['adminpass', 'validatepass', 'on' => ['login', 'changeemail', 'changepass']],
      ['adminemail', 'required', 'message' => 'Admin Email is required!', 'on' => ['seekpass', 'reg', 'changeemail']],
    //  ['adminemail', 'unique', 'message' => 'Admin Email is used!', 'on' => 'reg'],
      ['adminemail', 'email', 'on' => ['seekpass', 'reg', 'changeemail']],
      ['adminemail', 'validateEmail', 'on' => 'seekpass'],
      ['repass', 'required', 'message' => 'Repass is required!', 'on' => ['mailchangepass', 'reg']],
      ['repass', 'compare', 'compareAttribute' => 'adminpass', 'message'=> 'Adminpass does not match repass', 'on' => ['mailchangepass', 'reg']],
      ['newpass', 'required', 'message' => 'Newpass is required!', 'on' => 'changepass'],
      ['renewpass', 'compare', 'compareAttribute' => 'newpass', 'message'=> 'Adminpass does not match repass', 'on' => 'changepass'],
    ];
  }

  public function validatepass() {
    if (!$this->hasErrors()) {
      $data = self::find()->where('adminuser = :user and adminpass = :pass',
        [':user' => $this->adminuser, ':pass' => md5($this->adminpass)])->one();
      if (is_null($data)) {
        $this->addError('adminpass', 'Bad Admin User or Admin pass');
      }
    }
  }

  public function validateEmail() {
    if (!$this->hasErrors()) {
      $data = self::find()->where('adminuser = :user and adminemail = :email',
        [':user' => $this->adminuser, ':email' => $this->adminemail])->one();
      if (is_null($data)) {
        $this->addError('adminemail', 'Bad Admin User or Admin Email');
      }
    }
  }

  public function login($data) {
    $this->scenario = 'login';
    if ($this->load($data) && $this->validate()) {
      $lifetime = $this->rememberMe ? 3600 * 23 : 0;
      $session = Yii::$app->session;      
      session_set_cookie_params($lifetime);
      $session['admin'] = [
        'adminuser' => $this->adminuser,
        'isLogin' => 1,
      ];
      $this->updateAll(['logintime' => time(), 'loginip' => ip2long(Yii::$app->request->userIP)], 'adminuser = :user', [':user' => $this->adminuser]);
      return (bool)$session['admin']['isLogin'];
    }
    return false;
  }

  public function seekPass($data) {
    $this->scenario = "seekpass";
    if ($this->load($data) && $this->validate()) {
      $time = time();
      $token = $this->createToken($data['Admin']['adminuser'], $time);
      $mailer = Yii::$app->mailer->compose('seekpass', ['adminuser' => $data['Admin']['adminuser'], 'time' => $time, 'token' => $token]);
      $mailer->setFrom("mcyzlizhun@163.com");
      $mailer->setTo($data['Admin']['adminemail']);
      $mailer->setSubject("Seekpass");
      if ($mailer->send())
        return true;
    }
    return false;
  }

  public function createToken($adminuser, $time) {
    return md5(md5($adminuser).base64_encode(Yii::$app->request->userIP).md5($time));
  }

  public function mailChangepass($data) {
    $this->scenario = "mailchangepass";
    if ($this->load($data) && $this->validate()) {
      return (bool)self::updateAll(['adminpass' => md5($this->adminpass)], 'adminuser = :user', [':user' => $this->adminuser]);
    }
    return false;
  }

  public function reg($data) {
    $this->scenario = "reg";
    if ($this->load($data) && $this->validate()) {
      $this->adminpass = md5($this->adminpass);
      if ($this->save(false)) {
        return true;
      }
    }
    return false;
  }

  public function changeemail($date) {
    $this->scenario = "changeemail";
    if ($this->load($date) && $this->validate()) {
      return (bool)self::updateAll(['adminemail' => $this->adminemail], 'adminuser = :user', [':user' => $this->adminuser]);
    }
    return false;
  }

  public function changepass($data) {
    $this->scenario = "changepass";
    if ($this->load($data) && $this->validate()) {
      return (bool)self::updateAll(['adminpass' => md5($this->newpass)], 'adminuser = :user', [':user' => $this->adminuser]);
    }
    return false;
  }
} 
