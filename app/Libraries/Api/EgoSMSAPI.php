<?php

# Documentation link
# https://pay.iotec.io/api-docs/index.html

namespace App\Libraries\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class EgoSMSAPI
{
    protected $apiClient;
    protected $apiUrl;
    protected $username;
    protected $secretKey;
    protected $sender_id;

    protected $internetConnection = false;

    public function __construct()
    {
        $this->apiUrl = "https://www.egosms.co/api/v1/json/";
        $this->username = getenv('EGO_SMS_USERNAME');
        $this->secretKey = getenv('EGO_SMS_SECRET_KEY');
        $this->sender_id = getenv('EGO_SMS_SENDER_ID');

        $this->apiClient = new Client([
            'base_uri' => $this->apiUrl,
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

    public function makeRequest($method, $params = [])
    {
        if (!$this->checkInternetConnection()) {
            return ([
                'status' => false,
                'message' => 'No Internet Connection.'
            ]);
        }

        $endpoint = $this->apiUrl;

        try {

            $response = $this->apiClient->request($method, $endpoint, [
                'headers' => [
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

    public function initiate($module, array $params = [])
    {
        switch ($module) {
            case 'sms':
                # Request a payment from a mobile subscriber.
                # This represents level of priority. Priority can have values from (0-4) with  
                # 0: highest, 1: high, 2: Medium, 3: Low, 4: Lowest
                $data = [
                    "method" => "SendSms",
                    "userdata" => [
                        "username" => $this->username,
                        "password" => $this->secretKey,
                    ],
                    "msgdata" => [
                        [
                            "number" => $params['number'],
                            "message" => $params['message'],
                            "senderid" => $this->sender_id,
                            "priority" => "0"
                        ]
                    ]
                ];
                $response = $this->makeRequest('POST', $data);
                break;

            case 'balance':
                # Request balance inquiry.
                $data = [
                    "method" => "Balance",
                    "userdata" => [
                        "username" => $this->username,
                        "password" => $this->secretKey,
                    ]
                ];
                $response = $this->makeRequest('POST', $data);
                break;

            default:
                # code...
                $response = [
                    'status' => false,
                    'message' => 'Invalid parameter passed at the moment.'
                ];
                break;
        }
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
