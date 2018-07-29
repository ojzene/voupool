<?php
namespace App\Config;
use App\Config\RedisDatabase as RD;
use RedBeanPHP\R;

class Auth
{
    public $live_apiKey;
    public $token;

    public function __construct()
    {
        /**** Details   ****/
        $this->output_format = "json"; // json or xml
        // $this->output_app_format = "application/json"; // json or xml
    }

    public function getDbAuthHeader($id)
    {
        $findID = R::findOne("authheaders", 'token=?', [$id]);
        if (count($findID)) { return $findID; } else { return null; }
    }

    public function verifyAuthHeader($dataparams) {
        if (empty($dataparams)) { $result = null; }
        else {
            $gauth = $dataparams[0];
            $token = substr($gauth, 7, strlen($gauth));
            if(empty($token)) {
                $result = null;
            } else {
                $result = $token;
            }
        }
        return $result;
    }

    // to get token: save time into database after first request, then to make a request - call dis function to check the difference between time save in the db and server time, if

    public function getToken(){
        $get_data = (new Auth)->getTokenForAccess($this->live_apiKey);
        $get_json = $get_data['data'];
        $get_array_data = json_decode($get_json, true);
        $this->token = $get_array_data['token'];
        return $this->token;
    }

    public function hasTokenExpire(){
        $token_message = null;
        $get_data = (new Auth)->getTokenForAccess($this->live_apiKey);
        $get_json = $get_data['data'];
        $get_array_data = json_decode($get_json, true);
        $token_date = $get_array_data['token_expiration'];
        $token = $get_array_data['token'];

        $today_date = date("Y-m-d h:i:s");

        $timeFirst  = strtotime($token_date);
        $timeSecond = strtotime($today_date);
        $differenceInSeconds = $timeFirst - $timeSecond;

        if ($differenceInSeconds > 0)  // has not expired
        {
            $token_message = ["status"=>false, "message"=>$token];
        }
        elseif ($differenceInSeconds <= 0) // has expired
        {
            $token_message = ["status"=>true, "message" =>""];
        }
        return $token_message;
    }

    public function checkRedisConnection() {
        $is_connected = (new RD)->single_client();
        $connected = $is_connected ? "yes" : "no";
        return $connected;
    }

    public function getAllRedisKeys() {
        $redis_errors = "";
        $isConnected = $this->checkRedisConnection();
        if($isConnected == "yes") {
            $client = (new RD)->single_server();
            $allkeys = $client->keys('*');
            return $allkeys;
        } elseif($isConnected == "no") {
            $redis_errors = "Oops! Unable to connect Database";
        }
        return $redis_errors;
    }

    public function checkApikKeyExist($apiKey){

        $redis_errors = "";

        $client = (new RD)->single_server();

        $id_exists = $client->exists($apiKey);   // print_r($client->keys('*')); // get all redis keys

        if ($id_exists === 1) {
            $redis_errors = "";
        } elseif ($id_exists === 0) {
            $redis_errors = "Invalid request, apikey does not exist ";
        }
        return $redis_errors;
    }

    public function getTokenForAccess($apiKey) {

        $result_error = $this->checkApikKeyExist($apiKey);
        if($result_error == "" || empty($result_error)) {

            $client = (new RD)->single_server();
            $tracking_key = $apiKey;

            $response = $client->get($tracking_key);

            $result = ['success' => true, 'data' => $response ];

        } else {

            $result = ['success' => false, 'data' => $result_error ];
        }
        return $result;
    }

    public function saveTokenForAccess($apiKey, $token) {
        $redis_errors = "";

        $isConnected = $this->checkRedisConnection();
        if($isConnected == "yes") {

            $token_expiration = date('Y-m-d h:i:s', strtotime('+2 hour')); //the expiration date will be in two hour from the current moment

            $client = (new RD)->single_server();
            $track_token = json_encode([
                'apiKey' => $apiKey,
                'token' => $token,
                'token_expiration' => $token_expiration
            ]);

            $client->set($apiKey, $track_token);
            $client->expire($track_token, 1200);
            $client->ttl($track_token);

            $redis_errors = null;

        } elseif($isConnected == "no") {
            $redis_errors = "Oops! Unable to connect Database";
        }
        return $redis_errors;
    }

}