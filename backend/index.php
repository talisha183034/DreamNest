<?php
require_once(__DIR__ . '/src/dbConnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  header("Access-Control-Allow-Credentials: true");
  http_response_code(200);
  exit;
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
header("Access-Control-Allow-Credentials: true");

$part = explode("/", $_SERVER['REQUEST_URI']);
$route_path = $part[5] ?? null;
$token = null;
if (isset($part[6])) {
  $token = $part[6];
} elseif (isset($part[5])) {
  $token = $part[5];
}

spl_autoload_register(function ($class) {
  require __DIR__ . "/src/$class.php";
});

$token = $part[6] ?? null;
$userAccessToken = $part[7] ?? null;
$email = $part[8] ?? null;


$defaultPageController = new DefaultPageRouting();
$errorController = new ErrorController();

if ($route_path === '' || $route_path === null || is_numeric($route_path)) {
  $defaultPageController->index();
} elseif ($route_path === 'submit_login') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    if ($data === null) {
      $errorController->notFound();
    } else {
      $regUserController->logIn($data);
    }
  } else {
  $errorController->notFound();
}