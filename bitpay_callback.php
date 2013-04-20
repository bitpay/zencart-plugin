<?
require 'bitpay/bp_lib.php';
require 'includes/application_top.php';

function bplog($contents)
{
	file_put_contents('bitpay/log.txt', $contents, FILE_APPEND);
}


$response = bpVerifyNotification(MODULE_PAYMENT_BITPAY_APIKEY);
if (is_string($response))
	bplog(date('H:i')." bitpay callback error: $response\n");
else {
	bplog(date('H:i')." bitpay callback for invoice ".$response['id']." status: ".$response['status']);
	
	global $db;
	$order_id = $response['posData'];
	switch($response['status'])
	{
		case 'confirmed':		
		case 'complete':
			$db->Execute("update ". TABLE_ORDERS. " set orders_status = " . MODULE_PAYMENT_BITPAY_PAID_STATUS_ID . " where orders_id = ". intval($order_id));			
			break;
		
		// (bit-pay.com does not send expired notifications as of this release)
		case 'expired':			
			zen_remove_order($order_id, $restock = true);
			break;

	}
}
?>