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

require 'bitpay/bp_lib.php';
require 'includes/application_top.php';

function bplog($contents) {
    if (true === isset($contents)) {
        if (true === is_resource($contents)) {
            error_log(serialize($contents));
        } else {
            error_log(var_export($contents, true));
        }
    }
}

function validateResponse($response, $keys) {
    if (is_array($response) &&
        array_key_exists($keys[0], $response) &&
        array_key_exists($keys[0], $response[$keys[0]]) &&
        preg_match('/^\d+$/', $response[$keys[0]][$keys[0]]) &&
        array_key_exists($keys[1], $response)) {
        return true;
    }
    return false;
}

$response = bpVerifyNotification(MODULE_PAYMENT_BITPAY_APIKEY);
$keys = array('posData', 'status');

if (!validateResponse($response, $keys)) {
    bplog(date('H:i') . " bitpay callback error: " . $response . "\n");
} else {
    global $db;
    $order_id = $response[$keys[0]][$keys[0]];
    $status = $response[$keys[1]];
    switch ($status) {
        case 'confirmed':
        case 'complete':
            $db->Execute("update ". TABLE_ORDERS. " set orders_status = " . MODULE_PAYMENT_BITPAY_PAID_STATUS_ID . " where orders_id = ". intval($order_id));
            break;
        case 'expired':
            if (true === function_exists('zen_remove_order')) {
                zen_remove_order($order_id, $restock = true);
            }
            break;
    }
}
