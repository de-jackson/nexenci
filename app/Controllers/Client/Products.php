<?php

namespace App\Controllers\Client;

use App\Controllers\Client\MainController;

class Products extends MainController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Loans';
        $this->title = 'Applications';

        if (strtolower($this->userRow["account_type"]) == 'client') {
            $this->report->setUserAccountType([
                # Index: 0 account type i.e Administrator, Employee, Client
                strtolower($this->userRow["account_type"]),
                # Index: 1
                $this->userRow["id"]
            ]);
        }
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->loanProduct->find($id);
        // $data = $this->product($id);
        if ($data) {
            $data['charges'] = $this->getCharges([
                'charges.product_id' => $id,
                'charges.status' => 'Active',
            ]);
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
