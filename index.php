<?php

include_once 'card.php';
include_once 'paymenTechnologies.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = $_POST;

    // important
    // please change the credentials to your own credentials
    $data['secret_key'] = '61bb49f1e26ab5.50561485';
    $data['authenticate_id'] = 'bddc56b7082e140b4f93fd693b033ddd';
    $data['authenticate_pw'] = 'b3317d7870974f80d26b7d656e8de498';


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
        'transaction_type' => $data['transaction_type'],
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
        'transaction_hash' => $data['transaction_hash'],
        'customerip' => $data['customerip']
    );
    // end signature segment information
    // only the above list need to calculate signature

    // additional fields,  3dsv
    if(isset($data['transaction_type']) && $data['transaction_type'] == '3DSV') {
        $payment['dob'] = $data['dob'];
        $payment['success_url'] = urlencode($data['success_url']);
        $payment['fail_url'] = urlencode($data['fail_url']);
        $payment['notify_url'] = urlencode($data['notify_url']);
    }

    // add the signature to list
    $signature = $card->calculateSignature($payment);

    $payment['signature'] = $signature;

    // integration type
    // A => API version
    // 3DSV > 3DSV version
    $payment['transaction_type'] = $data['transaction_type'];
    
    $pay = new paymenTechnologies($payment);
    $response = $pay->payment();

    echo $response;

} else {
    // set response code - 504 Not found
    http_response_code(504);
  
    echo json_encode(
        array("message" => "Invalid Request.")
    );
}
