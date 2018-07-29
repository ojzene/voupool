<?php
/**
 * Created by PhpStorm.
 * User: kanmit
 * Date: 7/29/2018
 * Time: 2:00 PM
 */

use Slim\Http\Environment;
use Slim\Http\Request;

class AppTest extends PHPUnit_Framework_TestCase
{
    protected $app;
    public function setUp()
    {
        $this->app = (new Test())->get();
    }
    public function testSpecialOfferGet() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/offers',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame((string)$response->getBody(), "List of All Special Offer");
    }
    public function testSingleSpecialOfferGet() {
        $offerid = "RBFOThVpPw";
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/offers/'.$offerid,
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $result = json_decode($response->getBody(), true);
        $this->assertSame($result["message"], "Single Special Offer successfully fetched");
    }
    public function testSpecialOfferPost() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/offers',
            'CONTENT_TYPE'   => 'application/x-www-form-urlencoded',
        ]);
        $req = Request::createFromEnvironment($env)->withParsedBody([]);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $result = json_decode($response->getBody(), true);
        $this->assertSame($result["message"], "Special Offer created successfully");
    }


    public function testVoucherCodesGet() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/vouchers',
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame((string)$response->getBody(), "List of All Voucher Codes");
    }
    public function testSingleVoucherCodeGet() {
        $vcode = "PPNH33W8";
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/vouchers/'.$vcode,
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $result = json_decode($response->getBody(), true);
        $this->assertSame($result["message"], "Single Voucher Code successfully fetched");
    }
    public function testVoucherCodePost() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/vouchers',
            'CONTENT_TYPE'   => 'application/x-www-form-urlencoded',
        ]);
        $req = Request::createFromEnvironment($env)->withParsedBody([]);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $result = json_decode($response->getBody(), true);
        $this->assertSame($result["message"], "Voucher Code generated successfully");
    }

    public function testRedeemVoucherCodePost() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/vouchers/redeem',
            'CONTENT_TYPE'   => 'application/x-www-form-urlencoded',
        ]);
        $req = Request::createFromEnvironment($env)->withParsedBody([]);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $result = json_decode($response->getBody(), true);
        $this->assertSame($result["message"], "Voucher Code redeemed successfully");
    }

    public function testValidRecipientVoucherGet() {
        $recipient_email = "ttconfirmed@gmail.com";
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/vouchers/recipient/'.$recipient_email,
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $result = json_decode($response->getBody(), true);
        $this->assertSame($result["message"], "Recipient Valid Voucher Code successfully fetched");
    }

}