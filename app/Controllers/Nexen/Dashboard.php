<?php

namespace App\Controllers\Nexen;

use App\Controllers\MasterController;

class Dashboard extends MasterController
{
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Dashboard';
        $this->title = 'Nexen Tech';
    }

    public function index()
    {
        $accounts = $this->accounts(['accounts.account_id' => null, 'accounts.status' => '1']);
        return view('nexen/dashboard/nexen', [
            'title' => $this->title,
            'menu' => $this->menu,
            'settings' => $this->settingsRow,
            'user' => $this->userRow,
            'userMenu' => $this->load_menu(),
            'permissions' => $this->userPermissions,

            'summary' => [
                'sms' => [
                    'apiResponse' => $this->egoAPI->initiate('balance')
                ],
                'accounts' => $accounts,
                'subAccounts' => $this->accounts(['accounts.account_id !=' => null, 'accounts.status' => '1']),
            ]
        ]);
    }
}
