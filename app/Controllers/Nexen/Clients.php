<?php

namespace App\Controllers\Nexen;

use Hermawan\DataTables\DataTable;
use App\Controllers\MasterController;

class Clients extends MasterController
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Dashboard';
        $this->title = 'Nexen Tech Clients';
        $this->menuItem = [
            'title' => $this->title,
            'menu' => $this->menu,
        ];
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        return view('nexen/clients/index', [
            'title' => $this->title,
            'menu' => $this->menu,
            'settings' => $this->settingsRow,
            'user' => $this->userRow,
            'userMenu' => $this->load_menu(),
        ]);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $where = ['accounts.account_id !=' => null];
        $accounts = $this->account
            ->select('accounts.name, accounts.code, accounts.status, accounts.created_at, 
            accounts.updated_at, accounts.id, c.name as type')
            ->join('accounts c', 'c.id = accounts.account_id', 'left')
            ->where($where);

        return DataTable::of($accounts)
            ->add('checkbox', function ($account) {
                return '
                    <div class="">
                        <input type="checkbox" class="data-check" value="' . $account->id . '">
                    </div>
                ';
            })
            ->addNumbering("no") //it will return data output with numbering on first column
            ->add('status', function ($account) {
                if ($account->status) {
                    # code...
                    $text = '<span class="badge badge-sm badge-info">Active</span>';
                } else {
                    # code...
                    $text = '<span class="badge badge-sm badge-danger">Inactive</span>';
                }

                return $text;
            })
            ->add('action', function ($account) {
                if (strtolower($account->status)) {
                    $text = "info";
                } else {
                    $text = "danger";
                }

                return '';
                // show buttons based on user permissions
                $actions = '
                <div class="dropdown custom-dropdown mb-0">
                    <div class="btn sharp btn-' . $text . ' tp-btn" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="12" cy="5" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="12" cy="19" r="2"/></g></svg>
                    </div>
                    <div class="dropdown-menu dropdown-menu-end">';
                if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                    $actions .= '<a href="javascript:void(0)" onclick="view_account(' . "'" . $account->id . "'" . ')" title="View Client" class="dropdown-item"><i class="fas fa-eye text-success"></i> View Client</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="edit_department(' . "'" . $account->id . "'" . ')" title="Edit Client" class="dropdown-item"><i class="fas fa-edit text-info"></i> Edit Client</a>';
                }
                if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
                    $actions .= '<div class="dropdown-divider"></div>
                                    <a href="javascript:void(0)" onclick="delete_account(' . "'" . $account->id . "'" . ',' . "'" . $account->name . "'" . ')" title="Delete Client" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete Client</a>';
                }
                $actions .= ' 
                        </div>
                </div>';
                return $actions;
            })
            ->toJson(true);
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
