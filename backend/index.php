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

$pageController = new PageController($token);
$regUserController = new RegUserController($token, $conn);
$userController = new UserController($token, $userAccessToken, $email, $conn);
$defaultPageController = new DefaultPageRouting();
$errorController = new ErrorController();

if ($route_path === '' || $route_path === null || is_numeric($route_path)) {
  $defaultPageController->index();
}elseif ($route_path === 'page-info') {
  $query = $part[7] ?? null;
  $pageController->pageInfo($query);
} elseif ($route_path === 'submit_login') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    if ($data === null) {
      $errorController->notFound();
    } else {
      $regUserController->logIn($data);
    }
  }elseif ($route_path === 'submit_signup') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    if ($data === null) {
      $errorController->notFound();
    } else {
      $regUserController->saveUser($data);
    }
  }elseif($route_path === 'house_list_user'){
    $accessToken = $part[7] ?? null;
    $email = $part[8] ?? null;
    $builderController->fetchHouseList($accessToken, $email);
  }elseif($route_path === 'user_balance'){
    $accessToken = $part[7] ?? null;
    $email = $part[8] ?? null;
    $userController->fetchBalance($accessToken, $email);
  } elseif ($route_path === 'user_house_list_dashboard') {
    $accessToken = $part[7] ?? null;
    $email = $part[8] ?? null;
    $userController->fetchListHouse($accessToken, $email);
  } elseif ($route_path === 'user_request_list_dashboard') {
    $accessToken = $part[7] ?? null;
    $email = $part[8] ?? null;
    $userController->fetchListRequest($accessToken, $email);
  } elseif ($route_path === 'user_house_select_dashboard') {
    $accessToken = $part[7] ?? null;
    $email = $part[8] ?? null;
    $userController->fetchHouseSelect($accessToken, $email);
  } else {
  $errorController->notFound();
}