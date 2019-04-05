<?php

namespace App\Services;


interface PaymentServiceInterface
{
    const CURRENCY_UAH = 'UAH';
    const CURRENCY_USD = 'USD';
    const CURRENCY_EURO = 'EURO';


    /**
     * @param string $callbackUrl
     * @return string
     */
    public function getPaymentForm(string $callbackUrl) : string;


    /**
     * @return bool
     */
    public function submitPayment() : bool;

    /**
     * @param string $callbackUrl
     * @param bool $status
     * @return void
     */
    public function sendPaymentStatus(string $callbackUrl, bool $status) : void;

}