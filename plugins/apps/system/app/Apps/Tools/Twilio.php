<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seotoaster
 * Date: 1/21/14
 * Time: 12:41 PM
 * To change this template use File | Settings | File Templates.
 */

class Apps_Tools_Twilio {

    public static function normalizePhoneNumberToE164($phone, $countryPhoneCode = null) {
        // convert letters to numbers
        $phone = self::convertAlphaNumeric($phone);
        // get rid of any non (digit, + character)
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        //Glue country phone code with rest of a number
        if(!empty($countryPhoneCode)) {
            $phone = preg_replace('/^(\+' . $countryPhoneCode . ')(\d+)/', '$2', $phone);
            $phone = preg_replace_callback('/^(0)(.+)/', function($matches) {
                    return str_replace('0', '', $matches[1]) . $matches[2];
                } , $phone);
            $phone = $countryPhoneCode . $phone;
        }
        // validate intl 10
        if(preg_match('/^\+([2-9][0-9]{9})$/', $phone, $matches)){
            return "+{$matches[1]}";
        }
        // validate US DID
        if(preg_match('/^\+?1?([2-9][0-9]{9})$/', $phone, $matches)){
            return "+1{$matches[1]}";
        }
        // validate INTL DID
        if(preg_match('/^\+?([2-9][0-9]{8,14})$/', $phone, $matches)){
            return "+{$matches[1]}";
        }
        // premium US DID
        if(preg_match('/^\+?1?([2-9]11)$/', $phone, $matches)){
            return "+1{$matches[1]}";
        }
        return false;
    }

    public static function convertAlphaNumeric($phone) {
        // conver letters to numbers
        return str_ireplace(
            array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'),
            array('2','2','2','3','3','3','4','4','4','5','5','5','6','6','6','7','7','7','7','8','8','8','9','9','9','9'),
            $phone
        );
    }

    public static function normalizeE164ForDisplay($e164Number) {
        preg_match("/^\+?1?([2-9][0-9]{8,13})$/",$e164Number,$match);
        if(strlen($match[1]))
            return $match[1];
        else return $e164Number;
    }

}