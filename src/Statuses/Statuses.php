<?php
    namespace App\Statuses;

    class Statuses
    {
        public function errorCodes()
        {
            $code_array =
                [
                    4001 => "Token cannot be found in the database",
                    4002 => "Token has expired",
                    4003 => "Phone number not found",
                    4004 => "Token for this phone number doesn't exist",
                    4005 => "Phone number already existed in the database",
                    6099 => "Date is required",
                    6088 => "Date not properly formatted, the proper formate is ",
                    6006 => "Some keys or fields are missing",
                    5005 => "Bad request or one of the fields is empty",
                    5008 => "Request(s) not allowed",
                    5009 => "Deletion successful",
                    5010 => "Operation successful",
                    5011 => "Operation failed",
                    7000 => "Special Offer successfully created",
                    7001 => "No Special Offer found",
                    7002 => "No Such Special Offer",
                    7003 => "Special Offer already existed",
                    7004 => "Special Offer does not exist",
                    7005 => "Special Offer ID cannot be empty",
                    7006 => "Special Offer is not active",
                    7007 => "Field cannot be empty",
                    7008 =>  "Percent discount can only be between 1 and 100",
                    8002 => "Client not verified",
                    8006 => "Empty Body",
                    8012 => "Oops this is embarrassing, for some reason we're unable to process your request.",
                    8013 => "Page not found",
                    8050 => "We're currently undergoing maintenance, be back with you shortly",
                    8080 => "Method not allowed",
                    8081 => "Error! Please check your internet connection",
                    8082 => "Error! Invalid Request",
                    9001 => "Not a single voucher code found",
                    9002 => "Voucher Code cannot be empty",
                    9003 => "Voucher Code already existed",
                    9004 => "Voucher Code does not exist",
                    9005 => "Voucher Code successfully generated",
                    9006 => "Voucher Code has already been used",
                    9007 => "Voucher Code is not active",
                    9008 => "Expiration date is in the past, Voucher Code cannot be generated",
                    9009 => "Voucher Code had expired and cannot be used again",
                    9010 => "No Valid Voucher Code found for this recipient",
                ];
            return $code_array;
        }

        public function getStatusError()
        {
            $status_array = [ 6000 => true, 6001 => false ];
            return $status_array;
        }

        public function getStatus($code, $object_response=null)
        {
            $code_array = $this->getStatusError();
            $status = $code_array[$code];
            $statusHandler = [ 'code' => $code,'success' => $status, 'data' => $object_response];
            return $statusHandler;
        }

        public function addrStatus($code, $object_response=null)
        {
            $code_array = $this->errorCodes();
            $status = $code_array[$code];
            $statusHandler = [ 'code' => $code,'success' => $status, 'data' => $object_response];
            return $statusHandler;
        }

        public function pageListStatus($statuses, $page=null, $limit=null, $object_response=null)
        {
            $status_array = $this->getStatusError();
            $status = $status_array[$statuses];
            $statusHandler = [ 'success' => $status, 'code' => $statuses, 'page' => $page, 'items_per_page' => $limit, 'data' => $object_response];
            return $statusHandler;
        }

        public function getStatusWithError($statuses, $code)
        {
            $status_array = $this->getStatusError();
            $status = $status_array[$statuses];
            $code_array = $this->errorCodes();
            $status_code = $code_array[$code];
            $statusHandler = [ 'success' => $status, 'code' => $code, 'data' => $status_code ];
            return $statusHandler;
        }

        public function getStatusWithErrors($statuses, $code, $error)
        {
            $status_array = $this->getStatusError();
            $status = $status_array[$statuses];
            $code_array = $this->errorCodes();
            $status_code = $code_array[$code];
            $statusHandler = [ 'success' => $status, 'code' => $code, 'data' => $error];
            return $statusHandler;
        }

        public function getStatusWithErrorAndData($statuses, $code, $format)
        {
            $status_array = $this->getStatusError();
            $status = $status_array[$statuses];
            $code_array = $this->errorCodes();
            $status_code = $code_array[$code];
            $statusHandler = [ 'success' => $status, 'code' => $code, 'data' => $status_code.$format];
            return $statusHandler;
        }

    }