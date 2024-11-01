<?php

# Documentation link
# https://pay.iotec.io/api-docs/index.html

namespace App\Libraries\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class IoTecPaySDK
{
    protected $clientId;
    protected $clientSecret;
    protected $walletId;

    protected $authClient;
    protected $apiClient;

    public $apiBaseUrl;
    public $accessTokenBaseUrl;

    protected $collectionParams;
    protected $disbursementParams;

    protected $internetConnection = false;

    public function __construct($clientId, $clientSecret, $walletId)
    {
        $this->apiBaseUrl = "https://pay.iotec.io/api/";
        $this->accessTokenBaseUrl = "https://id.iotec.io/connect/";

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->walletId = $walletId;

        $this->collectionParams = [
            "category" => "MobileMoney", # "MobileMoney" "WalletToWallet" "BankTransfer"
            "currency" => "ITX", # "ITX" "UGX"
            "walletId" => $walletId,
            # "externalId" => "string",
            # "payer" => "string",
            # "payerNote" => "string",
            # "amount" => 0,
            # "payeeNote" => "string",
            "channel" => "Api",
            "transactionChargesCategory" => "ChargeWallet" # "ChargeWallet" "ChargeCustomer"
        ];

        $this->disbursementParams = [
            "category" => "MobileMoney",
            "currency" => "ITX",
            "walletId" => $walletId,
            "externalId" => "string",
            "payeeName" => "string",
            "payeeEmail" => "string",
            "payee" => "string",
            "amount" => 0,
            "payerNote" => "string",
            "payeeNote" => "string",
            "channel" => "string",
            "bankId" => "30a36e6b-4ce6-409a-8471-fa29e9999b3a",
            "bankIdentificationCode" => "string",
            "bankTransferType" => "InternalTransfer"
        ];

        $this->authClient = new Client([
            'base_uri' => $this->accessTokenBaseUrl,
            'headers' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ]
        ]);

