<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2011-2015 BitPay
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require_once 'bp_options.php';

function bpIPN($url){
	$ch = curl_init();
	$request_headers = array();
    $request_headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$responseString = curl_exec($ch);
	$response = json_decode($responseString, true);
	curl_close($ch);
    return $response;

}

function bpCurl($url, $apiKey, $post = false)
{
    global $bpOptions;

    $ch = curl_init();
    $length = 0;
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $length = strlen($post);
	}
    $request_headers = array();
    $request_headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $responseString = curl_exec($ch);

    if ($responseString == false) {

        $response = curl_error($ch);
    } else {

        $response = json_decode($responseString, true);
    }
    curl_close($ch);
    return $response;
}
// $orderId: Used to display an orderID to the buyer. In the account summary view, this value is used to
// identify a ledger entry if present.
//
// $price: by default, $price is expressed in the currency you set in bp_options.php.  The currency can be
// changed in $options.
//
// $posData: this field is included in status updates or requests to get an invoice.  It is intended to be used by
// the merchant to uniquely identify an order associated with an invoice in their system.  Aside from that, Bit-Pay does
// not use the data in this field.  The data in this field can be anything that is meaningful to the merchant.
//
// $options keys can include any of:
// ('itemDesc', 'itemCode', 'notificationEmail', 'notificationURL', 'redirectURL', 'apiKey'
//        'currency', 'physical', 'extendedNotifications', 'transactionSpeed', 'buyerName',
//        'buyerAddress1', 'buyerAddress2', 'buyerCity', 'buyerState', 'buyerZip', 'buyerEmail', 'buyerPhone')
// If a given option is not provided here, the value of that option will default to what is found in bp_options.php
// (see api documentation for information on these options).
function bpCreateInvoice($orderId, $price, $posData, $options = array())
{
    global $bpOptions;

    $options = array_merge($bpOptions, $options); // $options override any options found in bp_options.php

    $options['posData'] = '{"posData": "' . $posData . '"';
    if ($bpOptions['verifyPos']) // if desired, a hash of the POS data is included to verify source in the callback
    {
        $options['posData'] .= ', "hash": "' . crypt($posData, $options['apiKey']) . '"';
    }

    $options['posData'] .= '}';

    $options['orderID'] = $orderId;
    $options['price'] = $price;
    if ($options['env'] == 'Test') {
        #sandbox token
        $options['token'] = MODULE_PAYMENT_BITPAY_APIKEY_DEV;
    } else {
        #production token
        $options['token'] = MODULE_PAYMENT_BITPAY_APIKEY;
    }

    $postOptions = array('orderID', 'itemDesc', 'itemCode', 'notificationEmail', 'notificationURL', 'redirectURL',
        'posData', 'price', 'currency', 'physical', 'extendedNotifications', 'token', 'transactionSpeed', 'buyerName',
        'buyerAddress1', 'buyerAddress2', 'buyerCity', 'buyerState', 'buyerZip', 'buyerEmail', 'buyerPhone');
    foreach ($postOptions as $o) {
        if (array_key_exists($o, $options)) {
            $post[$o] = $options[$o];
        }
    }

    $post = json_encode($post);

    if ($options['env'] == 'Test') {
        $response = bpCurl('https://test.bitpay.com/invoices/', $options['apiKey'], $post);
    } else {
        $response = bpCurl('https://bitpay.com/invoices/', $options['apiKey'], $post);
    }

    return $response;
}

// Call from your notification handler to convert $_POST data to an object containing invoice data
function bpVerifyNotification($apiKey = false, $env = null)
{
    global $bpOptions;

    $all_data = json_decode(file_get_contents("php://input"), true);

    $data = $all_data['data'];
    $event = $all_data['event'];

    return bpGetInvoice($data['id'], $env);
}

// $options can include ('apiKey')
function bpGetInvoice($invoiceId, $env)
{
   if($env == 'Test'){
	$response = bpIPN('https://test.bitpay.com/invoices/' . $invoiceId);
   }else{
	$response = bpIPN('https://bitpay.com/invoices/' . $invoiceId);
   }
  
   return $response;
}
