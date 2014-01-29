<?php

/**
 * ©2011,2012,2013,2014 BITPAY, INC.
 * 
 * Permission is hereby granted to any person obtaining a copy of this software
 * and associated documentation for use and/or modification in association with
 * the bitpay.com service.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * Bitcoin payment plugin using the bitpay.com service.
 * 
 */
 
require 'bitpay/bp_lib.php';
require 'includes/application_top.php';

function bplog($contents) {
  //off by default
  //file_put_contents('bitpay/log.txt', $contents, FILE_APPEND);
}


$response = bpVerifyNotification(MODULE_PAYMENT_BITPAY_APIKEY);

if (is_string($response))
  bplog(date('H:i')." bitpay callback error: $response\n");
else {
  bplog(date('H:i')." bitpay callback for invoice ".$response['id']." status: ".$response['status']);
  
  global $db;
  $order_id = $response['posData'];

  switch($response['status']) {
    case 'confirmed':    
    case 'complete':
      $db->Execute("update ". TABLE_ORDERS. " set orders_status = " . MODULE_PAYMENT_BITPAY_PAID_STATUS_ID . " where orders_id = ". intval($order_id));      
      break;
    case 'expired':
      if(function_exists('zen_remove_order'))
        zen_remove_order($order_id, $restock = true);
      break;
  }
}
?>
