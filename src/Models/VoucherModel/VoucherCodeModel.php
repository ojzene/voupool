<?php
namespace App\Models\VoucherModel;
use App\Config\Auth;
use App\Config\Encryption;
use App\Config\RandomStringGenerator;
use App\Models\GeneralModel as GeneralModel;
use App\Models\OfferModel\SpecialOfferModel;
use DateTime;
use RedBeanPHP\R;

class VoucherCodeModel extends SpecialOfferModel {

    public function __construct() {}

    public function createVoucherCode($input) {
        if(empty($input))
        {
            $result = (new GeneralModel)->responseHandler(false, 5011);
        }
        else
        {
            $code = (new GeneralModel)->generateRandomString(8);
            $offer_name = (string)$input['offer_name'];
            $recipient_email = (string)$input['recipient_email'];
            $expiration_date = (string)$input['expiration_date'];
            $active = true;  // true or false

            if(!empty($code) && !empty($offer_name) && !empty($recipient_email))
            {
                $expiry_date = new DateTime($expiration_date);
                $current_date = new DateTime();

                if ($expiry_date > $current_date)
                {
                    $get_offer_by_id = $this::getSpecialOfferByName($offer_name); // check if special offer exist
                    if(!is_null($get_offer_by_id)) {
                        $voucher = R::dispense("vouchercodes");
                        $voucher->id = "";
                        $voucher->code = strtoupper($code);
                        $voucher->specialoffer = $offer_name;
                        $voucher->recipient = $recipient_email;
                        $voucher->expirationdate = $expiration_date;
                        $voucher->usagedate = "";
                        $voucher->usagenumber = 0;
                        $voucher->active = $active ? $active : false;
                        $voucher->createdon = date("d-m-Y");

                        $get_voucher = $this->getVoucherByCode($code);
                        if (is_null($get_voucher)) {
                            try {
                                R::store($voucher);
                                $decoded_voucher = json_decode($voucher, true);
                                $voucher_array = [$decoded_voucher];
                                $data = "";
                                foreach ($voucher_array as $key) {
                                    unset($key['id']);
                                    $data = $key;
                                }
                                $result = (new GeneralModel)->responseHandler(true, 9005, $data);
                            } catch (\Exception $e) {
                                $result = (new GeneralModel)->responseHandler(false, 5011, $e->getMessage());
                            }
                        } else {
                            $result = (new GeneralModel)->responseHandler(false, 9003);
                        }
                    }
                    else {
                        $result = (new GeneralModel)->responseHandler(false, 7004);
                    }
                }
                else {
                    $result = (new GeneralModel)->responseHandler(false, 9008);
                }
            }
            else
            {
                $result = (new GeneralModel)->responseHandler(false, 6006);
            }
        }
        return $result;
    }

    public function listVoucherCode() {
        $find_result = R::findAll("vouchercodes", "ORDER BY code");
        $count = count($find_result);
        if($count)
        {
            $data = [];
            foreach ($find_result as $key) {
                unset($key['id']);
                $data[] = $key;
            }
            $message = [ "count" => $count, "data" => $data ];
            $result = (new GeneralModel)->responseHandler(true, 5010, $message);
        }
        else
        {
            $result = (new GeneralModel)->responseHandler(false, 9001);
        }
        return $result;
    }

    public function fetchVoucherCode($code) {
        if(empty($code)) {
            $result = (new GeneralModel)->responseHandler(false, 9002);
        }
        else {
            $code = $code[0];
            $find_result = R::findOne("vouchercodes", 'code=?', [ $code ]);
            if(count($find_result))
            {
                $decoded_vouchercodes = json_decode($find_result, true);
                $voucher_array = [ $decoded_vouchercodes ];
                $data = "";
                foreach ($voucher_array as $key) {
                    unset($key['id']);
                    $data = $key;
                }
                $result = (new GeneralModel)->responseHandler(true, 5010, $data);
            }
            else
            {
                $result = (new GeneralModel)->responseHandler(false, 9004);
            }
        }
        return $result;
    }

    public function redeemVoucherCode($input) {
        $result = [];
        if (empty($input))
        {
            $result = (new GeneralModel)->responseHandler(false, 5005);
        }
        else
        {
            $voucher_code = $input['code'];
            $recipient_email = $input['email'];
            $get_voucher = $this->getVoucherByCode($voucher_code);
            if (!is_null($get_voucher))
            {
                if ($get_voucher['active'] == false && ($get_voucher['usagenumber'] == 0))
                {
                    $result = (new GeneralModel)->responseHandler(false, 9007);
                }
                else
                {
                    if(($get_voucher['usagenumber'] != 0) || ($get_voucher['usagenumber'] != "0"))
                    {
                        $result = (new GeneralModel)->responseHandler(false, 9006);
                    }
                    else
                    {
                        $get_expiry_date = $get_voucher['expirationdate'];
                        $expiry_date = new DateTime($get_expiry_date);
                        $current_date = new DateTime();

                        if ($expiry_date > $current_date)
                        {
                            $offer_name = $get_voucher['specialoffer'];
                            $get_special_offer = $this::getSpecialOfferByName($offer_name);
                            if(!is_null($get_special_offer))
                            {
                                $percent_discount = $get_special_offer['discount'];

                                $get_voucher['active'] = false;
                                $get_voucher['recipient'] = $recipient_email;
                                $get_voucher['usagenumber'] = 1;
                                $get_voucher['usagedate'] = date("d-m-Y");
                                R::store($get_voucher);

                                $decoded_voucher = json_decode($get_voucher, true);
                                $voucher_array = [$decoded_voucher];
                                $data = "";
                                foreach ($voucher_array as $key) {
                                    unset($key['id']);
                                    $data = $key;
                                    $data['percent_discount'] = $percent_discount;
                                }
                                $result = (new GeneralModel)->responseHandler(true, 5010, $data);
                            }
                            else
                            {
                                $result = (new GeneralModel)->responseHandler(false, 7002);
                            }
                        }
                        else
                        {
                            $get_voucher['active'] = false;
                            $get_voucher['usagenumber'] = 1;
                            $get_voucher['usagedate'] = date("d-m-Y");
                            R::store($get_voucher);
                            $result = (new GeneralModel)->responseHandler(false, 9009);
                        }
                    }
                }
            } elseif (!count($get_voucher)) {
                $result = (new GeneralModel)->responseHandler(false, 9004);
            }
        }
        return $result;
    }

    public function listRecipientVoucherCodes($recipient_email) {
        if (empty($recipient_email)) {
            $result = (new GeneralModel)->responseHandler(false, 5005);
        }
        else
        {
            $recipient_email = $recipient_email[0];
            $find_result = R::findAll("vouchercodes", "recipient=? AND active=?", [ $recipient_email, 1,  ]);
            $count = count($find_result);
            if($count)
            {
                $data = [];
                foreach ($find_result as $key) {
                    unset($key['id']);
                    $data[] = $key;
                }
                $message = [ "count" => $count, "data" => $data ];
                $result = (new GeneralModel)->responseHandler(true, 5010, $message);
            }
            else {
                $result = (new GeneralModel)->responseHandler(false, 9010);
            }
        }
        return $result;
    }

    public function getVoucherByOfferName($special_offer) {
        $get_result = R::findAll("vouchercodes", 'specialoffer=?', [ $special_offer ]);
        if (count($get_result)) {
            return $get_result;
        } else {
            return null;
        }
    }

    public function getVoucherByCode($code) {
        $get_result = R::findOne("vouchercodes", "code=?", [ $code ]);
        if (count($get_result)) {
            return $get_result;
        } else {
            return null;
        }
    }

}