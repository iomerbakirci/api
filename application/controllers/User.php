<?php
$app->post('/user/update', function() use ($app) {
    $post = $app->post;

    $tbl = new TblUser();
    if($tbl->updateUser($app->user['id'], $post))
        $res = array('status' => 'success');

    $app->view()->setData('result', $res);
});

$app->post('/user/passwordreminder', function() use ($app) {
    $post = $app->post;

    $newPassword = \MatMuh\Helper::generatePassword(6);
    $post["password"] = md5($newPassword);
    $post["new_password"] = $newPassword;

    $tbl = new TblUser();

    if ($tbl->updateUser($app->user['id'], $post))
        $res = array('status' => 'success');

    $app->view()->setData('result', $res);
});