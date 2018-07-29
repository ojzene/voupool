<?php

if ($mode == 'production' || $mode == 'debug')
{
    // API ROUTES
    $app->group('/api', function () use ($app) {
        $app->group('/v1', function () use ($app){
            $app->group('/recipients', function () use ($app){
                $app->post('', 'RecipientController:createRecipient');
                $app->get('', 'RecipientController:listRecipient');
                $app->get('/{id}', 'RecipientController:fetchRecipient');
                $app->put('/{id}', 'RecipientController:updateRecipient');
                $app->delete('/{id}', 'RecipientController:deleteRecipient');
                $app->put('/status/{id}', 'RecipientController:setRecipientStatus');
            });
            $app->group('/offers', function () use ($app){
                $app->post('', 'SpecialOfferController:createSpecialOffer');
                $app->get('', 'SpecialOfferController:listSpecialOffer');
                $app->get('/{offerid}', 'SpecialOfferController:fetchSpecialOffer');
                $app->put('/{offerid}', 'SpecialOfferController:updateSpecialOffer');
                $app->delete('/{id}', 'SpecialOfferController:deleteSpecialOffer');
            });
            $app->group('/vouchers', function () use ($app){
                $app->post('', 'VoucherCodeController:createVoucherCode');
                $app->get('', 'VoucherCodeController:listVoucherCode');
                $app->get('/{code}', 'VoucherCodeController:fetchVoucherCode');
                $app->post('/redeem', 'VoucherCodeController:redeemVoucherCode');
                $app->get('/recipient/{email}', 'VoucherCodeController:listRecipientVoucherCodes');
            });
        });
    });
}