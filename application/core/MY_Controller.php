<?php

class MY_Controller extends CI_Controller {
    public function __construct(){
        parent::__construct();
        
        $http_origin = !empty( $_SERVER['HTTP_ORIGIN'] ) ? $_SERVER['HTTP_ORIGIN'] : "";

        if( preg_match("/https?:\/\/(marketing\.uz|localhost|localhost\.local|([a-zA-Z0-9]*)\.maketing\.uz)/", $http_origin) ){
              header('Access-Control-Allow-Origin: '.$http_origin);
              header('Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
                header('Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept');
                header('Access-Control-Max-Age: 1728000');
                header('Content-Length: 0');
                header('Content-Type: text/plain');
                die();
        }

        $token = $this->getBearerToken();
        
        if ( $token != "L1bkrnD1ObOQ8JAUmHCBq7Iy7otZcyAagBLHVKvvYaIpmMuxmARQ97jUVG16" ) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->_display(json_encode([
                    'status' => false,
                    'message' => 'Unauthorized'
                ])
            );
            exit();
        }
    }

    private function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    private function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}