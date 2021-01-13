<?php

class Card {

    private $firstname;
    private $lastname;
    private $card_number;
    private $expiration_month;
    private $expiration_year;
    private $cvc;
    private $secret_key;
    private $array_params;

    public function __construct($data)
    {

        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->card_number = $data['card_number'];
        $this->expiration_month = $data['expiration_month'];
        $this->expiration_year = $data['expiration_year'];
        $this->cvc = $data['cvc'];
        $this->secret_key = $data['secret_key'];

        $this->array_params = array(
            "card_number" => $this->card_number,
            "expire" => $this->expiration_month . '/' . $this->expiration_year,
            "cvc" => $this->cvc,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname
        );
    }

    function array_implode_with_keys($array) {
        $return = '';
        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                $return .= $key . '||' . $value . '__';
            }
            $return = substr($return, 0, strlen($return) - 2);
        }
        return $return;
    }

    function encryptCardInfo()
    {
        $string = preg_replace("/[^A-Za-z0-9 ]/", '', $this->secret_key);
        $encryption_key = substr($string, 0, 16);

        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($this->array_implode_with_keys($this->array_params), 'aes-256-cbc', $encryption_key, 0, $iv);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        return base64_encode($encrypted . '::' . $iv);
    }

    function calculateSignature($params)
    {
        $signature = "";
        ksort($params);   
        foreach ($params as $key => $val) {
            if ($key != "signature") {
                $signature .= $val;
            }
        }
        
        $signature = $signature . $this->secret_key;  
        return strtolower(sha1($signature));   
    }
}