<?php
namespace App\Models\AccountModel;
use App\Config\Auth;
use App\Config\Encryption;
use App\Config\RandomStringGenerator;
use App\Models\GeneralModel as GeneralModel;
use RedBeanPHP\R;

class RecipientModel
{
    public function __construct()
    {
        $this->address_url = (new Auth)->paygateway_url;
        $this->get_auth = (new Auth)->paygateway_secret_key;
    }

    public function createRecipient($input)
    {
        if (empty($input)) {
            $result = (new GeneralModel)->responseHandler(false, 5011, "Bad request or one of the fields empty");
        }
        else {
            $firstname = (string)$input['firstname'];
            $lastname = (string)$input['lastname'];
            $email = (string)$input['email'];
            $active = true;
            $recipientid = (new GeneralModel)->generateRandomString(6);
            
            if(!empty($firstname) && !empty($lastname) && !empty($email)) {
                $recipients = R::dispense("recipients");
                $recipients->id = "";
                $recipients->recipientid = $recipientid;
                $recipients->firstname = $firstname;
                $recipients->lastname = $lastname;
                $recipients->email = $email;
                $recipients->active = $active;
                $recipients->createdon = date("d-m-Y h:i:sa");

                /**
                 *  check if recipient email already exist
                **/
                $findRecipientByEmail = $this->getRecipientByEmail($recipients->email);
                if (is_null($findRecipientByEmail)) {
                    try {
                        R::store($recipients);

                        $message = "Recipient account successfully created";
                        $result = (new GeneralModel)->responseHandler(true, 5010, $message);
                    } catch (\Exception $e) {
                        $result = (new GeneralModel)->responseHandler(false, 5011, $e->getMessage());
                    }
                } else {
                    $message = "Recipient with this email already exist";
                    $result = (new GeneralModel)->responseHandler(false, 5011, $message);
                }
            }
            else {
                $message = "Some keys or fields are missing";
                $result = (new GeneralModel)->responseHandler(false, 6006, $message);
            }
        }
        return $result;
    }

    public function listRecipient()
    {
        $findRecipient = R::findAll("recipients", "ORDER BY firstname");
        $count = count($findRecipient);
        if ($count) {
            $data = [];
            foreach ($findRecipient as $key) {
                unset($key['id']);
                $data[] = $key;
            }
            $message = ["count" => $count, "data" => $data];
            $result = (new GeneralModel)->responseHandler(true, 5010, $message);
        }
        else {
            $result = (new GeneralModel)->responseHandler(false, 5011, null);
        }
        return $result;
    }

    public function fetchRecipient($recipientid)
    {
        if(empty($recipientid))
        {
            $result = (new GeneralModel)->responseHandler(false, 5011, "Recipient ID cannot be empty");
        }
        else
        {
            $findRecipient = R::findOne("recipients", 'recipientid=?', [ $recipientid ]);
            if (count($findRecipient)) {
                $decoded_recipients = json_decode($findRecipient, true);
                $recipient_array = [$decoded_recipients];
                $data = "";
                foreach ($recipient_array as $key) {
                    unset($key['id']);
                    $data = $key;
                }
                $result = (new GeneralModel)->responseHandler(true, 5010, $data);
            } else {
                $result = (new GeneralModel)->responseHandler(false, 5011, "Recipient does not exist");
            }
        }
        return $result;
    }

    public function updateRecipient($input, $dataparams)
    {
        $result = [];
        $recipientid = $dataparams['attribute'];
        if(empty($recipientid))
        {
            $result = (new GeneralModel)->responseHandler(false, 5011, "Recipient ID cannot be empty");
        }
        else
        {
            $get_recipient = $this->getRecipientByRecipientID($recipientid);
            if(count($get_recipient))
            {
                if($get_recipient['active'] == false) {
                    $result = (new GeneralModel)->responseHandler(false, 5011, "Recipient account is not active");
                }
                else
                {
                    if(empty($input)) {
                        $result = (new GeneralModel)->responseHandler(false, 5011, "Request cannot be empty");
                    }
                    else {
                        $get_recipient['firstname'] = (string)$input['firstname'];
                        $get_recipient['lastname'] = (string)$input['lastname'];
                        R::store($get_recipient);

                        $decoded_recipients = json_decode($get_recipient, true);
                        $recipient_array = [$decoded_recipients];
                        $data = "";
                        foreach ($recipient_array as $key) {
                            unset($key['id']);
                            $data = $key;
                        }
                        $result = (new GeneralModel)->responseHandler(true, 5010, $data);
                    }
                }
            } elseif (!count($get_recipient)) {
                $message = "Unable to find account for this email";
                $result = (new GeneralModel)->responseHandler(false, 5011, $message);
            }
        }
        return $result;
    }

    public function deleteRecipient($recipientid)
    {
        $recipientid = $recipientid[0];
        if(empty($recipientid)) {
            $result = (new GeneralModel)->responseHandler(false, 5011, "Request cannot be empty");
        } else {
            $get_recipient = $this->getRecipientByRecipientID($recipientid);
            if (is_null($get_recipient)) {
                $result = (new GeneralModel)->responseHandler(false, 5011, "Unable to find recipient account");
            } else {
                R::trash($get_recipient);
                $result = (new GeneralModel)->responseHandler(true, 5010, "Recipient Account deleted successfully");
            }
        }
        return $result;
    }

    public function setRecipientStatus($input, $dataparams)
    {
        $message = null;
        $recipientid = $dataparams['attribute'];
        $get_recipient = $this->getRecipientByRecipientID($recipientid);
        if(is_null($get_recipient))
        {
            $result = (new GeneralModel)->responseHandler(false, 5011, "Unable to find recipient account");
        }
        else
        {
            if(empty($input))
            {
                $result = (new GeneralModel)->responseHandler(false, 5011, "Request cannot be empty");
            }
            else
            {
                $status = (int)$input['status'];
                if(($status == 1) || ($status == 0)) {
                    $get_recipient['active'] = $status;
                    R::store($get_recipient);

                    if($status == 1) { $message = "Recipient account successfully activated"; }
                    elseif($status == 0) { $message = "Recipient account successfully deactivated"; }
                    $result = (new GeneralModel)->responseHandler(true, 5010, $message);
                }
                else {
                    $message = "Status not found";
                    $result = (new GeneralModel)->responseHandler(false, 5011, $message);
                }
            }
        }
        return $result;
    }

    public function getRecipientByEmail($email)
    {
        $findRecipient = R::findOne("recipients", 'email=?', [ $email ]);
        if (count($findRecipient)) {
            return $findRecipient;
        } else {
            return null;
        }
    }

    public function getRecipientByRecipientID($recipientid)
    {
        $findRecipientByPhone = R::findOne("recipients", "recipientid=?", [ $recipientid ]);
        if (count($findRecipientByPhone)) {
            return $findRecipientByPhone;
        } else {
            return null;
        }
    }

}