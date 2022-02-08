<?php

class MomoApi{
    /**
     * Partner Code : Your business account’s unique identity.
     * Access Key: Server Access key
     * Secret Key: Used to create digital signature.
     * Public Key: Used to encrypt data by RSA algorithm.
     */
    const PARTNER_CODE = 'MOMO9EIU20211225';
    const ACCESS_KEY = 'lGFfQkYNbDhunacu';
    const SECRET_KEY = 'rvZBztwXFApgfyl74PhMlfUXAnam63uE';
    const API_ENDPOINT = 'https://test-payment.momo.vn';

    const URI_SAND = 'https://test-payment.momo.vn';
    const URI_PRO = 'https://payment.momo.vn';
    const URL ='/v2/gateway/api/create';

    const REDIRECT_URL = 'http://localhost/momo';

    public function _doRequest(){

    }

    protected function header(){
        $header = array(
            'Content-Type'=>'application/json; charset=UTF-8',
            'Method'=>'POST',
            'Domain'=>self::URI_SAND
        );
        return $header;
    }
    public function init(){
        $url = self::API_ENDPOINT.self::URL;
        $data = [
            'accessKey'=> self::ACCESS_KEY,
            'amount'=> 10000,
            'extraData'=> 0,
            'orderId'=> time().'AC',
            'orderInfo'=> 'Thanh toán qua MoMo',
            'ipnUrl'=> 'https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b',
            'partnerCode'=> self::PARTNER_CODE,
            'redirectUrl'=> self::REDIRECT_URL,
            'requestId'=> time(),
            'requestType'=> 'captureWallet',
        ];
        $sign =  $this->signature($data);
        $params = [
            'partnerCode' => $data['partnerCode'],
            'requestId' => $data['requestId'],
            'amount' => $data['amount'],
            'orderId' => $data['orderId'],
            'orderInfo' => $data['orderInfo'],
            'redirectUrl' => $data['redirectUrl'],
            'ipnUrl' => $data['ipnUrl'],
            'requestType' => $data['requestType'],
            'extraData' => $data['extraData'],
            'lang' => 'vi',
            'signature' => $sign,
        ];
        $params = json_encode($params);
     
        
        $ch = curl_init(); //init
        curl_setopt($ch,CURLOPT_URL,$url); //url
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($params))
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $response = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        echo '<pre>';
        print_r(json_decode($response));
        echo '</pre>';
        die('die');
        curl_close($ch);
        
    }

    public function signature($data,$serect = self::SECRET_KEY){
        $rawHash = "accessKey=" . $data['accessKey'] . "&amount=" . $data['amount'] . "&extraData=" . $data['extraData'] . "&ipnUrl=" . $data['ipnUrl'] . "&orderId=" . $data['orderId'] . "&orderInfo=" . $data['orderInfo'] . "&partnerCode=" . $data['partnerCode'] . "&redirectUrl=" . $data['redirectUrl'] . "&requestId=" . $data['requestId'] . "&requestType=" . $data['requestType'];
        $sig = hash_hmac('sha256', $rawHash, $serect);
        return $sig;
    }
}