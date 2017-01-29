<p>Hello: <?php echo $adminuser; ?></p>

<p>Your seekpassword url: </p>

<?php $url = Yii::$app->urlManager->createAbsoluteUrl(['admin/manage/mailchangepassword', 'timestamp' => $time, 'adminuser' => $adminuser, 'token' => $token]); ?>
<p><a href ="<?php echo $url; ?>"><?php echo $url; ?></p>

<p>DO NOT REPLY</p>
