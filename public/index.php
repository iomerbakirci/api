<?php

define('ROOT_DIR', dirname(dirname(__FILE__)));
define('API_URL', 'http://api.matmuh.net');

if (ROOT_DIR == '/var/www/tez/api')
    define('TEST', false);
else
    define('TEST', true);

set_include_path('.'
                    . PATH_SEPARATOR . ROOT_DIR . '/library'
                    . PATH_SEPARATOR . ROOT_DIR . '/library/MatMuh'
                    . PATH_SEPARATOR . ROOT_DIR . '/application/controllers'
                    . PATH_SEPARATOR . ROOT_DIR . '/application/models'
                    . PATH_SEPARATOR . get_include_path());

date_default_timezone_set('Europe/Istanbul');

require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

error_reporting(E_ALL || ~E_NOTICE);

spl_autoload_register(function ($class)
{
    include $class . '.php';
});

$app->hook('slim.before.router', function () use ($app) {
    //route olmadan önce burası çalışır. Authentication ve ACL burada yapılabilir

    $app->contentType('text/html;charset=utf-8');
    $uri = strtolower($app->request()->getResourceUri());
    $uri = explode('/', substr($uri, 1));
    $controller = $uri[0];
    $action = $uri[1];

    if ($controller != 'test') {
        $post = $app->request()->params();
        $tblClient = new TblApiClient();
        $res = $tblClient->check($post);

        if ($res !== true) {
            $app->response()->body(json_encode($res));
            $app->stop();
        }
        else {
            $app->client_key = $post['client_key'];
            unset($post['client_key']);
            unset($post['client_secret']);

            if ($post['access_token']) {
                $tblToken = new TblToken();
                $user = $tblToken->getUser($post['access_token']);
                $app->user = $user;
            }
            
            $tokenRequire = array('user', 'post', 'gallery');

            if (in_array($controller, $tokenRequire)) {
                if ($action != 'get' && $action != 'search') {
                    if (!$post['access_token'])
                        $error = "Token eksik!";
                    else {
                        if (!$user)
                            $error = "Token hatalı!";
                    }
                }
            }

            if ($error) {
                $app->response()->body(json_encode(array('status' => 'error', 'message' => $error)));
                $app->stop();
            }

            $app->post = $post;
        }
    }
    else if (!TEST) {
        echo 'Test kapalı';
        $app->stop();
    }
});

$app->hook('slim.after.router', function () use ($app) {
    if ($app->view()->getData('result')) {
        $data = $app->view()->getData('result');

        if (!$data['status'])
            $data['status'] = 'error';

        $res = json_encode($data);
        $app->response()->body($res);
    }
    else if (!$app->response()->body()) {
        $res = new stdClass();
        $res->status = 'error';
        $res->message = 'Empty response!';
        $app->response()->body(json_encode($res));
    }
});

$app->notFound(function() {
    $res = new stdClass();
    $res->status = 'error';
    $res->message = 'Url bulunamadı.';
    echo json_encode($res);
});

$app->error(function() {
    $res = new stdClass();
    $res->status = 'error';
    $res->message = 'Bir Sorun Oluştu!';
    echo json_encode($res);
});

$app->config('debug', true);

$modul = array('Auth', 'Test', 'User', 'Post', 'Gallery', 'Parameter');
foreach ($modul as $m) {
    require_once "$m.php";
}

$app->run();