<?php
namespace App\Controllers\VoucherController;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\GeneralModel;
use App\Config\Auth;

class VoucherCodeController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
        $this->output_format = (new Auth)->output_format;
        $this->method_names = (new GeneralModel)->get_model_methods("VoucherModel\VoucherCodeModel");
    }

    public function createVoucherCode(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 1, 'POST');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function listVoucherCode(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 2, 'GET');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function fetchVoucherCode(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 3, 'GET', '','code');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function redeemVoucherCode(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 4, 'POST');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function listRecipientVoucherCodes(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 5, 'GET', '', 'email');
        } catch (\Exception $e) {
            return $e;
        }
    }

}