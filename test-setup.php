<?php
error_reporting(0);
$existing_api = $_POST['api'];
$existing_secret = $_POST['secret'];
$existing_url = $_POST['url'];
$base_url = $_POST['base_url'];
if( isset($_POST['alt_payments']) )
{
     $alt_payments = $_POST['alt_payments'];
}else{
	 $alt_payments = 0;
}
$timer = $_POST['timer'];
class Blockonomics
{
    const BASE_URL = 'https://www.blockonomics.co';
    const NEW_ADDRESS_URL = 'https://www.blockonomics.co/api/new_address';
    const PRICE_URL = 'https://www.blockonomics.co/api/price';
    const ADDRESS_URL = 'https://www.blockonomics.co/api/address?&no_balance=true&only_xpub=true&get_callback=true';
    const SET_CALLBACK_URL = 'https://www.blockonomics.co/api/update_callback';
    public function __construct()
    {
    }
    public function new_address($api_key, $secret, $reset=false)
    {
        $options = array(
            'http' => array(
                'header'  => 'Authorization: Bearer ' . $api_key. "\r\n" ."Content-Type: application/x-www-form-urlencoded\r\n",
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
                'header'  => 'Authorization: Bearer ' . $api_key. "\r\n" ."Content-Type: application/x-www-form-urlencoded\r\n",
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
                'header'  => 'Authorization: Bearer ' . $api_key. "\r\n" ."Content-Type: application/x-www-form-urlencoded\r\n",
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

if($existing_api != "" || $existing_api != 0){
	function prepareForSave($db_api,$db_secret,$db_url,$alt_payments,$db_timer){
		return 'merchant_apikey="'.$db_api.'"|merchant_secret="'.$db_secret.'"|merchant_callback="'.$db_url.'"|alt_payments='.$alt_payments.'|timer="'.$db_timer.'"|';
	}

    define( '_JEXEC', 1 );
    define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../..' ));
    require_once ( JPATH_BASE .'/includes/defines.php' );
    require_once ( JPATH_BASE .'/includes/framework.php' );
    require_once ( JPATH_BASE .'/configuration.php' );

    $mainframe = JFactory::getApplication('site');

    // Get a db connection.
    $db = JFactory::getDbo();

    // Create a new query object.
    $query = $db->getQuery(true);
    $pmIDquery = 'SELECT `virtuemart_paymentmethod_id` FROM `#__virtuemart_paymentmethods` WHERE `payment_element`="' . "blockonomics" . '" ';
    $dbs       = JFactory::getDBO();
    $dbs->setQuery($pmIDquery);
    $pmIDcontent = $dbs->loadResult();
	
	// Create an object for the record we are going to update.
	$object = new stdClass();

	// Must be a valid primary key value.
	$object->virtuemart_paymentmethod_id = $pmIDcontent;
	$object->payment_params = prepareForSave($existing_api,$existing_secret,$existing_url,$alt_payments,$timer);

	// Update their details in the users table using id as the primary key.
	$result = JFactory::getDbo()->updateObject('#__virtuemart_paymentmethods', $object, 'virtuemart_paymentmethod_id');

	// $payment_params = prepareForSave($existing_api,$existing_secret,$existing_url,$alt_payments,$timer);
	// // echo $payment_params;
 //    $q = 'SELECT   `payment_params` FROM `#__virtuemart_paymentmethods` WHERE `payment_element`=' . 'blockonomics';
 //    $db->setQuery($q);
 //    $payment_params = $db->loadResult();
	// // var_dump($result);
	//echo "Settings saved<br>";
	function update_callback_url($callback_url, $xPub, $blockonomics,$existing_api)
	{
	    $blockonomics->update_callback(
	        $existing_api,
	        $callback_url,
	        $xPub
	    );
	}

	/**
	 * Check the status of callback urls
	 * If no xPubs set, return
	 * If one xPub is set without callback url, set the url
	 * If more than one xPubs are set, give instructions on integrating to multiple sites
	 * @return Strin Count of found xPubs
	 */
	function check_callback_urls($existing_api, $existing_secret, $existing_url, $base_url)
	{
	    $blockonomics = new Blockonomics;
	    //$virtuemart_order_id = $result
	    $responseObj = $blockonomics->get_xpubs($existing_api);

	    // No xPubs set
	    if (count($responseObj) == 0)
	    {
	        return "0";
	    }

	    // One xPub set
	    if (count($responseObj) == 1)
	    {
	    	// Existing Callback
	        $callback_secret = $existing_secret;
	        $callback_url = $existing_url;
	        // No Callback URL set, set one
	        if(!$responseObj[0]->callback || $responseObj[0]->callback == null)
	        {
	            update_callback_url($callback_url, $responseObj[0]->address, $blockonomics, $existing_api);
	            return "1";
	        }
	        // One xPub with one Callback URL
	        else
	        {
	            if($responseObj[0]->callback == $callback_url)
	            {
	                return "1";
	            }

	            if($responseObj[0]->callback == $callback_url)
	            {
	                return "1";
	            }
	            // Check if only secret differs
	            if(strpos($responseObj[0]->callback, $base_url) !== false)  // $responseObj[0]->callback is in $responseCallback
	            {
	            	//Looks like the user regenrated callback by mistake
	            	//Just force Update_callback on server
	            	update_callback_url($callback_url, $responseObj[0]->address, $blockonomics, $existing_api);
	                return "1";
	            }else{ // $responseObj[0]->callback not in $responseCallback
	            	return "2";
	            }
	        }
	    }

	    if (count($responseObj) > 1)
	    {
	        $callback_secret = $existing_secret;
	        $callback_url = $existing_url;

	        // Check if callback url is set
	        foreach ($responseObj as $resObj) {
	            if($resObj->callback == $callback_url)
	                {
	                    return "1";
	                }
	        }
	        return "2";
	    }
	}

	function testSetup($existing_api, $existing_secret)
	{
	    $blockonomics = new Blockonomics;
	    $responseObj = $blockonomics->new_address($existing_api, $existing_secret, true);
	    if(!ini_get('allow_url_fopen')) {
	        $error_str = 'allow_url_fopen is not enabled, please enable this in php.ini';

	    }  elseif(!isset($responseObj->response_code)) {
	        $error_str = 'Your web host is blocking outgoing HTTPS connections. Blockonomics requires an outgoing HTTPS POST (port 443) to generate a new address. Check with your web hosting provider to allow this.';

	    } else {

	        switch ($responseObj->response_code) {

	            case 'HTTP/1.1 200 OK':
	                break;

	            case 'HTTP/1.1 401 Unauthorized': {
	                $error_str = 'API Key is incorrect. Make sure that your API key is set. ';
	                break;
	            }

	            case 'HTTP/1.1 500 Internal Server Error': {

	                if(isset($responseObj->message)) {

	                    $error_code = $responseObj->message;

	                    switch ($error_code) {
	                        case "Could not find matching xpub":
	                            $error_str = 'There is a problem in the Callback URL. Make sure that you have set your Callback URL from the admin Blockonomics module configuration to your Merchants > Settings.';
	                            break;
	                        case "This require you to add an xpub in your wallet watcher":
	                            $error_str = 'There is a problem in the XPUB. Make sure that the you have added an address to Wallet Watcher > Address Watcher. If you have added an address make sure that it is an XPUB address and not a Bitcoin address.';
	                            break;
	                        default:
	                            $error_str = $responseObj->message;
	                    }
	                    break;
	                } else {
	                    $error_str = $responseObj->response_code;
	                    break;
	                }
	            }

	            default:
	                $error_str = $responseObj->response_code;
	                break;

	        }
	    }

	    if(isset($error_str)) {
	        return $error_str;
	    } 

	    return false;
	}

	$urls_count = check_callback_urls($existing_api, $existing_secret, $existing_url, $base_url);

	if($urls_count == '2')
	{
	    $message = "Seems that you have set multiple xPubs or you already have a Callback URL set. <a href='https://blockonomics.freshdesk.com/support/solutions/articles/33000209399-merchants-integrating-multiple-websites' target='_blank'>Here is a guide</a> to setup multiple websites.";
	    echo $message;
	}else {

	$setup_errors = testSetup($existing_api, $existing_secret);

	if($setup_errors)
	{
	    $message = $setup_errors . 'For more information, please consult <a href="https://blockonomics.freshdesk.com/support/solutions/articles/33000215104-troubleshooting-unable-to-generate-new-address" target="_blank">this troubleshooting article</a>';
	    echo $message;
	}
	else
	{
	    $message = 'Congrats! Setup is all done';
	    echo $message;
	}
	}
}else{
	$message = 'Please enter your API key and try again';
	echo $message;
}