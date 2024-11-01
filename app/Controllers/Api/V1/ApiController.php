<?php

namespace App\Controllers\Api\V1;

use Ramsey\Uuid\Uuid;
use App\Models\APIRequestModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\Microfinance\BaseController as MicrofinanceBaseController;

class ApiController extends MicrofinanceBaseController
{
    use ResponseTrait;

    public function __construct()
    {
        parent::__construct();
    }

    private function logApiRequest($result, $responseStatusCode, $errorMessage = NULL)
    {
        $request = service('request');

        $uuid = Uuid::uuid4()->toString(); // Generate a version 4 (random) UUID

        $status = ($responseStatusCode >= 200 && $responseStatusCode < 400) ? 'SUCCESS' : 'FAILED';

        $data = [
            // 'url' => $request->uri->getPath(),
            'method' => $request->getMethod(),
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent(),
            'status' => $status,
            'output' => json_encode($result),
            'error_message' => $errorMessage,
            'uuid' => $uuid
        ];

        if (!empty($request->getJSON())) {
            # code...
            $data['input'] = json_encode($request->getJSON()); // Assuming the input is JSON
        }

        $model = new APIRequestModel();

        $save = $model->insert($data);
    }

    public function sendResponse($message, $result = [], $code = 200)
    {
        try {

            $response = [
                'status' => true,
                'message' => $message,
            ];

            if (!empty($result)) {
                $response['data'] = $result;
            }

            # Add the API Version
            $response['version'] = '1.0.0';

            # Log the request
            $this->logApiRequest($response, $code);

            return $this->respond($response, $code);
        } catch (\Exception $e) {
            # Log the request
            # $this->updateRequestStatus('FAILED', $e->getMessage());
            $this->logApiRequest([], 500, $e->getMessage());

            # Return an error response
            return $this->failServerError('External Error Occurred' . $e->getMessage());
        }
    }

    public function sendError($error, $result = [], $code = 404)
    {
        try {

            $response = [
                'status' => false,
                'message' => $error,
            ];


            if (!empty($result)) {
                $response['data'] = $result;
            }

            # Add the API Version
            $response['version'] = '1.0.0';

            # Log the request
            $this->logApiRequest($response, $code, $error);

            return $this->respond($response, $code);
        } catch (\Exception $e) {
            # Log the request
            # $this->updateRequestStatus('FAILED', $e->getMessage());
            $this->logApiRequest([], 500, $e->getMessage());

            # Return an error response
            return $this->failServerError('External Error Occurred.' . $e->getMessage());
        }
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        //
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        return $this->sendError('You\'re not Authorized to perform this.', [], 401);
    }
}
