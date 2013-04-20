<?php
/**
 * Bit-pay.com Payment Module
 *
 * @package paymentMethod
 */
  class bitpay {
    var $code, $title, $description, $enabled, $payment;
	
	function log($contents){
		$file = 'bitpay/log.txt';
		file_put_contents($file, date('m-d H:i:s').": \n", FILE_APPEND);
		if (is_array($contents))
			foreach($contents as $k => $v)
				file_put_contents($file, $k.': '.$v."\n", FILE_APPEND);
		else
			file_put_contents($file, $contents."\n", FILE_APPEND);
	}
	
	// class constructor
	function bitpay() {
		global $order;
		$this->code = 'bitpay';
		$this->title = MODULE_PAYMENT_BITPAY_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_BITPAY_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_BITPAY_SORT_ORDER;
		$this->enabled = ((MODULE_PAYMENT_BITPAY_STATUS == 'True') ? true : false);

		if ((int)MODULE_PAYMENT_BITPAY_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_BITPAY_ORDER_STATUS_ID;
			$payment='bitpay';
		} else if ($payment=='bitpay') {
			$payment='';
		}

		if (is_object($order)) $this->update_status();

		$this->email_footer = MODULE_PAYMENT_BITPAY_TEXT_EMAIL_FOOTER;
	}

	// class methods
	function update_status() {
		global $db;
		global $order;

		// check zone
		if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_BITPAY_ZONE > 0) ) {
			$check_flag = false;
			$check = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . intval(MODULE_PAYMENT_BITPAY_ZONE) . "' and zone_country_id = '" . intval($order->billing['country']['id']) . "' order by zone_id");
			while (!$check->EOF) {
				if ($check->fields['zone_id'] < 1) {
					$check_flag = true;
					break;
				} elseif ($check->fields['zone_id'] == $order->billing['zone_id']) {
					$check_flag = true;
					break;
				}
				$check->MoveNext();
			}

			if ($check_flag == false) {
				$this->enabled = false;
			}
		}
		
		// check currency
		$currencies = array_map('trim',explode(",",MODULE_PAYMENT_BITPAY_CURRENCIES));
		if (array_search($order->info['currency'], $currencies) === false)
		{
			$this->enabled = false;
		}
					
		// check that api key is not blank
		if (!MODULE_PAYMENT_BITPAY_APIKEY OR !strlen(MODULE_PAYMENT_BITPAY_APIKEY))
		{
			print 'no secret '.MODULE_PAYMENT_BITPAY_APIKEY;
			$this->enabled = false;
		}
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

	// called upon requesting step 3
    function confirmation() {
 		return false;
    }
	
	// called upon requesting step 3 (after confirmation above)
	function process_button() {		
		return false;
	}

	// called upon clicking confirm
    function before_process() {
		return false; 
    }

	// called upon clicking confirm (after before_process and after the order is created)
    function after_process() {
		global $insert_id, $order, $db;
		require_once 'bitpay/bp_lib.php';    			
				
		// change order status to value selected by merchant
		$db->Execute("update ". TABLE_ORDERS. " set orders_status = " . intval(MODULE_PAYMENT_BITPAY_UNPAID_STATUS_ID) . " where orders_id = ". intval($insert_id));
				
		
		$options = array(
			'physical' => $order->content_type == 'physical' ? 'true' : 'false',
			'currency' => $order->info['currency'],
			'buyerName' => $order->customer['firstname'].' '.$order->customer['lastname'],			
			'fullNotifications' => 'true',
			'notificationURL' => zen_href_link('bitpay_callback.php', $parameters='', $connection='NONSSL', $add_session_id=true, $search_engine_safe=true, $static=true ),
			'redirectURL' => zen_href_link('account'),
			'transactionSpeed' => MODULE_PAYMENT_BITPAY_TRANSACTION_SPEED,
			'apiKey' => MODULE_PAYMENT_BITPAY_APIKEY,
			);
		$total = $order->info['total'];
		if ($order->info['currency_value'] != 1) 
		{
			global $currencies;
			$total *= $order->info['currency_value'];
			if ($currencies->is_set($order->info['currency']))
				$total = round($total, $currencies->get_decimal_places($order->info['currency']));
		}
		
		$invoice = bpCreateInvoice($insert_id, $total, $insert_id, $options);
		
		$this->log("created invoice orderID=$insert_id with options: ".var_export($options, true));
		$this->log("invoice: ".var_export($invoice, true));			
			
		if (isset($invoice['error']))
		{
			$this->log('createInvoice error '.var_export($invoice['error'], true));
			zen_remove_order($insert_id, $restock = true);
			// unfortunately, there's not a good way of telling the customer that it's hosed.  Their cart is still full so they can try again w/ a different payment option.
		}
		else
		{
			$_SESSION['cart']->reset(true);
			zen_redirect($invoice['url']);
		}

		
		return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_BITPAY_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
		global $db, $messageStack;
		if (defined('MODULE_PAYMENT_BITPAY_STATUS')) {
			$messageStack->add_session('Bit-pay module already installed.', 'error');
			zen_redirect(zen_href_link(FILENAME_MODULES, 'set=payment&module=bitpay', 'NONSSL'));
			return 'failed';
		}
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) "
		."values ('Enable Bit-pay Module', 'MODULE_PAYMENT_BITPAY_STATUS', 'True', 'Do you want to accept bitcoin payments via bit-pay.com?', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now());");

		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) "
		."values ('API Key', 'MODULE_PAYMENT_BITPAY_APIKEY', '', 'Enter you API Key which you generated at bitpay.com', '6', '0', now());");

		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) "
		."values ('Transaction speed', 'MODULE_PAYMENT_BITPAY_TRANSACTION_SPEED', 'low', 'At what speed do you want the transactions to be considered confirmed?', '6', '0', 'zen_cfg_select_option(array(\'high\', \'medium\', \'low\'),', now());");

		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) "
		."values ('Unpaid Order Status', 'MODULE_PAYMENT_BITPAY_UNPAID_STATUS_ID', '" . intval(DEFAULT_ORDERS_STATUS_ID) .  "', 'Automatically set the status of unpaid orders to this value.', '6', '0', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");

		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) "
		."values ('Paid Order Status', 'MODULE_PAYMENT_BITPAY_PAID_STATUS_ID', '2', 'Automatically set the status of paid orders to this value.', '6', '0', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");

		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) "
		."values ('Currencies', 'MODULE_PAYMENT_BITPAY_CURRENCIES', 'BTC, USD, EUR, GBP, AUD, BGN, BRL, CAD, CHF, CNY, CZK, DKK, HKD, HRK, HUF, IDR, ILS, INR, JPY, KRW, LTL, LVL, MXN, MYR, NOK, NZD, PHP, PLN, RON, RUB, SEK, SGD, THB, TRY, ZAR', 'Only enable bit-pay payments if one of these currencies is selected (note: currency must be supported by bit-pay.com).', '6', '0', now())");
			
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) "
		."values ('Payment Zone', 'MODULE_PAYMENT_BITPAY_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) "
		."values ('Sort order of display.', 'MODULE_PAYMENT_BITPAY_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '2', now())");
    }

    function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array(
		'MODULE_PAYMENT_BITPAY_STATUS', 
		'MODULE_PAYMENT_BITPAY_APIKEY',
		'MODULE_PAYMENT_BITPAY_TRANSACTION_SPEED',
		'MODULE_PAYMENT_BITPAY_UNPAID_STATUS_ID',
		'MODULE_PAYMENT_BITPAY_PAID_STATUS_ID',
		'MODULE_PAYMENT_BITPAY_SORT_ORDER',
		'MODULE_PAYMENT_BITPAY_ZONE',		
		'MODULE_PAYMENT_BITPAY_CURRENCIES',
		);
    }
  }
