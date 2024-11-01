<?php

namespace App\Controllers\Client;


use \Hermawan\DataTables\DataTable;
use App\Controllers\Client\MainController;

class Shares extends MainController
{
    protected $account_typeId = 8;
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Shares';
        $this->title = 'Shares';
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
     * Handles the display of different share types (purchases, withdrawals, dividends).
     *
     * This function takes a parameter $type which determines the type of shares to be displayed.
     * It then sets the appropriate view, URL, and data based on the $type.
     *
     * @param string $type The type of shares to be displayed. Default is 'purchases'.
     *
     * @return mixed Returns a view with the specified data if the user has permission.
     *               Redirects to the dashboard with a failed flash message if the user does not have permission.
     */
    public function module($type = 'purchases')
    {
        switch ($type) {
            case 'purchases':
                $part = 'credit';
                $url = 'client/shares/purchases';
                break;
            case 'withdrawals':
                $part = 'debit';
                $url = 'client/shares/withdrawals';
                break;
            case 'dividends':
                $part = 'debit';
                $url = 'client/shares/dividends';
                break;
            default:
                session()->setFlashdata('failed', ucwords($type) . ' Page requested can not be found!');
                return redirect()->to(base_url('client/dashboard'));
                break;
        }
        $this->title = ucfirst($type);
        $this->menuItem = [
            'menu' => $this->menu,
            'title' => $this->title,
        ];
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $data = [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'part' => (isset($part) ? $part : null),
            ];

            return view($url, $data);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->menu . ' > ' . $this->title . " page!");
            return redirect()->to(base_url('client/dashboard'));
        }
    }

    /**
     * Retrieves and calculates the client's share balance for each particular.
     *
     * This function retrieves the client's record and distinct share particulars.
     * It then loops over each particular, calculates the total shares transactions,
     * and retrieves the client's share balance for each particular.
     * If a charge is found for a particular, it calculates the share balance in units.
     *
     * @param int|string|null $client_id The ID of the client for whom to retrieve share particulars.
     *
     * @return ResponseInterface
     *     Returns a JSON response containing an array of particular objects with calculated share balances.
     *     Returns a 404 Not Found JSON response if the client record or particulars are not found.
     */
    public function client_sharesParticulars($client_id = null)
    {
        // Find client record
        $clientRow = $this->client->find($client_id);
        if (!$clientRow) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Client record not found',
            ];
            return $this->respond($response);
        }

        // Get distinct share particulars
        $particularIds = $this->entry->distinct()->select('particular_id')
            ->where(['client_id' => $client_id, 'account_typeId' => $this->account_typeId])
            ->findColumn('particular_id');

        if (!$particularIds) {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'No Client Shares Particulars found!',
            ];
            return $this->respond($response);
        }

        // Loop over each particular and calculate client shares
        $data = [];
        foreach ($particularIds as $particular_id) {
            $particular = $this->particular->find($particular_id);
            # Calculate total shares transactions for this particular (all clients)
            $sharesTransactions = $this->entry->select('SUM(amount) as amount')
                ->where(['entries.account_typeId' => 8, 'entries.particular_id' => $particular_id, 'entries.client_id' => $client_id])
                ->get()->getRow();

            if (!$sharesTransactions || !$sharesTransactions->amount) {
                continue; // Skip this particular if no transactions
            }

            # Get client shares for this particular
            $clientShares = $this->entry->select('
            SUM(CASE WHEN LOWER(entries.status) = "debit" THEN entries.amount ELSE 0 END) as sharesDebit,
            SUM(CASE WHEN LOWER(entries.status) = "credit" THEN entries.amount ELSE 0 END) as sharesCredit')
                ->where(['entries.account_typeId' => 8, 'entries.client_id' => $client_id, 'entries.particular_id' => $particular_id])
                ->get()
                ->getRow();

            if ($clientShares) {
                $clientSharesParticularPurchases = (float)$clientShares->sharesCredit;
                $clientSharesParticularWithdrawals = (float)$clientShares->sharesDebit;
                $clientSharesParticularBalance = ($clientSharesParticularPurchases - $clientSharesParticularWithdrawals);

                if ($clientSharesParticularBalance >! 0) {
                    continue;
                }

                # Lookup charge for this particular
                $chargeRow = $this->vlookupSharesCharge($particular_id, $clientRow['reg_date']);
                if ($chargeRow) {
                    $particular['clientSharesBalance'] = $clientSharesParticularBalance;
                    $particular['clientSharesBalanceUnits'] = round(($clientSharesParticularBalance / $chargeRow['charge']), 2);
                } else {
                    $particular['clientSharesBalance'] = $clientSharesParticularBalance;
                    $particular['clientSharesBalanceUnits'] = null;
                }
            } else {
                $particular['clientSharesBalance'] = null;
                $particular['clientSharesBalanceUnits'] = null;
            }

            $data [] = $particular;
        }
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'No Shares found for client!',
            ];
            return $this->respond($response);
            exit;
        }
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
        //
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        //
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
        //
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
        //
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
        //
    }
}
