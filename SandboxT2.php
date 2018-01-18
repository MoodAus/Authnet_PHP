<?php
require 'vendor/autoload.php';
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
define("AUTHORIZENET_LOG_FILE", "phplog");
  
function getCustomerPaymentProfile($customerProfileId = "1811466738", $customerPaymentProfileId = "1806024653", $unmaskExpirationDate = true)
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(\SampleCode\Constants::MERCHANT_LOGIN_ID);
    $merchantAuthentication->setTransactionKey(\SampleCode\Constants::MERCHANT_TRANSACTION_KEY);
    
    // Set the transaction's refId
    $refId = 'ref' . time();
	//request requires customerProfileId and customerPaymentProfileId
	$request = new AnetAPI\GetCustomerPaymentProfileRequest();
	$request->setMerchantAuthentication($merchantAuthentication);
	$request->setRefId( $refId);
	$request->setCustomerProfileId($customerProfileId);
	$request->setCustomerPaymentProfileId($customerPaymentProfileId);
	$request->setunmaskExpirationDate($unmaskExpirationDate);
	$controller = new AnetController\GetCustomerPaymentProfileController($request);
	$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
	if(($response != null)){
		if ($response->getMessages()->getResultCode() == "Ok")
		{
			echo "GetCustomerPaymentProfile SUCCESS: " . "\n";
			echo "Customer Payment Profile Id: " . $response->getPaymentProfile()->getCustomerPaymentProfileId() . "\n";
			echo "Customer Payment Profile Billing Address: " . $response->getPaymentProfile()->getbillTo()->getAddress(). "\n";
			echo "Customer Payment Profile First Name: " . $response->getPaymentProfile()->getbillTo()->getFirstName(). "\n";
			echo "Customer Payment Profile Last Name: " . $response->getPaymentProfile()->getbillTo()->getLastName(). "\n";
			echo "Customer Payment Profile Card Last 4: " . $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber(). "\n";
			echo "Customer Payment Profile Expiration Date: " . $response->getPaymentProfile()->getPayment()->getCreditCard()->getExpirationDate(). "\n";
			echo "Customer Payment Profile Card Type: " . $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardType(). "\n";
			if($response->getPaymentProfile()->getSubscriptionIds() != null) 
			{
				if($response->getPaymentProfile()->getSubscriptionIds() != null)
				{
					echo "List of subscriptions:";
					foreach($response->getPaymentProfile()->getSubscriptionIds() as $subscriptionid)
						echo $subscriptionid . "\n";
				}
			}
		}
		else
		{
			echo "GetCustomerPaymentProfile ERROR :  Invalid response\n";
			$errorMessages = $response->getMessages()->getMessage();
		    echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
		}
	}
	else{
		echo "NULL Response Error";
	}
	return $response;
}
if(!defined('DONT_RUN_SAMPLES'))
    getCustomerPaymentProfile();
?>