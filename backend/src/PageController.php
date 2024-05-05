<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once 'dbConnect.php';

class PageController{

  private $status;
  private $msg;
  private $accessToken;
  private $conn;

  public function __construct($token){
    global $conn;
    $this->conn = $conn;
    $sytemConfig = require(__DIR__ . '/config/systemConfig.php');
    $this->accessToken = $sytemConfig['APP_TOKEN'];
    if (!$this->validateToken($token)) {
      $this->unAutReq();
      exit();
    }
  }

  private function validateToken($token){
    return $token === $this->accessToken;
  }

  private function unAutReq(){
    $this->status = '401';
    $this->msg = 'Unauthorize access';
    $response = [
      'status' => $this->status,
      'msg' => $this->msg
    ];
    echo json_encode($response);
    http_response_code(401);
  }

  /* =============== Token Validateion end =============== */

  public function pageInfo($query){
    if($query === null){
      $this->status= "4000";
      $this->msg= "query required";
      $response = [
        'status' => $this->status,
        'msg' => $this->msg
      ];
      echo json_encode($response);
      http_response_code(200);
    }else{
      $sytemConfig = require(__DIR__ . '/config/systemConfig.php');
      $baseUrl = $sytemConfig['Base_Url'];
      if($query === 'nav'){
        $sytemConfig = require(__DIR__ . '/config/systemConfig.php');
        $appName = $sytemConfig['APP_Name'];
        $html = $baseUrl.'/frontend/component/navbar.html';
        $response = [
          'status' => '0000',
          'msg' => file_get_contents($html),
          'adi_data' => [
            'appName' => $appName
          ]
        ];
        echo json_encode($response);
        http_response_code(200);
      }elseif($query === 'welcome'){
        $html = $baseUrl . '/frontend/page/welcome.html';
        $response = [
          'status' => '0000',
          'msg' => file_get_contents($html)
        ];
        echo json_encode($response);
        http_response_code(200);
      } elseif ($query === 'property_list') {
        $html = $baseUrl . '/frontend/page/property-list.html';
        $response = [
          'status' => '0000',
          'msg' => file_get_contents($html)
        ];
        echo json_encode($response);
        http_response_code(200);
      }elseif($query === 'check_login'){
        if ($query === 'check_login') {
          if (isset($_SESSION['access_token'])) {
            $access = true;
            $html = $baseUrl.'/frontend/dashboard/dashboard.html';
            $response = [
              'status' => '0000',
              'msg' => [
                'access' => $access,
                'url' => $html
              ]
            ];
            echo json_encode($response);
            http_response_code(200);
          } else {
            $access = false;
            $html = $baseUrl . '/frontend/page/login.html';
            $response = [
              'status' => '0000',
              'msg' => [
                'access' => $access,
                'url' => file_get_contents($html)
              ]
            ];
            echo json_encode($response);
          }
        }
      }elseif($query === 'sign_up'){
        $html = $baseUrl . '/frontend/page/sign-up.html';
        $response = [
          'status' => '0000',
          'msg' => file_get_contents($html)
        ];
        echo json_encode($response);
        http_response_code(200);
      } elseif ($query === 'submit_signup') {
        $html = $baseUrl . '/frontend/page/sign-up.html';
        $response = [
          'status' => '0000',
          'msg' => file_get_contents($html)
        ];
        echo json_encode($response);
        http_response_code(200);
      } elseif ($query === 'primay_data') {
        $sytemConfig = require(__DIR__ . '/config/systemConfig.php');
        $appName = $sytemConfig['APP_Name'];
        $response = [
          'status' => '0000',
          'adi_data' => [
            'appName' => $appName
          ]
        ];
        echo json_encode($response);
        http_response_code(200);
      }elseif($query === 'product_details'){
        $html = $baseUrl . '/frontend/property-single.html';
        $response = [
          'status' => '0000',
          'msg' => $html
        ];
        echo json_encode($response);
        http_response_code(200);
      }else{
        $response = [
          'status' => '0000',
          'msg' => 'your query is : ' . $query
        ];
        echo json_encode($response);
        http_response_code(200);
      }
    }
  }
}