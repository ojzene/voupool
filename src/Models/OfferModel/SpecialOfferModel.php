<?php
namespace App\Models\OfferModel;
use App\Config\Auth;
use App\Config\Encryption;
use App\Config\RandomStringGenerator;
use App\Models\GeneralModel as GeneralModel;
use RedBeanPHP\R;

class SpecialOfferModel
{
    public function __construct() {}

    public function createSpecialOffer($input) {
        if(empty($input))
        {
            $result = (new GeneralModel)->responseHandler(false, 5005);
        }
        else
        {
            $name = (string)$input['name'];
            $percent_discount = (float)$input['percent_discount']; // discount is below or equal 100%
            $active = true;
            $offerid = (new GeneralModel)->generateRandomString(10);

            if(!empty($name) && !empty($percent_discount))
            {
                if(($percent_discount >=1) || ($percent_discount <=100))
                {
                    $specialoffers = R::dispense("specialoffers");
                    $specialoffers->id = "";
                    $specialoffers->name = $name;
                    $specialoffers->offerid = $offerid;
                    $specialoffers->discount = $percent_discount;
                    $specialoffers->active = $active ? $active : false;
                    $specialoffers->createdon = date("d-m-Y");

                    $getSpecialOfferByName = $this->getSpecialOfferByName($name);
                    if (is_null($getSpecialOfferByName)) {
                        try {
                            R::store($specialoffers);
                            $decoded_offer = json_decode($specialoffers, true);
                            $offer_array = [$decoded_offer];
                            $data = "";
                            foreach ($offer_array as $key) {
                                unset($key['id']);
                                $data = $key;
                            }
                            $result = (new GeneralModel)->responseHandler(true, 7000, $data);
                        } catch (\Exception $e) {
                            $result = (new GeneralModel)->responseHandler(false, 5011, $e->getMessage());
                        }
                    } else {
                        $result = (new GeneralModel)->responseHandler(false, 7003);
                    }
                }
                else
                {
                    $result = (new GeneralModel)->responseHandler(false, 7008);
                }
            }
            else
            {
                $result = (new GeneralModel)->responseHandler(false, 6006);
            }
        }
        return $result;
    }

    public function listSpecialOffer()
    {
        $findSpecialOffer = R::findAll("specialoffers", "ORDER BY name");
        $count = count($findSpecialOffer);
        if($count)
        {
            $data = [];
            foreach ($findSpecialOffer as $key) {
                unset($key['id']);
                $data[] = $key;
            }
            $message = [ "count" => $count, "data" => $data ];
            $result = (new GeneralModel)->responseHandler(true, 5010, $message);
        }
        else {
            $result = (new GeneralModel)->responseHandler(false, 7001);
        }
        return $result;
    }

    public function fetchSpecialOffer($offerid) {
        if(empty($offerid)) {
            $result = (new GeneralModel)->responseHandler(false, 7005);
        }
        else {
            $offerid = $offerid[0];
            $findSpecialOffer = R::findOne("specialoffers", 'offerid=?', [ $offerid ]);
            if (count($findSpecialOffer)) {
                $decoded_specialoffers = json_decode($findSpecialOffer, true);
                $specialoffer_array = [$decoded_specialoffers];
                $data = "";
                foreach ($specialoffer_array as $key) {
                    unset($key['id']);
                    $data = $key;
                }
                $result = (new GeneralModel)->responseHandler(true, 5010, $data);
            } else {
                $result = (new GeneralModel)->responseHandler(false, 7002);
            }
        }
        return $result;
    }

    public function updateSpecialOffer($input, $offerid)
    {
        $result = [];
        if (empty($offerid))
        {
            $result = (new GeneralModel)->responseHandler(false, 7005);
        }
        else
        {
            if(empty($input)) {
                $result = (new GeneralModel)->responseHandler(false, 5005);
            }
            else {
                $get_special_offer = $this->getSpecialOfferByID($offerid);
                if (count($get_special_offer)) {
                    if ($get_special_offer['active'] == false) {
                        $result = (new GeneralModel)->responseHandler(false, 7006);
                    } else {
                        $get_special_offer['name'] = (string)$input['name'];
                        R::store($get_special_offer);

                        $decoded_special_offers = json_decode($get_special_offer, true);
                        $special_offer_array = [$decoded_special_offers];
                        $data = "";
                        foreach ($special_offer_array as $key) {
                            unset($key['id']);
                            $data = $key;
                        }
                        $result = (new GeneralModel)->responseHandler(true, 5010, $data);
                    }
                } elseif (!count($get_special_offer)) {
                    $result = (new GeneralModel)->responseHandler(false, 7004);
                }
            }

        }
        return $result;
    }

    public function getSpecialOfferByName($name)
    {
        $get_result = R::findOne("specialoffers", "name=?", [ $name ]);
        if (count($get_result)) {
            return $get_result;
        } else {
            return null;
        }
    }

    public function getSpecialOfferByID($offerid)
    {
        $get_result = R::findOne("specialoffers", "offerid=?", [ $offerid ]);
        if (count($get_result)) {
            return $get_result;
        } else {
            return null;
        }
    }

}