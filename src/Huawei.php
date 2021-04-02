<?php

namespace Navari\Huawei;

use Navari\Huawei\Exception\HuaweiException;
use Navari\Huawei\Http\Request;
use GuzzleHttp\Client;

class Huawei
{
    private $url = 'https://subscr-at-dre.iap.dbankcloud.com/';
    private $oauthUrl = 'https://oauth-login.cloud.huawei.com/oauth2/v3/token';
    private $authorization = '';
    private $authorizationType = '';
    private $clientSecret = '';
    private $clientId = '';
    private $headers = [];

    public function __construct($clientId, $clientSecret)
    {
        Request::setHttpClient(new Client());
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->getAuthorizationToken();
    }

    /**
     * @param $rawBody
     * @return mixed
     */
    private function decodeRawBodyToJson($rawBody)
    {
        return json_decode($rawBody, true, 512, JSON_BIGINT_AS_STRING);
    }

    /**
     * @throws HuaweiException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function getAuthorizationToken()
    {
        $response = Request::post($this->oauthUrl, $this->headers, [
            'grant_type' => 'client_credentials',
            'client_secret' => $this->clientSecret,
            'client_id' => $this->clientId
        ]);

        if($response->code !== 200){
            throw new HuaweiException('Server connect failed response code : '. $response->code . ' Body : '. $response->raw_body);
        }

        $jsonResponse = $this->decodeRawBodyToJson($response->raw_body);

        $this->authorization = $jsonResponse['access_token'];
        $this->authorizationType = $jsonResponse['token_type'];
    }

    /**
     * @return array
     */
    private function buildHeaders()
    {
        $this->headers['Authorization'] = 'Basic ' . base64_encode('APPAT:'.$this->authorization);
        return $this->headers;
    }

    /**
     * @param $purchaseToken
     * @param $subscriptionId
     * @return mixed
     * @throws HuaweiException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function verifySubscription($purchaseToken, $subscriptionId)
    {
        $response = Request::post($this->url. 'sub/applications/v2/purchases/get', $this->buildHeaders(), [
            'purchaseToken' => $purchaseToken,
            'subscriptionId' => $subscriptionId
        ]);
        if($response->code !== 200)
            throw new HuaweiException('Server connect failed response code : '. $response->code . ' Body : '. $response->raw_body);

        $jsonResponse = $this->decodeRawBodyToJson($response->raw_body);

        return $jsonResponse;
    }

    /**
     * @param $purchaseToken
     * @param $productId
     * @return mixed
     * @throws HuaweiException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function verifyOrder($purchaseToken, $productId)
    {
        $response = Request::post($this->url. 'applications/purchases/tokens/verify', $this->buildHeaders(), [
            'purchaseToken' => $purchaseToken,
            'productId' => $productId
        ]);
        if($response->code !== 200)
            throw new HuaweiException('Server connect failed response code : '. $response->code . ' Body : '. $response->raw_body);

        $jsonResponse = $this->decodeRawBodyToJson($response->raw_body);

        return $jsonResponse;
    }


    /**
     * @param $purchaseToken
     * @param $productId
     * @return mixed
     * @throws HuaweiException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function confirmPurchase($purchaseToken, $productId)
    {
        $response = Request::post($this->url. 'applications/v2/purchases/confirm', $this->buildHeaders(), [
            'purchaseToken' => $purchaseToken,
            'productId' => $productId
        ]);
        if($response->code !== 200)
            throw new HuaweiException('Server connect failed response code : '. $response->code . ' Body : '. $response->raw_body);

        $jsonResponse = $this->decodeRawBodyToJson($response->raw_body);

        return $jsonResponse;
    }

    /**
     * @param $purchaseToken
     * @param $subscriptionId
     * @return mixed
     * @throws HuaweiException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function cancelSubscription($purchaseToken, $subscriptionId)
    {
        $response = Request::post($this->url. 'sub/applications/v2/purchases/stop', $this->buildHeaders(), [
            'purchaseToken' => $purchaseToken,
            'subscriptionId' => $subscriptionId
        ]);
        if($response->code !== 200)
            throw new HuaweiException('Server connect failed response code : '. $response->code . ' Body : '. $response->raw_body);

        $jsonResponse = $this->decodeRawBodyToJson($response->raw_body);

        return $jsonResponse;
    }

    /**
     * @param array $params
     * @return mixed
     * @throws HuaweiException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function cancelledList(array $params)
    {
        $response = Request::post($this->url. 'sub/applications/v2/purchases/stop', $this->buildHeaders(), $params);
        if($response->code !== 200)
            throw new HuaweiException('Server connect failed response code : '. $response->code . ' Body : '. $response->raw_body);

        $jsonResponse = $this->decodeRawBodyToJson($response->raw_body);

        return $jsonResponse;

    }

    /**
     * @param string $content
     * @param string $sign
     * @param string $publicKey
     * @return int
     */
    public function verifyCallback(string $content, string $sign, string $publicKey)
    {
        $opensslPublicKey = openssl_get_publickey($publicKey);
        $verify = openssl_verify($content, base64_decode($sign), $opensslPublicKey, 'SHA256');
        openssl_free_key($opensslPublicKey);

        return $verify;
    }
}