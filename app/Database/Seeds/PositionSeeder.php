<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use \App\Models\PositionModel;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $position = new PositionModel();
        $data = [
            [
                'department_id' => 1,
                'position' => 'Super',
                'position_slug' => 'super',
                'position_status' => 'Inactive',
                'permissions' => 's:3:"all";',
                'deleted_at' => date('Y-m-d H:i:s')
            ],
            [
                'department_id' => 1,
                'position' => 'Admin',
                'position_slug' => 'admin',
                'position_status' => 'Active',
                'permissions' => 's:3:"all";',
                'deleted_at' => date('Y-m-d H:i:s')
            ],
            [
                'department_id' => 1,
                'position' => 'Credit Officer',
                'position_slug' => 'credit-officer',
                'position_status' => 'Active',
                'permissions' => 'a:26:{i:0;s:13:"viewDashboard";i:1;s:13:"createClients";i:2;s:11:"viewClients";i:3;s:13:"updateClients";i:4;s:13:"exportClients";i:5;s:9:"viewLoans";i:6;s:14:"createProducts";i:7;s:14:"importProducts";i:8;s:12:"viewProducts";i:9;s:14:"updateProducts";i:10;s:14:"deleteProducts";i:11;s:14:"exportProducts";i:12;s:18:"createApplications";i:13;s:18:"importApplications";i:14;s:16:"viewApplications";i:15;s:18:"updateApplications";i:16;s:18:"deleteApplications";i:17;s:18:"exportApplications";i:18;s:17:"viewDisbursements";i:19;s:19:"exportDisbursements";i:20;s:18:"createApplications";i:21;s:18:"importApplications";i:22;s:16:"viewApplications";i:23;s:18:"updateApplications";i:24;s:18:"deleteApplications";i:25;s:18:"exportApplications";}',
                'deleted_at' => date('Y-m-d H:i:s')
            ],
            [
                'department_id' => 1,
                'position' => 'Supervisor',
                'position_slug' => 'supervisor',
                'position_status' => 'Active',
                'permissions' => 'a:26:{i:0;s:13:"viewDashboard";i:1;s:13:"createClients";i:2;s:11:"viewClients";i:3;s:13:"updateClients";i:4;s:13:"exportClients";i:5;s:9:"viewLoans";i:6;s:14:"createProducts";i:7;s:14:"importProducts";i:8;s:12:"viewProducts";i:9;s:14:"updateProducts";i:10;s:14:"deleteProducts";i:11;s:14:"exportProducts";i:12;s:18:"createApplications";i:13;s:18:"importApplications";i:14;s:16:"viewApplications";i:15;s:18:"updateApplications";i:16;s:18:"deleteApplications";i:17;s:18:"exportApplications";i:18;s:17:"viewDisbursements";i:19;s:19:"exportDisbursements";i:20;s:18:"createApplications";i:21;s:18:"importApplications";i:22;s:16:"viewApplications";i:23;s:18:"updateApplications";i:24;s:18:"deleteApplications";i:25;s:18:"exportApplications";}',
                'deleted_at' => date('Y-m-d H:i:s')
            ],
            [
                'department_id' => 1,
                'position' => 'Operations Officer',
                'position_slug' => 'credit-officer',
                'position_status' => 'Active',
                'permissions' => 'a:55:{i:0;s:13:"viewDashboard";i:1;s:11:"viewCompany";i:2;s:12:"viewSettings";i:3;s:12:"viewBranches";i:4;s:15:"viewDepartments";i:5;s:13:"viewPositions";i:6;s:14:"viewAccounting";i:7;s:19:"viewChartofAccounts";i:8;s:17:"viewSubcategories";i:9;s:15:"viewParticulars";i:10;s:10:"viewStaffs";i:11;s:18:"viewAdministrators";i:12;s:13:"viewEmployees";i:13;s:11:"viewClients";i:14;s:18:"viewUserManagement";i:15;s:9:"viewUsers";i:16;s:10:"viewLogins";i:17;s:12:"viewActivity";i:18;s:9:"viewLoans";i:19;s:12:"viewProducts";i:20;s:14:"updateProducts";i:21;s:18:"createApplications";i:22;s:18:"importApplications";i:23;s:16:"viewApplications";i:24;s:18:"updateApplications";i:25;s:17:"viewDisbursements";i:26;s:16:"viewTransactions";i:27;s:11:"viewSavings";i:28;s:14:"viewRepayments";i:29;s:16:"updateRepayments";i:30;s:16:"deleteRepayments";i:31;s:16:"exportRepayments";i:32;s:16:"viewApplications";i:33;s:18:"updateApplications";i:34;s:18:"deleteApplications";i:35;s:22:"bulkDeleteApplications";i:36;s:18:"exportApplications";i:37;s:14:"viewMembership";i:38;s:16:"exportMembership";i:39;s:14:"createExpenses";i:40;s:14:"importExpenses";i:41;s:12:"viewExpenses";i:42;s:14:"exportExpenses";i:43;s:16:"exportInvestment";i:44;s:14:"viewStatements";i:45;s:16:"viewBalanceSheet";i:46;s:18:"exportBalanceSheet";i:47;s:14:"viewProfitLoss";i:48;s:16:"exportProfitLoss";i:49;s:16:"viewTrialBalance";i:50;s:18:"exportTrialBalance";i:51;s:12:"viewCashFlow";i:52;s:14:"exportCashFlow";i:53;s:11:"viewReports";i:54;s:13:"exportReports";}',
                'deleted_at' => date('Y-m-d H:i:s')
            ],
            [
                'department_id' => 1,
                'position' => 'Accounts Officer',
                'position_slug' => 'accounts-officer',
                'position_status' => 'Active',
                'permissions' => 'a:101:{i:0;s:13:"viewDashboard";i:1;s:11:"viewCompany";i:2;s:12:"viewBranches";i:3;s:14:"exportBranches";i:4;s:15:"viewDepartments";i:5;s:17:"exportDepartments";i:6;s:15:"exportPositions";i:7;s:14:"viewAccounting";i:8;s:19:"viewChartofAccounts";i:9;s:21:"updateChartofAccounts";i:10;s:17:"viewSubcategories";i:11;s:19:"updateSubcategories";i:12;s:19:"exportSubcategories";i:13;s:15:"viewParticulars";i:14;s:17:"updateParticulars";i:15;s:17:"exportParticulars";i:16;s:10:"viewStaffs";i:17;s:18:"viewAdministrators";i:18;s:20:"exportAdministrators";i:19;s:13:"viewEmployees";i:20;s:15:"exportEmployees";i:21;s:11:"viewClients";i:22;s:13:"exportClients";i:23;s:18:"viewUserManagement";i:24;s:11:"exportUsers";i:25;s:12:"exportLogins";i:26;s:14:"exportActivity";i:27;s:9:"viewLoans";i:28;s:12:"viewProducts";i:29;s:14:"exportProducts";i:30;s:18:"createApplications";i:31;s:18:"importApplications";i:32;s:16:"viewApplications";i:33;s:18:"updateApplications";i:34;s:18:"deleteApplications";i:35;s:22:"bulkDeleteApplications";i:36;s:18:"exportApplications";i:37;s:19:"createDisbursements";i:38;s:17:"viewDisbursements";i:39;s:19:"exportDisbursements";i:40;s:16:"viewTransactions";i:41;s:13:"createSavings";i:42;s:13:"importSavings";i:43;s:11:"viewSavings";i:44;s:13:"updateSavings";i:45;s:13:"deleteSavings";i:46;s:17:"bulkDeleteSavings";i:47;s:13:"exportSavings";i:48;s:16:"createRepayments";i:49;s:16:"importRepayments";i:50;s:14:"viewRepayments";i:51;s:16:"updateRepayments";i:52;s:16:"deleteRepayments";i:53;s:20:"bulkDeleteRepayments";i:54;s:16:"exportRepayments";i:55;s:18:"createApplications";i:56;s:18:"importApplications";i:57;s:16:"viewApplications";i:58;s:18:"updateApplications";i:59;s:18:"deleteApplications";i:60;s:22:"bulkDeleteApplications";i:61;s:18:"exportApplications";i:62;s:16:"createMembership";i:63;s:16:"importMembership";i:64;s:14:"viewMembership";i:65;s:16:"updateMembership";i:66;s:16:"deleteMembership";i:67;s:20:"bulkDeleteMembership";i:68;s:16:"exportMembership";i:69;s:14:"createExpenses";i:70;s:14:"importExpenses";i:71;s:12:"viewExpenses";i:72;s:14:"updateExpenses";i:73;s:14:"deleteExpenses";i:74;s:18:"bulkDeleteExpenses";i:75;s:14:"exportExpenses";i:76;s:14:"createTransfer";i:77;s:14:"importTransfer";i:78;s:12:"viewTransfer";i:79;s:14:"updateTransfer";i:80;s:14:"deleteTransfer";i:81;s:18:"bulkDeleteTransfer";i:82;s:14:"exportTransfer";i:83;s:16:"createInvestment";i:84;s:16:"importInvestment";i:85;s:14:"viewInvestment";i:86;s:16:"updateInvestment";i:87;s:16:"deleteInvestment";i:88;s:20:"bulkDeleteInvestment";i:89;s:16:"exportInvestment";i:90;s:14:"viewStatements";i:91;s:16:"viewBalanceSheet";i:92;s:18:"exportBalanceSheet";i:93;s:14:"viewProfitLoss";i:94;s:16:"exportProfitLoss";i:95;s:16:"viewTrialBalance";i:96;s:18:"exportTrialBalance";i:97;s:12:"viewCashFlow";i:98;s:14:"exportCashFlow";i:99;s:11:"viewReports";i:100;s:13:"exportReports";}',
                'deleted_at' => date('Y-m-d H:i:s')
            ],
        ];
        $position->insertBatch($data);
    }
}
