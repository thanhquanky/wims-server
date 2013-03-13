<?php

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'wims/conf/database.php';
require 'wims/interface/persistable.interface.php';
require 'wims/models/model.php';
require 'wims/models/User.php';

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();
$app->config('db_connection', $db_connection);

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, and `Slim::delete`
 * is an anonymous function.
 */


// GET route

$app->get('/user/check/:username', function($username) use($app, $db_connection) {

	$params = Array('username' => $username);
	$user = new User($params);
	$user->cursor($db_connection);
	$res = $user->exist();
	$response = $app->response();
	if ($res)
		$response->status(200);
	else
		$response->status(400);
	$response['Content-Type'] = 'application/json';
	$response->body(json_encode($res));
});

$app->get('/user/authenticate/:username/:password', function($username, $password) use($app, $db_connection) {
	
	$params = Array('username' => $username, 'password' => $password);
	$user = new User($params);
	$user->cursor($db_connection);
	$res = $user->authenticate();
 	$response = $app->response();
 	$data = [];
 	if ($res != false) {
 		$response->status(200);
 		$data['authenticate'] = true;
 		$data['token'] = $res;
 	}
 	else {
 		$response->status(400);
 		$data['authenticate'] = false;
 		$data['token'] = false;
 	}
 	$response['Content-Type'] = 'application/json';
    $response->body(json_encode($data));
});

$app->get('/user/register/:username/:password', function($username, $password) use($app, $db_connection) {

	$params = Array('username' => $username, 'password' => $password);
	$user = new User($params);
	$user->cursor($db_connection);
	$res = $user->register();
	$response = $app->response();
	$data = [];
	if ($res != false) {
		$response->status(200);
		$data['authenticate'] = true;
		$data['token'] = $res;
	}
	else {
		$response->status(400);
		$data['authenticate'] = false;
		$data['token'] = false;
	}
	$response['Content-Type'] = 'application/json';
	$response->body(json_encode($data));
});
	

// POST route
$app->post('/login', function () use ($app, $db_connection) {
	$params = array(
			'username' => $app->request()->post('username'), 
			'password' => $app->request()->post('password')
	);
	$user = new User($params);
	$user->cursor($db_connection);
	$userInfo = $user->authenticate();
	$response = $app->response();
	$data = [];
	if ($userInfo != false) {
		$response->status(200);
		$data['authenticate'] = true;
		$data['user'] = $userInfo;
		$data['message'] = "Success";
	}
	else {
		$response->status(200);
		$data['authenticate'] = false;
		$data['user'] = false;
		$data['message'] = "Invalid credentials. Please try again";
	}
	$response['Content-Type'] = 'application/json';
	$response->body(json_encode($data));
});

$app->post('/register', function () use ($app, $db_connection) {
	$params = array(
			'username' => $app->request()->post('username'),
			'password' => $app->request()->post('password'),
			'email' => $app->request()->post('email'),
	);
	$user = new User($params);
	$user->cursor($db_connection);
	$res = $user->register();
	$response = $app->response();
	$data = [];
	$response->status(200);
	if ($res != false) {
		$data['authenticate'] = true;
		$data['user'] = $res;
		$data['message'] = "Your account has been created";
	}
	else {
		$data['authenticate'] = false;
		$data['token'] = false;
		$data['message'] = "Something wrong has happened. Please try again.";
	}
	$response['Content-Type'] = 'application/json';
	$response->body(json_encode($data));
});

// PUT route
$app->put('/put', function () {
    echo 'This is a PUT route';
});

// DELETE route
$app->delete('/delete', function () {
    echo 'This is a DELETE route';
});


/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
