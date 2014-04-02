<?php
$app->get('/test/auth/register', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'first_name' => 'Ömer',
        'last_name' => 'Bakırcı',
        'email' => 'iomerbakirci@gmail.com',
        'password' => md5('123qwe'),
        'gsm' => '5385780092',
        'date_of_birth' => '1993-02-11',
        'gender' => 'erkek',
        'city' => '34',
        'role_id' => '1'
    );

    $res = \MatMuh\Api::request('/auth/register', $post, true);
    $app->response()->body($res);
});

$app->get('/test/auth/login', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'email' => 'iomerbakirci@gmail.com',
        'password' => md5('123qwe')
    );

    $res = \MatMuh\Api::request('/auth/login', $post, true);
    $app->response()->body($res);
});

$app->get('/test/auth/login/facebook', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'facebook_token' => 'xxxxxxxxxx'
    );

    $res = \MatMuh\Api::request('/auth/login/facebook', $post, true);
    $app->response()->body($res);
});

$app->get('/test/user/update', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'last_name' => 'Bakırcı'
    );

    $res = \MatMuh\Api::request('/user/update', $post, true);
    $app->response()->body($res);
});

$app->get('/test/user/passwordreminder', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419'
    );

    $res = \MatMuh\Api::request('/user/passwordreminder', $post, true);
    $app->response()->body($res);
});

$app->get('/test/post/add', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'post_type' => 'post',
        'post_title' => 'deneme başlık8',
        'post_content' => 'deneme içerik',
        'post_excerpt' => 'deneme özet',
        'tags' => array('tag1', 'tag2', 'tag3'),
        'categories' => array('1', '3', '5')
    );

    $res = \MatMuh\Api::request('/post/add', $post, true);
    $app->response()->body($res);
});

$app->get('/test/post/update', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'id' => '35',
        'post_type' => 'page',
        'post_url' => 'xxx',
        'post_title' => 'deneme başlık8',
        'post_content' => 'deneme içerik',
        'post_excerpt' => 'deneme özet',
        'tags' => array('tag1', 'tag2', 'tag3'),
        'categories' => array('1')
    );

    $res = \MatMuh\Api::request('/post/update', $post, true);
    $app->response()->body($res);
});

$app->get('/test/post/get/:id', function($id) use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f'
    );

    $res = \MatMuh\Api::request('/post/get/' . $id, $post, true);
    $app->response()->body($res);
});

$app->get('/test/post/search', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'category' => 1
    );

    $res = \MatMuh\Api::request('/post/search', $post, true);
    $app->response()->body($res);
});

$app->get('/test/category/add', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'category_name' => 'siyaset'
    );

    $res = \MatMuh\Api::request('/post/category/add', $post, true);
    $app->response()->body($res);
});

$app->get('/test/category/update', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'id' => '1',
        'category_name' => 'spor'
    );

    $res = \MatMuh\Api::request('/post/category/update', $post, true);
    $app->response()->body($res);
});

$app->get('/test/category/get/:id', function($id) use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f'
    );

    $res = \MatMuh\Api::request('/post/get/category/' . $id, $post, true);
    $app->response()->body($res);
});

$app->get('/test/category/search', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'category_name' => 'Sp'
    );

    $res = \MatMuh\Api::request('/post/search/category', $post, true);
    $app->response()->body($res);
});

$app->get('/test/comment/add', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'post_id' => 1231,
        'comment_content' => 'slm cnm nbr :)'
    );

    $res = \MatMuh\Api::request('/post/comment/add', $post, true);
    $app->response()->body($res);
});

$app->get('/test/comment/update', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'id' => 1,
        'comment_status' => 'confirmed'  //  { awaiting, confirmed, canceled }
    );

    $res = \MatMuh\Api::request('/post/comment/update', $post, true);
    $app->response()->body($res);
});

$app->get('/test/comment/get/:id', function($id) use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f'
    );

    $res = \MatMuh\Api::request('/post/get/comment/' . $id, $post, true);
    $app->response()->body($res);
});

$app->get('/test/comment/search', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'comment_status' => 'confirmed'
    );

    $res = \MatMuh\Api::request('/post/search/comment', $post, true);
    $app->response()->body($res);
});

$app->get('/test/gallery/add', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'gallery_type' => 'video'   //  { photo, video }
    );

    $res = \MatMuh\Api::request('/gallery/add', $post, true);
    $app->response()->body($res);
});

$app->get('/test/gallery/update', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'id' => 1,
        'gallery_status' => 0
    );

    $res = \MatMuh\Api::request('/gallery/update', $post, true);
    $app->response()->body($res);
});

$app->get('/test/gallery/get/:id', function($id) use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f'
    );

    $res = \MatMuh\Api::request('/gallery/get/' . $id, $post, true);
    $app->response()->body($res);
});

$app->get('/test/photo/add', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'gallery_id' => 1
    );

    $res = \MatMuh\Api::request('/gallery/photo/add', $post, true);
    $app->response()->body($res);
});

$app->get('/test/photo/delete', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'id' => 3
    );

    $res = \MatMuh\Api::request('/gallery/photo/delete', $post, true);
    $app->response()->body($res);
});

$app->get('/test/video/add', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'gallery_id' => 3
    );

    $res = \MatMuh\Api::request('/gallery/video/add', $post, true);
    $app->response()->body($res);
});

$app->get('/test/video/delete', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f',
        'access_token' => '07ba30edc403657adb753307b0f1d419',
        'id' => 1
    );

    $res = \MatMuh\Api::request('/gallery/video/delete', $post, true);
    $app->response()->body($res);
});

$app->get('/test/city', function() use ($app) {
    $post = array(
        'client_key' => '1064f3a3e98620580b5b',
        'client_secret' => 'd044c50e14504b83613f'
    );

    $res = \MatMuh\Api::request('/parameter/city', $post, true);
    $app->response()->body($res);
});