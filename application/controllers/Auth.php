<?php
$app->post('/auth/register', function() use ($app) {
    $post = $app->post;

    $tbl = new TblUser();
    $res = $tbl->register($post);

    $app->view()->setData('result', $res);
});

$app->post('/auth/login', function() use ($app) {
    $post = $app->post;

    $tbl = new TblUser();
    $res = $tbl->signIn($post);

    $app->view()->setData('result', $res);
});

$app->post('/auth/login/facebook', function() use ($app) {
    $post = $app->post;

    $tbl = new TblUser();
    $res = $tbl->facebookLogin($post);

    $app->view()->setData('result', $res);
});