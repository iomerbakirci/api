<?php

/*
 *
 *  POST
 *
 */
$app->post('/post/add', function() use ($app) {
    $post = $app->post;
    $post["post_author"] = $app->user["id"];

    $tbl = new TblPost();
    $res = $tbl->add($post);

    $app->view()->setData('result', $res);
});

$app->post('/post/update', function() use ($app) {
    $post = $app->post;
    $post["post_author"] = $app->user["id"];

    $tbl = new TblPost();
    $res = $tbl->upgrade($post);

    $app->view()->setData('result', $res);
});

$app->map('/post/get/:id', function($id) use ($app) {
    $tbl = new TblPost();
    $res = $tbl->get($id);

    $app->view()->setData('result', $res);
})->via("GET", "POST");

$app->map('/post/search', function() use ($app) {
    $post = $app->post;

    $tbl = new TblPost();
    $res = $tbl->search($post);

    $app->view()->setData('result', $res);
})->via("GET", "POST");

/*
 *
 *  CATEGORY
 *
 */
$app->post('/post/category/add', function() use ($app) {
    $post = $app->post;

    $tbl = new TblCategory();
    $res = $tbl->add($post);

    $app->view()->setData('result', $res);
});

$app->post('/post/category/update', function() use ($app) {
    $post = $app->post;

    $tbl = new TblCategory();
    $res = $tbl->upgrade($post);

    $app->view()->setData('result', $res);
});

$app->map('/post/get/category/:id', function($id) use ($app) {
    $tbl = new TblCategory();
    $res = $tbl->get($id);

    $app->view()->setData('result', $res);
})->via("GET", "POST");

$app->map('/post/search/category', function() use ($app) {
    $post = $app->post;

    $tbl = new TblCategory();
    $res = $tbl->search($post);

    $app->view()->setData('result', $res);
})->via("GET", "POST");

/*
 *
 *  COMMENT
 *
 */
$app->post('/post/comment/add', function() use ($app) {
    $post = $app->post;
    $post["comment_author"] = $app->user["id"];

    $tbl = new TblComment();
    $res = $tbl->add($post);

    $app->view()->setData('result', $res);
});

$app->post('/post/comment/update', function() use ($app) {
    $post = $app->post;
    $post["comment_author"] = $app->user["id"];

    $tbl = new TblComment();
    $res = $tbl->upgrade($post);

    $app->view()->setData('result', $res);
});

$app->map('/post/get/comment/:id', function($id) use ($app) {
    $tbl = new TblComment();
    $res = $tbl->get($id);

    $app->view()->setData('result', $res);
})->via("GET", "POST");

$app->map('/post/search/comment', function() use ($app) {
    $post = $app->post;

    $tbl = new TblComment();
    $res = $tbl->search($post);

    $app->view()->setData('result', $res);
})->via("GET", "POST");

/*
 *
 *  BANNER
 *
 */
$app->map('/post/banner', function() use ($app) {
    $post = $app->post;

    $tbl = new TblPost();
    $res = $tbl->getLatestPostsForBanner($post);

    $app->view()->setData('result', $res);
})->via("GET", "POST");