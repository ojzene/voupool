<?php
namespace App\Controllers\AccountController;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\GeneralModel;
use App\Config\Auth;

class RecipientController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
        $this->output_format = (new Auth)->output_format;
        $this->method_names = (new GeneralModel)->get_model_methods("AccountModel\RecipientModel");
    }

    public function createRecipient(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 1, 'POST');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function listRecipient(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 2, 'GET');
       } catch (\Exception $e) {
            return $e;
        }
    }

    public function fetchRecipient(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 3, 'GET', null,'id');
       } catch (\Exception $e) {
            return $e;
        }
    }

    public function updateRecipient(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 4, 'PUT', null,'id');
       } catch (\Exception $e) {
            return $e;
        }
    }

    public function deleteRecipient(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 5, 'DELETE', null,'id');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function setRecipientStatus(Request $request, Response $response)
    {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 6, 'PUT', null,'id');
        } catch (\Exception $e) {
            return $e;
        }
    }

}