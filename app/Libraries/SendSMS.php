<?php

namespace App\Libraries;
use App\Models\NotificationsLog;

class SendSMS {
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

    private static function getCURLResult($curloptURL, $fieldData)
    {
        $onlyToken = self::getToken();
        if (!isset($onlyToken)) {
            return false;
        }

        $headers = array(
            "Authorization: Bearer $onlyToken",
            "Content-Type: application/json",
        );
        $respData = CommonFunction::curlPostRequest($curloptURL, $fieldData, $headers, true);
        if ($respData['http_code'] != 200) {
            return false;
        }
        return $respData['data'];
    }

	public static function sendFastSMS( $mobile_no, $message ) {
		$validated_mobile_no = self::formatMobileNumberWithValidation( $mobile_no ); //if valid it returns 8801xxxxxxxxx
		if ( $validated_mobile_no == false ) {
			return false;
		}
		$sms_api_url_for_send = env( 'SMS_API_BASE_URL_FOR_SEND' ) . '/api/broker-service/sms/send_sms';

		try {
			$fieldData  = json_encode( [ 'msg' => $message, 'destination' => $validated_mobile_no ] );
			$response   = self::getCURLResult( $sms_api_url_for_send, $fieldData );
			$decoded_response = json_decode( $response, true );

			if ( $response && $decoded_response['status'] === 200 ) {
                self::storeNotificationsLog($validated_mobile_no,$message,'success',$response);
				return $decoded_response;
			} else {
                self::storeNotificationsLog($validated_mobile_no,$message,'error',$response);
				return false;
			}

		} catch ( Exception $e ) {
            self::storeNotificationsLog($validated_mobile_no,$message,'exception',"ErrorMsg: $e->getMessage(); File: $e->getFile(); Line: $e->getLine()");
			return false;
		}
	}

	/**
	 * @param $smsTrackingCode
	 *
	 * @return bool
	 */

	/**
	 * @param $mobile_no Accepts Following
	 * 01711-393336
	 * +8801615000888
	 * +8801615-000888
	 * 01615000888
	 * 1755676700
	 *
	 * @return int|mixed|string
	 * 8801831614476
	 */
	private static function formatMobileNumberWithValidation( $mobile_no ) {
		$mobile_no_formated  = str_replace( "+88", "88", "$mobile_no" );
		$firstValidationFlag = ( preg_match( "/^(88)?0?1[0-9]{3}(\-)?[0-9]{6}$/", $mobile_no_formated ) );

		if ( $firstValidationFlag ) {
			$removedDash = str_replace( "-", "", $mobile_no );
			if ( substr( $removedDash, 0, 2 ) == '01' ) {
				return '88' . $removedDash;
			} else if ( substr( $removedDash, 0, 1 ) == '1' ) {
				return '880' . $removedDash;
			} else if ( substr( $removedDash, 0, 1 ) == '+' ) {
				return str_replace( "+", "", $removedDash );
			} else {
				return $removedDash;
			}
		} else {
			return false;
		}
	}

    public static function storeNotificationsLog($mobile,$message,$type,$response){

        $data = [
                'mobile' => !empty($mobile) ? $mobile : '',
                'message' => !empty($message) ? $message : '',
                'type' => !empty($type) ? $type : '',
                'source_project' => 'PRP',
                'response' => !empty($response) ? $response : '',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 0,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 0,
        ];
        NotificationsLog::create($data);
    }
	/*     * ****************************End of Class***************************** */
}
