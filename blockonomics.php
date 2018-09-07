<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2011-2014 Blockonomics
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

function bplog($contents)
{
    error_log($contents);
}

defined('_JEXEC') or die('Restricted access');

if (!class_exists('vmPSPlugin'))
{
    require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
}

class Blockonomics
{
    const BASE_URL = 'https://www.blockonomics.co';
    const NEW_ADDRESS_URL = 'https://www.blockonomics.co/api/new_address';
    const PRICE_URL = 'https://www.blockonomics.co/api/price';
    const ADDRESS_URL = 'https://www.blockonomics.co/api/address?only_xpub=true&get_callback=true';
    const SET_CALLBACK_URL = 'https://www.blockonomics.co/api/update_callback';
    const GET_CALLBACK_URL = 'https://www.blockonomics.co/api/address?&no_balance=true&only_xpub=true&get_callback=true';
    public function __construct()
    {
    }
    public function new_address($api_key, $secret, $reset=false)
    {
        $options = array(
            'http' => array(
                'header'  => 'Authorization: Bearer ' . $api_key,
                'method'  => 'POST',
                'content' => '',
                'ignore_errors' => true
            )
        );
        if($reset)
        {
            $get_params = "?match_callback=$secret&reset=1";
        } 
        else
        {
            $get_params = "?match_callback=$secret";
        }
        
        $context = stream_context_create($options);
        $contents = file_get_contents(Blockonomics::NEW_ADDRESS_URL.$get_params, false, $context);
        $responseObj = json_decode($contents);
        //Create response object if it does not exist
        if (!isset($responseObj)) $responseObj = new stdClass();
        $responseObj->{'response_code'} = $http_response_header[0];
        return $responseObj;
    }
    public function get_price($currency)
    {
        $options = array( 'http' => array( 'method'  => 'GET') );
        $context = stream_context_create($options);
        $contents = file_get_contents(Blockonomics::PRICE_URL. "?currency=$currency", false, $context);
        $price = json_decode($contents);
        return $price->price;
    }
    public function get_xpubs($api_key)
    {
        $options = array(
            'http' => array(
                'header'  => 'Authorization: Bearer ' . $api_key,
                'method'  => 'GET',
                'content' => '',
                'ignore_errors' => true
            )
        );
        $context = stream_context_create($options);
        $contents = file_get_contents(Blockonomics::ADDRESS_URL, false, $context);
        $responseObj = json_decode($contents);
        return $responseObj;
    }
    public function update_callback($api_key, $callback_url, $xpub)
    {
        $options = array(
            'http' => array(
                'header'  => 'Authorization: Bearer ' . $api_key,
                'method'  => 'POST',
                'content' => '{"callback": "'.$callback_url.'", "xpub": "'.$xpub.'"}',
                'ignore_errors' => true
            )
        );
        $context = stream_context_create($options);
        $contents = file_get_contents(Blockonomics::SET_CALLBACK_URL, false, $context);
        $responseObj = json_decode($contents);
        return $responseObj;
    }


}

jimport('joomla.form.formfield');

class JFormFieldCity extends JFormField {
	
	protected $type = 'City';

