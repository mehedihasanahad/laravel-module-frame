<?php

namespace App\Libraries;
class SendMail {
    private static function getToken() {

        $sms_api_url_for_token      = env( 'SMS_API_BASE_URL_FOR_TOKEN' ) . '/auth/realms/dev/protocol/openid-connect/token';
        $postdata['client_id']      = env( 'SMS_CLIENT_ID' );
        $postdata['client_secret']  = env( 'SMS_CLIENT_SECRET' );
        $postdata['grant_type']     = env( 'SMS_GRANT_TYPE' );

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
        );

        try {
            $respData = CommonFunction::curlPostRequest($sms_api_url_for_token, $postdata, $headers);
            if ($respData['http_code'] != 200) {
                return false;
            }
            $decoded_json = json_decode( $respData['data'], true );
            return $decoded_json['access_token'];

        } catch ( \Exception $e ) {
            return false;
        }
    }
    public static function sendEmail( $emailData ) {
        $onlyToken = self::getToken();
        if (!isset($onlyToken)) {
            return false;
        }

        $sms_api_url = env( 'SMS_API_BASE_URL_FOR_SEND' ) . '/api/broker-service/email/send_email';
        $response = [];
        try {
            $headers = array(
                "Authorization: Bearer $onlyToken",
                "Content-Type: application/x-www-form-urlencoded",
            );
            $respData = CommonFunction::curlPostRequest($sms_api_url, $emailData, $headers);
            $respDataArr = json_decode($respData['data'],true);

            $response['status']=$respDataArr['status'];
            if($respDataArr['status'] == 200){
                $response['message']= "Email successfully send to ".$emailData['receipant'];
            }else{
                $response['message']= "Email cannot send to ".$emailData['receipant'];
            }
        } catch ( \Exception $e ) {
            $response['status']= false;
            $response['message']= "Something went wrong while sending Email.";
        }

        #return $response;          // while working with this function, this line need to be uncommented and the rest will be removed
        echo '<pre>';
        print_r($response);
    }
}
