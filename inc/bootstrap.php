<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions_tasks.php';
require_once __DIR__ . '/functions_user.php';
require_once __DIR__ . '/functions_auth.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
/*
 * Exception Handler
 */
function exception_handler($e) {
  die($e->getMessage());
}
set_exception_handler("exception_handler");

try {
    $db = new PDO("sqlite:".__DIR__."/todo.db");
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
/*
 * Set access to components from \Symfony\Component\HttpFoundation\
 * 1. Session
 * 2. Request
 * 3. Redirect
 */

// 1. session \Symfony\Component\HttpFoundation\Session
$session = new \Symfony\Component\HttpFoundation\Session\Session();
$session->start();

// 2. request \Symfony\Component\HttpFoundation\Request
function request() {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}

// 3. redirect \Symfony\Component\HttpFoundation\Response
function redirect($path, $extra = []) {
    $response = \Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND, ['Location' => $path]);
    if (key_exists('cookies', $extra)) {
      foreach ($extra['cookies'] as $cookie) {
        $response->headers->setCookie($cookie);
      }
    }
    $response->send();
    exit;
  }