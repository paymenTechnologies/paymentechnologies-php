<?php

class  paymenTechnologies {
 
    public $payment;
    public $secret_key;
    public $api_url;
    public $api_url_3DSv;
    public $api_type;

    public function __construct($payment) {
        $this->payment = $payment;

        // sandbox
        $this->api_url = 'https://sandbox-api.paymentechnologies.co.uk/v2/authorize';
        $this->api_url_3DSv = 'https://sandbox-api.paymentechnologies.co.uk/v2/authorize-3dsv';

        // live
        // $this->api_url = 'https://api.paymentechnologies.co.uk/v2/authorize';
        // $this->api_url_3DSv = 'https://api.paymentechnologies.co.uk/v2/authorize-3dsv';
        
        $this->api_type = $payment['type'];
    }

    function payment()
    {
        $data_stream = http_build_query($this->payment);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_stream);

        if($this->api_type == 'API') {
          curl_setopt($ch, CURLOPT_URL, $this->api_url);
        } elseif ($this->api_type == '3DSV'){
          curl_setopt($ch, CURLOPT_URL, $this->api_url_3DSv);
        }
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result_str = curl_exec($ch);
        if (curl_errno($ch) != 0) {
            $result_str = 'curl_error=' . curl_errno($ch);
        }
        curl_close($ch);
        return $result_str;

    }
}
