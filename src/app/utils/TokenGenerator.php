<?php
namespace App\Utils;

class TokenGenerator {
    
    public static function getToken($length = 32){
        if(!isset($length) || intval($length) <= 8 ){
          $length = 32;
        }
        else if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length));
        }
        else if (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
        } 
        else if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }
    }

    public static function getSalt(){
        return substr(strtr(base64_encode(hex2bin(TokenGenerator::getToken(32))), '+', '.'), 0, 44);
    }
}