        $this->apiClient = new Client([
            'base_uri' => $this->apiBaseUrl,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ]
        ]);

        # check internet connection
        if ($this->checkInternetConnection()) {
            $this->internetConnection = true;
        }
    }

    public function checkNetworkConnection()
    {
        if ($this->internetConnection) {
            # Internet connection OK
            return true;
        } else {
            # Internet connection problem
            return false;
        }
    }

    private function checkInternetConnection($sCheckHost = 'www.google.com')
    {
        return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 5);
    }

    public function getToken()
    {
        $response = $this->authClient->request('POST', 'token', [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials',
            ],
        ]);

        return json_decode($response->getBody(), true)['access_token'];
    }

    public function makeRequest($method, $endpoint, $params = [])
    {
        if (!$this->checkInternetConnection()) {
            return ([
                'status' => false,
                'message' => 'No Internet Connection.'
            ]);
        }

        try {

            $accessToken = $this->getToken();

            $response = $this->apiClient->request($method, $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $params,
            ]);


            return json_decode($response->getBody(), true);
            # Access the status code and add it to the array
            $code = ['code' => $response->getStatusCode()];
            # Api response
            $data = json_decode($response->getBody(), true);
            return (array_merge($data, $code));
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                # You can also retrieve the response body in case of an error
                $body = $response->getBody();
                # echo "Error Response Body: $body";
                echo json_encode([
                    'status' => $statusCode,
                    'error' => 'External Error Occurred',
                    'messages' => 'External Error Occurred at the moment try again later',
                    'data' => $e
                ]);
                exit;
            }
        }
    }

    public function iotech($module, $uuid = NULL, array $params = [])
    {
        switch (strtolower($module)) {
            case 'payment':
                # Request a payment from a mobile subscriber.
                $collection = [
                    "category" => "MobileMoney",
                    "currency" => "ITX",
                    "walletId" => $this->walletId,
                    "channel" => "Api",
                    "transactionChargesCategory" => "ChargeWallet"
                ];
                # First method
                # Merge collection array with the params
                $params = array_merge($collection, $params);
                # Alternative method.
                /*
                $params['category'] = "MobileMoney";
                $params['currency'] = "ITX";
                $params['walletId'] = $this->walletId;
                $params['channel'] = "Api";
                $params['transactionChargesCategory'] = "ChargeWallet";
                */
                $response = $this->makeRequest('POST', 'collections/collect', $params);
                break;

            case 'status':
                # Get transaction status by requestId
                $transaction = $this->makeRequest('GET', 'collections/status/' . $uuid);
                # check the status
                # "Pending" "SentToVendor" "Success" "Failed"
                switch ($transaction['status']) {
                    case 'Success':
                        # code...
                        $response = $transaction;
                        break;

                    case 'Pending':
                        # code...
                        break;

                    case 'SentToVendor':
                        # code...
                        break;

                    default:
                        # code...
                        echo json_encode([
                            'status' => 404,
                            'error' => 'Transaction Failed',
                            'messages' => $transaction['statusMessage'],
                            'transaction' => $transaction
                        ]);
                        exit;
                        break;
                }
                break;

            case 'external':
                # Get transaction status by externalId
                $response = $this->makeRequest('GET', 'collections/external-id/' . $uuid);
                break;

            case 'history':
                # Get request history
                $params['WalletId'] = $this->walletId;
                $response = $this->makeRequest('GET', 'collections/history/', $params);
                break;

            case 'paged':
                # Get paged request history
                $params['WalletId'] = $this->walletId;
                $response = $this->makeRequest('GET', 'collections/paged-history', $params);
                break;

            case 'request':
                # Get request details by the requestId
                $response = $this->makeRequest('GET', 'collections/history/' . $uuid);
                break;

            case 'disburse':
                # Send money to mobile subscriber
                $params['category'] = "MobileMoney";
                $params['currency'] = "ITX";
                $params['walletId'] = $this->walletId;
                $params['channel'] = "Api";
                # $params['transactionChargesCategory'] = "ChargeWallet";
                $response = $this->makeRequest('POST', 'disbursements/disburse', $params);
                break;

            case 'bankAccount':
                # Send money to bank account
                $params['WalletId'] = $this->walletId;
                $response = $this->makeRequest('POST', 'disbursements/bank-disburse', $params);
                break;

            case 'bankList':
                # Get list of supported banks
                $response = $this->makeRequest('GET', 'disbursements/bank-list');
                break;

            case 'disburseStatus':
                # Get transaction status by transactionId
                $response = $this->makeRequest('GET', 'disbursements/status/' . $uuid);
                # check the status
                # "Pending" "SentToVendor" "Success" "Failed"
                switch ($response['status']) {
                    case 'Success':
                        # code...
                        $response = $response;
                        break;

                    case 'Pending':
                        # code...
                        break;

                    case 'SentToVendor':
                        # code...
                        break;

                    default:
                        # code...
                        echo json_encode([
                            'status' => 404,
                            'error' => 'Transaction Failed',
                            'messages' => $response['statusMessage'],
                            'transaction' => $response
                        ]);
                        exit;
                        break;
                }
                break;

            case 'disbursedExternal':
                # Get transaction status by externalId
                $response = $this->makeRequest('GET', 'disbursements/external-id/' . $uuid);
                break;

            case 'disbursedHistory':
                # Get request history
                $response = $this->makeRequest('GET', 'disbursements/history/', $params);
                break;

            case 'disbursedPaged':
                # Get paged request history
                $params['WalletId'] = $this->walletId;
                $response = $this->makeRequest('GET', 'disbursements/paged-history', $params);
                break;

            case 'disbursedRequest':
                # Get request details by the requestId
                $response = $this->makeRequest('GET', 'disbursements/history/' . $uuid);
                break;

            case 'WalletBalance':
                # WalletBalance by walletId
                $walletId = $this->walletId;
                $response = $this->makeRequest('GET', 'wallet-balance/' . $walletId);
                break;

            default:
                # code...
                $response = [
                    'status' => false,
                    'message' => 'Invalid parameter passed at the moment.'
                ];
                break;
        }

        /*
        # check the status
        # "Pending" "SentToVendor" "Success" "Failed"
        switch ($response['status']) {
            case 'Success':
                # code...
                break;

            case 'Pending':
                # code...
                break;

            case 'SentToVendor':
                # code...
                break;

            default:
                # code...
                break;
        }
        */


        return $response;
    }

    public function limitContent($editorContent, $maxWords = 120)
    {
        # Remove HTML tags and send the sanitized content
        $sanitizedContent = strip_tags($editorContent);

        # Limit the content to the specified number of words
        $words = explode(' ', $sanitizedContent);
        $limitedWords = array_slice($words, 0, $maxWords);
        $limitedContent = implode(' ', $limitedWords);

        return $limitedContent;
    }

    public function executeWithTimeout(callable $function, int $maxExecutionTime = 180, int $sleepInterval = 10)
    {
        $startTime = time();
        $maxExecutionTime = 180; // 3 minutes in seconds

        while (time() - $startTime < $maxExecutionTime) {
            $response = $function();

            if ($response['status'] == "Success") {
                return $response;
            }
            # Sleep for 10 seconds before calling the function again
            sleep($sleepInterval);
        }

        echo json_encode([
            'status' => false,
            'error' => 'Transaction Failed',
            'message' => "Failed to complete within {$maxExecutionTime} seconds."
        ]);
        exit;
    }
}
