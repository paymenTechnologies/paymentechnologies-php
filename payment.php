<?php

class Payment {
 
    public $payment;
    public $secret_key;
    public $api_url;
    public $api_url_3DSv;
    public $api_type;

    public function __construct($payment, $api_type = "API") {
        $this->payment = $payment;
        $this->api_url = 'https://pay.paymentechnologies.co.uk/authorize_payment';
        $this->api_url_3DSv = 'https://pay.paymentechnologies.co.uk/authorize3dsv_payment';
        $this->api_type = $api_type;
    }

    function Pay()
    {

        // option 1
        // $data_stream = http_build_query($this->payment);
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data_stream);
        // if($this->api_type == 'API') {
        //   curl_setopt($ch, CURLOPT_URL, $this->api_url);
        // } elseif ($this->api_type == '3DSV'){
        //   curl_setopt($ch, CURLOPT_URL, $this->api_url_3DSv);
        // }
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // $result_str = curl_exec($ch);
        // if (curl_errno($ch) != 0) {
        //     $result_str = 'curl_error=' . curl_errno($ch);
        // }
        // curl_close($ch);
        // return $result_str;


        // option 2
        // use key 'http' even if you send the request to https://...
        $options = array(
          'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($this->payment),
          ),
        );
        $context  = stream_context_create($options);

        if($this->api_type == 'API') {
          $result = file_get_contents($this->api_url, false, $context);
        } elseif ($this->api_type == '3DSV'){
          $result = file_get_contents($this->api_url_3DSv, false, $context);
        }
        
        return $result;
    }
}