	// getLabel() left out
	public function getInput() {
        // $db = JFactory::getDBO();
        // $query = 'SELECT `payment_params` FROM ' . '#__virtuemart_paymentmethods' . " WHERE  `payment_element`= '" . "blockonomics" ."'";
        // $db->setQuery($query);
        // $result = $db->loadResult();
        // $array = explode('|', $result);
        // $ex_api = explode("=",$array[0]);
        // $ex_api = $ex_api[1];
        // $ex_api = str_replace('"', "", $ex_api);
        // $ex_secret = explode("=",$array[1]);
        // $ex_secret = $ex_secret[1];
        // $ex_secret = str_replace('"', "", $ex_secret);
        // $ex_url = explode("callback=",$array[2]);
        // $ex_url = $ex_url[1];
        // $ex_url = str_replace('"', "", $ex_url);
        //Vmerror('Blockonomics Error - '); Working
        $app = JFactory::getApplication();
        $app->enqueueMessage('Blockonomics Message');
        JError::raiseNotice( 100, 'Blockonomics Notice' );
        JError::raiseWarning( 100, 'Blockonomics Warning' );
        //JError::raiseError( 4711, 'Blockonomics Error' );
        $post_url = JROUTE::_(JURI::root() . 'plugins/vmpayment/blockonomics/test-setup.php');
        $base_url = JROUTE::_(JURI::root());
    		return '<button class="btn btn-success" type="button" id="'.$this->id.'" name="'.$this->name.'">' . 'Test Setup'. '</button>
    		'.'
    			<script>
                jQuery(".alert-error").hide();
                jQuery(".alert-info").hide();
                jQuery(".alert-message").each(function( ) {
                    jQuery(this).hide();
                    
                    if(jQuery(this).html() == "Payment Method successfully saved"){
                        jQuery(this).addClass("blockonomics-pm-saved");
                        jQuery(this).html("Payment Method successfully saved. Visit the Configuration tab to enter your API key.");
                        jQuery(this).show();
                    }
                    if(jQuery(this).html() == "Blockonomics Message"){
                        jQuery(this).addClass("blockonomics-message");
                    }
                    if(jQuery(this).html() == "Blockonomics Notice"){
                        jQuery(this).addClass("blockonomics-notice");;
                    }
                    if(jQuery(this).html() == "Blockonomics Warning"){
                        jQuery(this).addClass("blockonomics-warning");
                    }
                    if(jQuery(this).html() == "Blockonomics Error"){
                        jQuery(this).addClass("blockonomics-error");
                    }
                });

                jQuery("#params_title").click(function() {
                    jQuery("#params_title").prop("disabled", true);
                    jQuery(".alert-success").hide();
                    jQuery(".alert-error").hide();
                    jQuery(".blockonomics-pm-saved").hide();
                    jQuery(".blockonomics-notice").html( "Connecting to Blockonomics" );
                    jQuery(".blockonomics-notice").show();
                    jQuery(".alert-info").show();
                    var send_secret = jQuery("#params_merchant_secret").val();
                    var send_api = jQuery("#params_merchant_apikey").val();
                    var send_url = jQuery("#params_merchant_callback").val();
                    if(jQuery("#params_alt_payments").val()){
                        var alt_payments = jQuery("#params_alt_payments").val();
                    }
                    var timer = jQuery("#params_timer").val();
                    jQuery("#blocko_test_return").show();
                    jQuery.ajax({
                        type: "POST",
                        url: "'. $post_url . '",
                        data: {secret:send_secret, api:send_api, url:send_url, base_url:"'. $base_url . '", alt_payments:alt_payments, timer:timer},
                        success: function(data) {
                            console.log(data);
                            if(data.match("^Congrats")){
                                jQuery(".alert-info").hide();
                                jQuery(".blockonomics-message").html(data);
                                jQuery(".alert-success").show();
                                jQuery(".alert-error").hide();
                                jQuery(".blockonomics-message").show();
                                jQuery(".blockonomics-warning").hide();
                                jQuery("#params_title").prop("disabled", false);
                            }
                            else{
                                jQuery(".alert-info").hide();
                                jQuery(".blockonomics-warning").html(data);
                                jQuery(".alert-success").hide();
                                jQuery(".alert-error").show();
                                jQuery(".blockonomics-warning").show();
                                jQuery(".blockonomics-message").hide();
                                jQuery("#params_title").prop("disabled", false);
                            }
                        }
                    });
                });
    			</script>
    		';
	}
}

class plgVmPaymentBlockonomics extends vmPSPlugin
{

