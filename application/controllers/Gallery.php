<?php

/*
 *
 *  GALLERY
 *
 */
$app->post('/gallery/add', function() use ($app) {
    $post = $app->post;

    $tbl = new TblGallery();
    $res = $tbl->add($post);

    $app->view()->setData('result', $res);
});

$app->post('/gallery/update', function() use ($app) {
    $post = $app->post;

    $tbl = new TblGallery();
    $res = $tbl->upgrade($post);

    $app->view()->setData('result', $res);
});

$app->map('/gallery/get/:id', function($id) use ($app) {
    $tbl = new TblGallery();
    $res = $tbl->get($id);

    $app->view()->setData('result', $res);
})->via("GET", "POST");

/*
 *
 *  PHOTO
 *
 */
$app->post('/gallery/photo/add', function() use ($app) {
    $post = $app->post;

    $tbl = new TblPhotoGallery();
    $res = $tbl->add($post);

    $app->view()->setData('result', $res);
});

$app->post('/gallery/photo/delete', function() use ($app) {
    $post = $app->post;

    $tbl = new TblPhotoGallery();

    if ($post['id'])
        $res = $tbl->delete(array('id' => $post['id']));

    $app->view()->setData('result', $res);
});

/*
 *
 *  VIDEO
 *
 */
$app->post('/gallery/video/add', function() use ($app) {
    $post = $app->post;

    $tbl = new TblVideoGallery();
    $res = $tbl->add($post);

    $app->view()->setData('result', $res);
});

$app->post('/gallery/video/delete', function() use ($app) {
    $post = $app->post;

    $tbl = new TblVideoGallery();

    if ($post['id'])
        $res = $tbl->delete(array('id' => $post['id']));

    $app->view()->setData('result', $res);
});