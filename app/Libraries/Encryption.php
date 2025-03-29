<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Auth;

class Encryption{
    private static $defskey 	= "s3321B$#K3y!@#BAT4v"; // EncryptionKey

    public static function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return urlencode($data);
    }

	public static function safe_b64decode($string) {
        $data = urldecode($string);
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public static function encodeId($id, $module=null){
        if(!$module) $module=time();
        if(Auth::user()) {
            return Encryption::encode($module . '_' . $id . '_' . Auth::user()->id);
        }else{
            return Encryption::encode($module . '_' . $id);
        }
    }

    public static function decodeId($id, $module=null){
        $ids=explode('_',Encryption::decode($id));
        if(count($ids)==3 && Auth::user()){
            if(Auth::user()->id == $ids[2]) {
                if ($module) {
                    if (strcmp($module, $ids[0]) == 0) {
                        return $ids[1];
                    }
                } else {
                    return $ids[1];
                }
            }
        }
        if(count($ids)==2){
            if ($module) {
                if (strcmp($module, $ids[0]) == 0) {
                    return $ids[1];
                }
            } else {
                return $ids[1];
            }
        }
        die('Invalid Id! 401-This incident will be reported.');
    }

    public static function encode($data)
    {
        if(!$data){return false;}
        $cypherData = config('app.cipher');
        $ivLength = openssl_cipher_iv_length($cypherData);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt($data, $cypherData, Encryption::$defskey, OPENSSL_RAW_DATA, $iv);
        return Encryption::safe_b64encode($iv . $encrypted);
    }

    public static function decode($encrypted)
    {
        if(!$encrypted){return false;}
        $cypherData = config('app.cipher');
        $data = Encryption::safe_b64decode($encrypted);
        $ivLength = openssl_cipher_iv_length($cypherData);
        $iv = substr($data, 0, $ivLength);
        $encryptedData = substr($data, $ivLength);
        $decrypted = openssl_decrypt($encryptedData, $cypherData, Encryption::$defskey, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }
}