    /**
     * @param $subject
     * @param $config
     */
    function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->_loggable   = true;
        $this->tableFields = array_keys($this->getTableSQLFields());
        $this->_tablepkey = 'id';
        $this->_tableId = 'id';
        $merchant_secret = $this->generate_secret();
        $merchant_callback = JROUTE::_(JURI::root() . 'index.php?option=com_virtuemart&view=pluginresponse&task=pluginresponsereceived&secret='.$merchant_secret);
        $varsToPush        = array(
            'merchant_apikey'   => array('', 'char'),
            'merchant_secret'   => array($merchant_secret, 'char'),
            'merchant_callback' => array($merchant_callback, 'char'),
            'alt_payments' 		=> array(0, 'int'),
            'timer'				=> array(10, 'int')

        );
        $this->setConfigParameterable($this->_configTableFieldName, $varsToPush);
    }

    function generate_secret()
    {
        $callback_secret = sha1(openssl_random_pseudo_bytes(20));
        return $callback_secret;
    }

    /**
     * Create the table for this plugin if it does not yet exist.
     *
     * @return
     */
    public function getVmPluginCreateTableSQL()
    {
        return $this->createTableSQL('Payment Blockonomics Table');
    }

    /**
     * Fields to create the payment table
     *
     * @return array
     */
    function getTableSQLFields()
    {
        $SQLfields = array(
            'id'                          => 'int(1) UNSIGNED NOT NULL AUTO_INCREMENT',
            'virtuemart_order_id'         => 'int(1) UNSIGNED',
            'order_number'                => 'char(64)',
            'virtuemart_paymentmethod_id' => 'mediumint(1) UNSIGNED',
            'payment_name'                => 'varchar(255) NOT NULL DEFAULT \'\' ',
            'payment_order_total'         => 'decimal(15,5) NOT NULL DEFAULT \'0.00000\'',
            'payment_currency'            => 'char(3)',
            'logo'			              => 'varchar(255) NOT NULL DEFAULT \'\' ',
            'addr'                        => 'varchar(255) NOT NULL DEFAULT \'\' ',
            'txid'                        => 'varchar(255) NOT NULL DEFAULT \'\' ',
            'status'                      => 'int(11) NOT NULL DEFAULT \'0\'',
            'timestamp'                   => 'int(11) NOT NULL DEFAULT \'0\'',
            'bits'                        => 'int(11) NOT NULL DEFAULT \'0\'',
            'bits_payed'                  => 'int(11) NOT NULL DEFAULT \'0\''
        );

        return $SQLfields;
    }


    /**
     * Display stored payment data for an order
     *
     * @param $virtuemart_order_id
     * @param $virtuemart_payment_id
     *
     * @return
     */
    function plgVmOnShowOrderBEPayment($virtuemart_order_id, $virtuemart_payment_id)
    {
        if (!$this->selectedThisByMethodId($virtuemart_payment_id)) 
        {
            return null; // Another method was selected, do nothing
        }
    
        $db = JFactory::getDBO();
        $q = 'SELECT * FROM `' . $this->_tablename . '` '
            . 'WHERE `virtuemart_order_id` = ' . $virtuemart_order_id;
        $db->setQuery($q);
        if (!($paymentTable = $db->loadObject())) 
        {
            return '';
        }
        $this->getPaymentCurrency($paymentTable);
        $q = 'SELECT `currency_code_3` FROM `#__virtuemart_currencies` WHERE `virtuemart_currency_id`="' . $paymentTable->payment_currency . '" ';
        $db = &JFactory::getDBO();
        $db->setQuery($q);
        $currency_code_3 = $db->loadResult();
        $html = '<table class="adminlist">' . "\n";
        $html .=$this->getHtmlHeaderBE();
        $html .= '<tr><td width="50%">Payment Name:</td><td>'.$paymentTable->payment_name.'</td></tr>';
        $html .= '<tr><td width="50%">Transaction ID:</td><td>'.$paymentTable->txid.'</td></tr>';
        $html .= '<tr><td width="50%" valign=top>Status:</td><td>'.$paymentTable->status.'</td></tr>';
        $html .= '</table>' . "\n";
        return $html;
    }

    /**
     * @param VirtueMartCart $cart
     * @param                $method
     * @param array          $cart_prices
     *
     * @return
     */
    function getCosts(VirtueMartCart $cart, $method, $cart_prices)
    {
        if (preg_match('/%$/', $method->cost_percent_total))
        {
            $cost_percent_total = substr($method->cost_percent_total, 0, -1);
        }
        else
        {
            $cost_percent_total = $method->cost_percent_total;
        }

        return ($method->cost_per_transaction + ($cart_prices['salesPrice'] * $cost_percent_total * 0.01));
    }

    /**
     * Check if the payment conditions are fulfilled for this payment method
     *
     * @param $cart
     * @param $method
     * @param $cart_prices
     *
     * @return boolean
     */
    protected function checkConditions($cart, $method, $cart_prices)
    {
        $this->convert($method);
        //         $params = new JParameter($payment->payment_params);
        $address = (($cart->ST == 0) ? $cart->BT : $cart->ST);

        $amount      = $cart_prices['salesPrice'];
        $amount_cond = ($amount >= $method->min_amount AND $amount <= $method->max_amount
            OR
            ($method->min_amount <= $amount AND ($method->max_amount == 0)));
        if (!$amount_cond)
        {
            return false;
        }
        $countries = array();
        if (!empty($method->countries))
        {
            if (!is_array($method->countries))
            {
                $countries[0] = $method->countries;
            }
            else
            {
                $countries = $method->countries;
            }
        }

        // probably did not gave his address
        if (!is_array($address))
        {
            $address                          = array();
            $address['virtuemart_country_id'] = 0;
        }

        if (!isset($address['virtuemart_country_id']))
        {
            $address['virtuemart_country_id'] = 0;
        }
        if (count($countries) == 0 || in_array($address['virtuemart_country_id'], $countries) || count($countries) == 0)
        {
            return true;
        }

        return false;
    }

    /**
     * @param $method
     */
    function convert($method)
    {
        $method->min_amount = (float)$method->min_amount;
        $method->max_amount = (float)$method->max_amount;
    }

    /*
     * We must reimplement this triggers for joomla 1.7
     */

    /**
     * Create the table for this plugin if it does not yet exist.
     * This functions checks if the called plugin is active one.
     * When yes it is calling the standard method to create the tables
     *
     * @param $jplugin_id
     *
     * @return
     */
    function plgVmOnStoreInstallPaymentPluginTable($jplugin_id)
    {
        return $this->onStoreInstallPluginTable($jplugin_id);
    }

    /*
     * plgVmonSelectedCalculatePricePayment
     * Calculate the price (value, tax_id) of the selected method
     * It is called by the calculator
     * This function does NOT to be reimplemented. If not reimplemented, then the default values from this function are taken.
     *
     * @param VirtueMartCart $cart
     * @param array          $cart_prices
     * @param                $cart_prices_name
     *
     * @return
     */

    public function plgVmonSelectedCalculatePricePayment(VirtueMartCart $cart, array &$cart_prices, &$cart_prices_name)
    {
        return $this->onSelectedCalculatePrice($cart, $cart_prices, $cart_prices_name);
    }

    /**
     * @param $virtuemart_paymentmethod_id
     * @param $paymentCurrencyId
     *
     * @return
     */
    function plgVmgetPaymentCurrency($virtuemart_paymentmethod_id, &$paymentCurrencyId)
    {
        if (!($method = $this->getVmPluginMethod($virtuemart_paymentmethod_id)))
        {
            return NULL; // Another method was selected, do nothing
        }
        if (!$this->selectedThisElement($method->payment_element))
        {
            return false;
        }
        $this->getPaymentCurrency($method);

        $paymentCurrencyId = $method->payment_currency;
        return;
    }

    /**
     * plgVmOnCheckAutomaticSelectedPayment
     * Checks how many plugins are available. If only one, the user will not have the choice. Enter edit_xxx page
     * The plugin must check first if it is the correct type
     *
     * @param VirtueMartCart $cart
     * @param array          $cart_prices
     * @param                $paymentCounter
     *
     * @return
     */
    function plgVmOnCheckAutomaticSelectedPayment(VirtueMartCart $cart, array $cart_prices = array(), &$paymentCounter)
    {
        return $this->onCheckAutomaticSelected($cart, $cart_prices, $paymentCounter);
    }

    /**
     * This method is fired when showing the order details in the frontend.
     * It displays the method-specific data.
     *
     * @param $virtuemart_order_id
     * @param $virtuamart_paymentmethod_id
     * @param $payment_name
     */
    public function plgVmOnShowOrderFEPayment($virtuemart_order_id, $virtuemart_paymentmethod_id, &$payment_name)
    {
        $this->onShowOrderFE($virtuemart_order_id, $virtuemart_paymentmethod_id, $payment_name);
    }

    /**
     * This method is fired when showing when priting an Order
     * It displays the the payment method-specific data.
     *
     * @param integer $_virtuemart_order_id The order ID
     * @param integer $method_id  method used for this order
     *
     * @return mixed Null when for payment methods that were not selected, text (HTML) otherwise
     */
    function plgVmonShowOrderPrintPayment($order_number, $method_id)
    {
        return $this->onShowOrderPrint($order_number, $method_id);
    }

    /**
     * @param $name
     * @param $id
     * @param $data
     *
     * @return
     */
    function plgVmDeclarePluginParamsPayment($name, $id, &$data)
    {
        return $this->declarePluginParams('payment', $name, $id, $data);
    }
    function plgVmDeclarePluginParamsPaymentVM3( &$data) {
        return $this->declarePluginParams('payment', $data);
    }

    /**
     * @param $name
     * @param $id
     * @param $table
     *
     * @return
     */
    function plgVmSetOnTablePluginParamsPayment($name, $id, &$table)
    {
        return $this->setOnTablePluginParams($name, $id, $table);
    }


    /**
     * This event is fired by Offline Payment. It can be used to validate the payment data as entered by the user.
     *
     * @return
     */
    function plgVmOnPaymentNotification ()
    {
        if (!class_exists('VirtueMartCart')) {
            require(JPATH_VM_SITE . DS . 'helpers' . DS . 'cart.php');
        }
        if (!class_exists('shopFunctionsF')){
            require(JPATH_VM_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
        }

        if (!class_exists('VirtueMartModelOrders')){
            require( JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php' );
        }

        $cart = VirtueMartCart::getCart();
        $cart->emptyCart();
        $html = '<h2>Payment Completed</h2>';
        return $html;
        // Call Blockonomics
        // if ($method->network == "test")
        // {
        //     $network_uri = 'test.blockonomics.co';
        // }
        // else
        // {
        //     $network_uri = 'blockonomics.co';
        // }
        // $curl   = curl_init('https://' . $network_uri . '/api/invoice/'.$blockonomics_data['id']);
        // $length = 0;

        // $uname  = base64_encode($method->merchant_apikey);
        // $header = array(
        //     'Content-Type: application/json',
        //     "Content-Length: $length",
        //     "Authorization: Basic $uname",
        //     'X-Blockonomics-Plugin-Info: virtuemart073015',
        // );

        // curl_setopt($curl, CURLOPT_PORT, 443);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        // curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1); // verify certificate
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // check existence of CN and verify that it matches hostname
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        // curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);

        // $responseString = curl_exec($curl);

        // if($responseString == false)
        // {
        //     return NULL;
        // }
        // else
        // {
        //     $blockonomics_data = json_decode($responseString, true);
        // }
        // curl_close($curl);

        // $this->logInfo ('IPN ' . implode (' / ', $blockonomics_data), 'message');

        // if ($blockonomics_data['status'] != 'confirmed' and $blockonomics_data['status'] != 'complete')
        // {
        //     return NULL; // not the status we're looking for
        // }

        // $order['order_status'] = 'C'; // move to admin method option?
        // $modelOrder->updateStatusForOneOrder ($virtuemart_order_id, $order, TRUE);
    }

    /**
     * @param $html
     *
     * @return bool|null|string
     */
    function plgVmOnPaymentResponseReceived (&$html)
    {

        if (!class_exists('VirtueMartCart')) {
            require(JPATH_VM_SITE . DS . 'helpers' . DS . 'cart.php');
        }
        if (!class_exists('shopFunctionsF')){
            require(JPATH_VM_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
        }

        if (!class_exists('VirtueMartModelOrders')){
            require( JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php' );
        }

        $jinput = JFactory::getApplication()->input;

        $secret         = $jinput->get('secret');
        $tranID         = $jinput->get('txid');
        $status         = $jinput->get('status');
        $addr           = $jinput->get('addr');
        $amount         = $jinput->get('value');

        $addr = $jinput->get('addr');
        //Fetch VM Order ID Values Payment Name - Order Number - Amount - Currency
        $db = JFactory::getDBO();
        $query = 'SELECT * FROM ' . $this->_tablename . " WHERE  `addr`= '" . $addr ."'";
        $db->setQuery($query);
        $result = $db->loadAssoc();
        $virtuemart_order_id = $result['virtuemart_order_id'];
        $order_number = $result['order_number'];
        $currency = $result['payment_currency'];
        $amount_currency = $result['payment_order_total'];
        if ($virtuemart_order_id) {
            $modelOrder = new VirtueMartModelOrders();
            $orderitems = $modelOrder->getOrder($virtuemart_order_id);
	        if (!$orderitems)
	        {
	            bplog('order could not be loaded '.$virtuemart_order_id);
	            return NULL;
	        }
	        $method = $this->getVmPluginMethod($orderitems['details']['BT']->virtuemart_paymentmethod_id);

            $callback_secret = $method->merchant_secret;
        	if ($callback_secret  && $callback_secret == $secret) {
                $payment_name = "Blockonomics";
                $nb_history = count($orderitems['history']);
                vmdebug('history', $orderitems);
                $status = intval($status);
                $existing_status = $result['status'];
                $timestamp = $result['timestamp'];
                $time_selected = $method->merchant_secret;
                $time_period = $time_selected * 60;   // ******** TEST  *************
                if ($nb_history == 1) {
                    if ($status == 0 && time() > $timestamp + $time_period) {
                        $minutes = (time() - $timestamp)/60;
                        //Warning: Payment arrived after $minutes minutes
                        $order['order_status'] = 'P';
                        $order['virtuemart_order_id'] = $virtuemart_order_id;
                        $order['customer_notified'] = 0;
                        $order['comments'] = 'Warning: Payment arrived after '.round($minutes, 2).' minutes. Received BTC may not match current bitcoin price';
                    }
                    elseif ($status == 2) {
                        if ($result['bits'] > $amount) {
                            $status = -2;
                            //Paid BTC amount less than expected
                            $order['order_status'] = 'X';
                            $order['virtuemart_order_id'] = $virtuemart_order_id;
                            $order['customer_notified'] = 0;
                            $order['comments'] = 'Paid BTC amount less than expected';
                        }
                        elseif($result['bits'] < $amount){
                                //Overpayment of BTC amount
                                $order['order_status'] = 'C';
                                $order['virtuemart_order_id'] = $virtuemart_order_id;
                                $order['customer_notified'] = 0;
                                $order['comments'] = 'Overpayment of BTC amount';
                        }
                        else {
                            //Payment completed
                            $order['order_status'] = 'C';
                            $order['virtuemart_order_id'] = $virtuemart_order_id;
                            $order['customer_notified'] = 0;
                            $order['comments'] = 'Payment completed';
                        }

                    }
                    $modelOrder->updateStatusForOneOrder($virtuemart_order_id, $order, true);
                }
                //if ($existing_status == -1) {
                    //update_post_meta($wc_order->id, 'blockonomics_txid', $orderVM['txid']);
                    //update_post_meta($wc_order->id, 'expected_btc_amount', $orderVM['satoshi']/1.0e8);
                //}
                //Store Transaction in DB
                $this->debugLog("before tranID store", "plgVmConfirmedOrder", 'debug');
                $db =& JFactory::getDBO();
                $query = 'UPDATE ' . $this->_tablename . " SET `txid`='".$tranID."', `status`='" . $status . "', `bits_payed`='" . $amount . "' WHERE  `addr`='" . $addr ."'";
                $db->setQuery( $query );
                $db->query();
            }else {
            	echo "Secrets do not match" . "<br>";
           		// echo "Secret DB " . $callback_secret;
           		// echo "Secret Input " . $secret;
                return null;
            }
            $cart = VirtueMartCart::getCart();
            $cart->emptyCart();
            $html = '<table width="50%">' . "\n";
            $html = '<tr><td width="25%">Payment Name</td>
                     <td width="25%">'.$payment_name.'</td>
                     </tr>
                     <tr><td>Order Number</td>
                     <td>'.$order_number .'</td>
                     </tr>
                     <tr><td>Amount</td>
                     <td>'.$currency.': '.$amount_currency.'</td>
                     </tr>
                     <tr><td>Amount</td>
                     <td>BTC: '.$amount.'</td>
                     </tr>';    
            $html .= '</table>' . "\n";
            return $html;
        }else {
        	echo "No VM ID";
            return null;
        }

    }

    /**
     * This shows the plugin for choosing in the payment list of the checkout process.
     *
     * @param VirtueMartCart $cart
     * @param integer        $selected
     * @param                $htmlIn
     *
     * @return
     */
    function plgVmDisplayListFEPayment (VirtueMartCart $cart, $selected = 0, &$htmlIn)
    {
        $session = JFactory::getSession ();
        $errors  = $session->get ('errorMessages', 0, 'vm');

        if($errors != "")
        {
            $errors = unserialize($errors);
            $session->set ('errorMessages', "", 'vm');
        }
        else
        {
            $errors = array();
        }

        return $this->displayListFE ($cart, $selected, $htmlIn);
    }

    /**
     * getGMTTimeStamp:
     *
     * this function creates a timestamp formatted as per requirement in the
     * documentation
     *
     * @return string The formatted timestamp
     */
    public function getGMTTimeStamp()
    {
        /* Format: YYYYDDMMHHNNSSKKK000sOOO
            YYYY is a 4-digit year
            DD is a 2-digit zero-padded day of month
            MM is a 2-digit zero-padded month of year (January = 01)
            HH is a 2-digit zero-padded hour of day in 24-hour clock format (midnight =0)
            NN is a 2-digit zero-padded minute of hour
            SS is a 2-digit zero-padded second of minute
            KKK is a 3-digit zero-padded millisecond of second
            000 is a Static 0 characters, as Blockonomics does not store nanoseconds
            sOOO is a Time zone offset, where s is + or -, and OOO = minutes, from GMT.
         */
        $tz_minutes = date('Z') / 60;

        if ($tz_minutes >= 0)
        {
            $tz_minutes = '+' . sprintf("%03d",$tz_minutes); //Zero padding in-case $tz_minutes is 0
        }

        $stamp = date('YdmHis000000') . $tz_minutes; //In some locales, in some situations (i.e. Magento 1.4.0.1) some digits are missing. Added 5 zeroes and truncating to the required length. Terrible terrible hack.

        return $stamp;
    }

    /**
     * @param       $data
     * @param array $outputArray
     *
     * @return
     */
    private function makeXMLTree ($data, &$outputArray = array())
    {
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        $result = xml_parse_into_struct($parser, $data, $values, $tags);
        xml_parser_free($parser);
        if ($result == 0)
        {
            return false;
        }

        $hash_stack = array();
        foreach ($values as $key => $val)
        {
            switch ($val['type'])
            {
            case 'open':
                array_push($hash_stack, $val['tag']);
                break;
            case 'close':
                array_pop($hash_stack);
                break;
            case 'complete':
                array_push($hash_stack, $val['tag']);
                // ATTN, I really hope this is sanitized
                eval("\$outputArray['" . implode($hash_stack, "']['") . "'] = \"{$val['value']}\";");
                array_pop($hash_stack);
                break;
            }
        }

        return true;
    }

    /**
     * @param $cart
     * @param $order
     *
     * @return
     */
    function plgVmConfirmedOrder($cart, $order)
    {
       if (!($method = $this->getVmPluginMethod($order['details']['BT']->virtuemart_paymentmethod_id))) {
            return null; // Another method was selected, do nothing
        }
        if (!$this->selectedThisElement($method->payment_element)) {
            return false;
        }
        $session        = JFactory::getSession();
        $return_context = $session->getId();
        
        if (!class_exists('VirtueMartModelOrders')) {
            require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php');
        }
        if (!class_exists('VirtueMartModelCurrency')) {
            require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'currency.php');
        }
        
        $new_status = '';
        
        $usrBT   = $order['details']['BT'];
        $address = ((isset($order['details']['ST'])) ? $order['details']['ST'] : $order['details']['BT']);
        
        $vendorModel = VmModel::getModel('Vendor');
        $vendorModel->setId(1);
        $vendor = $vendorModel->getVendor();
        $this->getPaymentCurrency($method);
        $q  = 'SELECT `currency_code_3` FROM `#__virtuemart_currencies` WHERE `virtuemart_currency_id`="' . $method->payment_currency . '" ';
        $db = JFactory::getDBO();
        $db->setQuery($q);
        $currency_code_3 = $db->loadResult();
        
        $paymentCurrency        = CurrencyDisplay::getInstance($method->payment_currency);
        $totalInPaymentCurrency = str_replace(',', '', number_format($paymentCurrency->convertCurrencyTo($method->payment_currency, $order['details']['BT']->order_total, false), '2', '.', ''));
        //$cd                     = CurrencyDisplay::getInstance($cart->pricesCurrency);
        
        //to get RM
        // $countryquery = 'SELECT `country_2_code` FROM `#__virtuemart_countries` WHERE `virtuemart_country_id`="' . $address->virtuemart_country_id . '" ';
        // $dbs          = JFactory::getDBO();
        // $dbs->setQuery($countryquery);
        // $country = $dbs->loadResult();
        
        // //vcode
        // $vcode = md5($totalInPaymentCurrency . $method->blockonomics_merchantid . $order['details']['BT']->order_number . $method->blockonomics_verifykey);
        
        // //mart name
        // $martquery = 'SELECT `vendor_store_name` FROM `#__virtuemart_vendors_en_gb` WHERE `virtuemart_vendor_id`="' . $method->virtuemart_vendor_id . '" ';
        // $dbs       = JFactory::getDBO();
        // $dbs->setQuery($martquery);
        // $martname = $dbs->loadResult();
        
        //Call Blockonomics
        $api_key    =  $method->merchant_apikey;
        $alt_payments    =  $method->alt_payments;
        $timer    =  $method->timer;
        $blockonomics_callback_secret = $method->merchant_secret;
        $blockonomics = new Blockonomics;
        $responseObj = $blockonomics->new_address($api_key, $blockonomics_callback_secret); // Development use: ($api_key, $blockonomics_callback_secret, true)
        $price = $blockonomics->get_price($paymentCurrency->_vendorCurrency_code_3);
        $satoshi = intval(1.0e8*$totalInPaymentCurrency/$price);
        $this->logInfo ('invoice ' . implode (' / ', $responseObj), 'message');
        if($responseObj->response_code == 'HTTP/1.1 200 OK') {
                            
            $address = $responseObj->address;
            $price = $totalInPaymentCurrency;
            //data need to send to blockonomics
            $post_variables = Array(
                'address'            => $address,
                'value'              => $totalInPaymentCurrency,
                'satoshi'            => $satoshi,
                'currency'           => $paymentCurrency->_vendorCurrency_code_3,
                'order_id'           => $order['details']['BT']->order_number,
                'status'             => -1,
                'timestamp'          => time(),
                'alt_payments'		 => $alt_payments,
                'timer'				 => $timer,
                'txid'               => '',
                'returnurl' 		 => JROUTE::_(JURI::root() . 'index.php?option=com_virtuemart&view=pluginresponse&task=pluginresponsereceived&secret=' . $blockonomics_callback_secret)
            );

            $html = $this->renderByLayout('post_payment', $post_variables);
                //$modelOrder = VmModel::getModel ('orders');
                //     2 = don't delete the cart, don't send email and don't redirect

                // Update to DB **** Make sure key is 'addr' not 'id'
                // Prepare data that should be stored in the database

                $dbValues['order_number']                = $order['details']['BT']->order_number;
                $dbValues['payment_name']                = "Blockonomics";
                $dbValues['virtuemart_paymentmethod_id'] = $cart->virtuemart_paymentmethod_id;
                $dbValues['payment_currency']            = $paymentCurrency->_vendorCurrency_code_3;
                $dbValues['payment_order_total']         = $totalInPaymentCurrency;
                $dbValues['addr']                        = $address;
                $dbValues['status']                      = -1;
                $dbValues['timestamp']                   = $post_variables['timestamp'];
                $dbValues['bits']                        = $satoshi;
                $this->debugLog("before addr store", "plgVmConfirmedOrder", 'debug');
                $this->storePSPluginInternalData($dbValues,'virtuemart_order_id', true);
                
                return $this->processConfirmedOrderPaymentResponse(2, $cart, $order, $html, $new_status);

            //print_r($responseObj);
            //$cart->emptyCart ();
            //vRequest::setVar ('html', $html);
            //return TRUE;
             //header('Location: ' . $options['redirectURL']);
             //exit;
        }else
        {
             $html = vmText::_ ('Blockonomics could not process your payment.' . '<br />'  . 'Error: ' . $responseObj->response_code);
             $returnValue = 0;
             return $this->processConfirmedOrderPaymentResponse ($returnValue, $cart, $order, $html, '', '');
        }
    }

    /**
     * @param $virtualmart_order_id
     * @param $html
     */
    function _handlePaymentCancel ($virtuemart_order_id, $html)
    {
        if (!class_exists ('VirtueMartModelOrders'))
        {
            require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'orders.php');
        }
        $modelOrder = VmModel::getModel ('orders');
        $modelOrder->remove (array('virtuemart_order_id' => $virtuemart_order_id));
        // error while processing the payment
        $mainframe = JFactory::getApplication ();
        $mainframe->redirect (JRoute::_ ('index.php?option=com_virtuemart&view=cart&task=editpayment'), $html);
    }

    /**
     * takes a string and returns an array of characters
     *
     * @param string $input string of characters
     * @return array
     */
    function toCharArray($input)
    {
        $len = strlen ( $input );
        for($j = 0; $j < $len; $j ++)
        {
            $char [$j] = substr ( $input, $j, 1 );
        }
        return ($char);
    }
    /**
     * @param $virtuemart_paymentmethod_id
     * @param $paymentCurrencyId
     * @return bool|null
     */
    function plgVmgetEmailCurrency($virtuemart_paymentmethod_id, $virtuemart_order_id, &$emailCurrencyId) {
        if (!($method = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
            return NULL; // Another method was selected, do nothing
        }
        if (!$this->selectedThisElement($method->payment_element)) {
            return FALSE;
        }
        if (!($payments = $this->getDatasByOrderId($virtuemart_order_id))) {
            // JError::raiseWarning(500, $db->getErrorMsg());
            return '';
        }
        if (empty($payments[0]->email_currency)) {
            $vendorId = 1; //VirtueMartModelVendor::getLoggedVendor();
            $db = JFactory::getDBO();
            $q = 'SELECT   `vendor_currency` FROM `#__virtuemart_vendors` WHERE `virtuemart_vendor_id`=' . $vendorId;
            $db->setQuery($q);
            $emailCurrencyId = $db->loadResult();
        } else {
            $emailCurrencyId = $payments[0]->email_currency;
        }
    }
}

defined('_JEXEC') or die('Restricted access');

/*
 * This class is used by VirtueMart Payment  Plugins
 * which uses JParameter
 * So It should be an extension of JElement
 * Those plugins cannot be configured througth the Plugin Manager anyway.
 */
if (!class_exists( 'VmConfig' ))
{
    require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');
}
if (!class_exists('ShopFunctions'))
{
    require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'shopfunctions.php');
}

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
