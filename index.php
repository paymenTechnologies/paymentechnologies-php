<?php

include_once 'card.php';
include_once 'paymenTechnologies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = $_POST;

    // important
    // please change the credentials to your own credentials
    // $data['secret_key'] = '61ada225b0c248.83763788';
    // $data['authenticate_id'] = 'f546ef2cf2528ea73206873cc439ba28';
    // $data['authenticate_pw'] = 'e19a5ecd06e75f7a7c46dd0f47c5655d';


    // card information
    $cardInfo = array (
        'firstname' => $data['firstname'],
        'lastname' => $data['lastname'],
        'card_number' => $data['card_number'],
        'expiration_month' => $data['exp_month'],
        'expiration_year' => $data['exp_year'],
        'cvc' => $data['cvc_code'],
        'secret_key' => $data['secret_key']
    );

    $card = new Card($cardInfo);

    $data['card_info'] = $card->encryptCardInfo();

    // signature segment information
    $payment = array(
        'authenticate_id' => $data['authenticate_id'],
        'authenticate_pw' => $data['authenticate_pw'],
        'orderid' => $data['orderid'],
        'transaction_type' => 'A',
        'amount' => $data['amount'],
        'currency' => $data['currency'],
        'card_info' => $data['card_info'],
        'email' => $data['email'],
        'street' => $data['street'],
        'city' => $data['city'],
        'zip' => $data['zip'],
        'state' => $data['state'],
        'country' => $data['country'],
        'phone' => $data['phone'],
        // 'transaction_hash' => $data['transaction_hash'],
        'customerip' => $data['customerip']
    );
    // end signature segment information
    // only the above list need to calculate signature

    // additional fields,  3DSV
    // uncomment if 3DSV 
    // comment if API
    $payment['dob'] = $data['dob'];
    $payment['success_url'] = urlencode($data['success_url']);
    $payment['fail_url'] = urlencode($data['fail_url']);
    $payment['notify_url'] = urlencode($data['notify_url']);
    // end additional fields,  3DSV


    // add the signature to list
    $signature = $card->calculateSignature($payment);

    $payment['signature'] = $signature;

    // integration type
    
    // ********** IMPORTANT: ***********
    // 
    // A => API version
    // $pay = new paymenTechnologies($payment, 'A');
    // 
    // 3DSV > 3DSV version
    // $pay = new paymenTechnologies($payment, '3DSV');
    // 
    // ********** END ***********

    $pay = new paymenTechnologies($payment, 'A');
    $response = $pay->payment();

    echo "Card Info: ". $data['card_info'] . "\n";
    echo "Signature: ". $payment['signature'] . "\n";
    echo $response;

} else {
    // set response code - 504 Not found
    http_response_code(504);
  
    echo json_encode(
        array("message" => "Invalid Request.")
    );
}
