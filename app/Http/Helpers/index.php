<?php


function Replace($data) {
    $data = str_replace("!", "", $data);
    $data = str_replace("@", "", $data);
    $data = str_replace("#", "", $data);
    $data = str_replace("$", "", $data);
    $data = str_replace("%", "", $data);
    $data = str_replace("^", "", $data);
    $data = str_replace("&", "", $data);
    $data = str_replace("*", "", $data);
    $data = str_replace("(", "", $data);
    $data = str_replace(")", "", $data);
    $data = str_replace("?", "", $data);
    $data = str_replace("+", "", $data);
    $data = str_replace("=", "", $data);
    $data = str_replace(",", "", $data);
    $data = str_replace(":", "", $data);
    $data = str_replace(";", "", $data);
    $data = str_replace("|", "", $data);
    $data = str_replace("'", "", $data);
    $data = str_replace('"', "", $data);
    $data = str_replace("  ", "-", $data);
    $data = str_replace(" ", "-", $data);
    $data = str_replace(".", "-", $data);
    $data = str_replace("__", "-", $data);
    $data = str_replace("_", "-", $data);
    return strtolower($data);
 }

 

function encryptor($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
    $secret_key = 'beatnik#technolgoy_sampreeti';
    $secret_iv = 'beatnik$technolgoy@sampreeti';

        // hash
    $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the encyption given text/string/number
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
            //decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}


// The function to count words in Unicode  strings
function countUnicodeWords( $unicode_string ){
    // First remove all the punctuation marks & digits
    $unicode_string = preg_replace('/[[:punct:][:digit:]]/', '', $unicode_string);
    // Now replace all the whitespaces (tabs, new lines, multiple spaces) by single space
    $unicode_string = preg_replace('/[[:space:]]/', ' ', $unicode_string);
    // The words are now separated by single spaces and can be splitted to an array
    // I have included \n\r\t here as well, but only space will also suffice
    $words_array = preg_split( "/[\n\r\t ]+/", $unicode_string, 0, PREG_SPLIT_NO_EMPTY );
    // Now we can get the word count by counting array elments
    return count($words_array);
}
  
  
  
function limitWordShow($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ", array_splice($words, 0, $word_limit));
}

function currentUserId(){
	return encryptor('decrypt', request()->session()->get('userId'));
}

function currentUser(){
	return encryptor('decrypt', request()->session()->get('roleIdentity'));
}

function company(){
    return ['company_id' => encryptor('decrypt', Session::get('companyId'))];
}

function branch(){
    return ['branch_id' => encryptor('decrypt', Session::get('branchId'))];
}



function invoice(){
	return [
		['image'=>'','link'=>''],
		['image'=>'','link'=>'']
	];
}
// if (!function_exists('money_format')) {

//     function money_format($number)
//     {
//         // Separate the number into its integer and decimal parts
//         $decimal = number_format($number - floor($number), 2, '.', '');
//         $money = floor($number);
//         $length = strlen($money);
//         $delimiter = '';
//         $money = strrev($money);

//         // Add commas as thousand separators
//         for ($i = 0; $i < $length; $i++) {
//             if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
//                 $delimiter .= ',';
//             }
//             $delimiter .= $money[$i];
//         }

//         $result = strrev($delimiter);
//         $decimal = substr($decimal, 1); // Remove the leading zero from the decimal part

//         // Append the decimal part to the result
//         $result = $result . $decimal;

//         return $result;
//     }
// }
if (!function_exists('money_format')) {
    function money_format($number)
    {
        // Check if the number is negative
        $isNegative = $number < 0;

        // Work with the absolute value of the number
        $number = abs($number);

        // Separate the number into its integer and decimal parts
        $decimal = number_format($number - floor($number), 2, '.', '');
        $money = floor($number);
        $length = strlen($money);
        $delimiter = '';
        $money = strrev($money);

        // Add commas as thousand separators
        for ($i = 0; $i < $length; $i++) {
            if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
                $delimiter .= ',';
            }
            $delimiter .= $money[$i];
        }

        $result = strrev($delimiter);
        $decimal = substr($decimal, 1); // Remove the leading zero from the decimal part

        // Append the decimal part to the result
        $result = $result . $decimal;

        // Add the minus sign back if the number was negative
        if ($isNegative) {
            $result = '-' . $result;
        }

        return $result;
    }
}