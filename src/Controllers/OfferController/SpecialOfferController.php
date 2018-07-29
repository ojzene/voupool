<?php
namespace App\Controllers\OfferController;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\GeneralModel;
use App\Config\Auth;

class SpecialOfferController {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
        $this->output_format = (new Auth)->output_format;
        $this->method_names = (new GeneralModel)->get_model_methods("OfferModel\SpecialOfferModel");
    }

    public function createSpecialOffer(Request $request, Response $response) {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 1, 'POST');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function listSpecialOffer(Request $request, Response $response) {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 2, 'GET');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function fetchSpecialOffer(Request $request, Response $response) {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 3, 'GET', '','offerid');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function updateSpecialOffer(Request $request, Response $response) {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 4, 'PUT', '','id');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function deleteSpecialOffer(Request $request, Response $response) {
        try {
            return (new GeneralModel)->reqres_parser($request, $response, $this->method_names, 5, 'PUT', '','id');
        } catch (\Exception $e) {
            return $e;
        }
    }
}