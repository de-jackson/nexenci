<?php

use CodeIgniter\Router\RouteCollection;

use App\Controllers\Api\BaseController as BaseController;

/**
 * @var RouteCollection $routes
 */
# $routes->get('/', 'Home::index');

$routes->set404Override(function ($message = null) {
    return view('admin/dashboard/errors', [
        'title' => 'Error!',
        'menu' => 404,
        'message' => $message,
    ]);
});

$routes->get('/admin', 'Admin\Login::index');
$routes->get('/', 'Client\Auth::index');
# admin log out
$routes->get('logout', 'Admin\Dashboard::logout');
// general table counter badges
$routes->get('counter/(:any)', '\App\Controllers\MasterController::json_counter/$1');

$routes->group("admin", ["namespace" => "App\Controllers\Admin", 'filter' => 'noauth'], function ($routes) {
    # admin login
    $routes->match(['get', 'post'], 'login', 'Login::index');
    # admin authentication
    $routes->post('account/authentication', 'Login::authentication');
    # admin forgot password
    $routes->get('account/password', 'Password::index');
    # admin authentication
    $routes->post('account/password/recovery', 'Password::new');
    # admin reset new password
    $routes->match(['get', 'post'], 'account/password/reset/(:any)', 'Password::reset/$1/$2/$3');
    # save newly created admin password
    $routes->post('account/password/create', 'Password::create');
    # two step or factor authentication
    $routes->get('account/password/verify', 'Password::verify');
    # send the user token otp code
    $routes->post('account/password/account', 'Password::sendTokenCode');
    # ask the user to enter the otp code
    $routes->get('account/password/code', 'Password::code');
    # authenticate the user otp code
    $routes->post('account/password/authenticate', 'Password::authCode');
    # clear session variable for 2nd step verification
    $routes->get('account/password/sessions', 'Password::clearUserSession');

    # Registration for company
    $routes->get('account/register', 'Register::index');
    $routes->post('account/register', 'Register::create');
    $routes->post('account/signup', 'Register::register');
    $routes->get('fetch-utilities/(:any)', '\App\Controllers\Microfinance\BaseController::fetchUtilities');
});

$routes->group("nexen", ["namespace" => "App\Controllers\Nexen", 'filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->resource('clients');
    $routes->resource('blog/categories');
    $routes->post('blog/categories/(:any)', 'Blog\Categories::update/$1');
    $routes->post('blog/category/bulk-delete', 'Blog\Categories::bulkDelete');
    $routes->resource('blog/blogs');
    $routes->post('blog/blog/bulk-delete-posts', 'Blog\Blogs::bulkDelete');
});

$routes->group("api/v1/", ["namespace" => "App\Controllers\Api\Nexen", 'filter' => 'noauth'], function ($routes) {
    $routes->resource('blogs');
    $routes->get('blog/category/(:any)', 'Blogs::category/$1');
    $routes->get('blog/categories', 'Blogs::blogs');
});

# run auto updates
$routes->match(['get', 'post'],'admin/auto-update/(:any)', '\App\Controllers\MasterController::run_auto_updates/$1');
# routes for clients to access the dashboard with all the modules
$routes->group("admin", ["namespace" => "App\Controllers\Admin", 'filter' => 'auth'], function ($routes) {
    /** routes for dashboard controller */
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('profile', 'Dashboard::profile');
    $routes->get('profile/search-logs', 'Dashboard::search_logs');
    $routes->get('profile/search-activities', 'Dashboard::search_activities');
    $routes->post('profile/change-password', 'Dashboard::change_password');
    $routes->post('profile/two-factor-auth/(:any)', 'Dashboard::two_factorAuth/$1');


    # fetch various history data
    $routes->post('fetch-history', '\App\Controllers\MasterController::fetch_history');

    /** routes for emails */
    $routes->resource('communications/emails');
    $routes->post('mails/update-mail/(:any)', 'Communications\Emails::update_status/$1');
    $routes->get('mails/recipients/(:any)', 'Communications\Emails::fetch_mailAddresses/$1');
    $routes->get('mails/fetch-mails/(:any)', 'Communications\Emails::fetch_mails/$1');
    $routes->post('mails/bulk-mailUpdate', 'Communications\Emails::ajax_bulky_change');
    $routes->get('attachments/download/(:any)', 'Communications\Emails::download/$1');

    /** routes for menu */
    $routes->resource('menu');
    $routes->post('edit-menu/(:any)', 'Menu::update_menu/$1');
    $routes->get('menus/info/(:any)', 'Menu::menu_view/$1');
    $routes->get('menus/get-menu-list/(:any)', 'Menu::menu_list/$1');
    $routes->get('menus/search-menu/(:any)', 'Menu::search_menu/$1');
    $routes->get('menus/menu-permissions', 'Menu::allowed_menus');
    $routes->post('menus/bulk-deleteMenu', 'Menu::ajax_bulky_delete');
    $routes->get('menus/child-menu/(:any)', 'Menu::childMenus/$1');

    /** routes for users controller */
    $routes->resource('user');
    $routes->get('user-pages/(:any)', 'User::user_views/$1');
    $routes->get('generate-users', 'User::users_list');
    $routes->get('user-Forms/(:any)', 'User::user_forms/$1');
    $routes->post('store', 'User::store');
    $routes->post('update-userStatus/(:any)', 'User::update_userStatus/$1');
    $routes->post('permissions', 'User::user_permissions');
    $routes->post('bulk-delete-user', 'User::bulkDeleteUser');

    // user logs routes
    $routes->get('generate-logs', 'User::logs_list');
    $routes->get('logs-report/(:any)', 'User::logs_report/$1/$2');
    $routes->get('log/(:num)', 'User::show_log/$1');
    $routes->post('delete-log/(:num)', 'User::delete_log/$1');
    $routes->post('logsBulk-delete', 'User::bulkDeleteLogs');

    // activity log routes
    $routes->get('generate-activity', 'User::activity_list');
    $routes->get('activity-report/(:any)', 'User::activity_report/$1/$2');
    $routes->get('activity/(:num)', 'User::show_activity/$1');
    $routes->post('delete-activity/(:num)', 'User::delete_activity/$1');
    $routes->post('activityBulk-delete', 'User::bulkDeleteActivity');

    /** routes for settings controller */
    $routes->resource('company/settings');
    $routes->post('company/edit-settings/(:any)', 'Company\Settings::update_settings/$1');
    $routes->get('settings/get-currencies', 'Company\Settings::get_currencies');
    $routes->get('settings/print-settingsForm/(:num)', 'Company\Settings::settings_formPDF/$1');
    $routes->post('settings/bulk-delete', 'Company\Settings::ajax_bulky_delete');

    /** routes for branch controller */
    $routes->resource('company/branch');
    $routes->post('company/edit-branch/(:any)', 'Company\Branch::update_branch/$1');
    $routes->get('branch/info/(:any)', 'Company\Branch::branch_view/$1');
    $routes->get('branches/generate-branches/(:any)', 'Company\Branch::branches_list/$1');
    $routes->get('branches/branches-report/(:any)', 'Company\Branch::branches_report/$1/$2');
    $routes->get('branches/get-branches', 'Company\Branch::getBranches');
    $routes->post('branches/bulk-delete', 'Company\Branch::ajax_bulky_delete');
    $routes->get('branches/print-branchForm/(:num)', 'Company\Branch::branch_formPDF/$1');

    /** routes for department controller */
    $routes->resource('company/department');
    $routes->post('company/edit-department/(:any)', 'Company\Department::update_department/$1');
    $routes->get('department/info/(:any)', 'Company\Department::department_view/$1');
    $routes->get('departments/generate-departments/(:any)', 'Company\Department::departments_list/$1');
    $routes->post('departments/departmentsBulk-delete', 'Company\Department::ajax_bulky_delete');
    $routes->post('departments/all-departments', 'Company\Department::getDepartments');
    $routes->get('departments/departmentform/(:num)', 'Company\Department::department_forms/$1');

    /** routes for position controller */
    $routes->resource('company/position');
    $routes->post('company/edit-position/(:any)', 'Company\Position::update_position/$1');
    $routes->get('position/info/(:any)', 'Company\Position::position_view/$1');
    $routes->get('positions/generate-positions/(:any)', 'Company\Position::positions_list/$1');
    $routes->post('positions/positionsBulk-delete', 'Company\Position::ajax_bulky_delete');
    $routes->post('positions/all-positions', 'Company\Position::getPositions');
    $routes->post('positions/department-positions/(:num)', 'Company\Position::departmentPositions/$1');
    $routes->get('positions/positionform/(:num)', 'Company\Position::position_forms/$1');

    /** routes for administrator controller */
    $routes->resource('staff/administrator');
    $routes->post('staff/edit-administrator/(:any)', 'Staff\Administrator::update_administrator/$1');
    $routes->get('staff/info/(:any)', 'Staff\Administrator::admin_view/$1');
    $routes->get('staff/generate-admins/(:any)', 'Staff\Administrator::administrators_list/$1');
    $routes->post('staff/administratorBulk-delete', 'Staff\Administrator::ajax_bulky_delete');
    $routes->post('staff/update-staffStatus/(:any)', 'Staff\Administrator::update_staffStatus/$1');
    $routes->get('staff/staffs-report/(:any)', 'Staff\Administrator::staff_report/$1/$2/$3/$4');

    /** routes for employees controller */
    $routes->resource('staff/employee');
    $routes->post('staff/edit-employee/(:any)', 'Staff\Employee::update_employee/$1');
    $routes->get('staff/generate-employees/(:any)', 'Staff\Employee::employees_list/$1');
    $routes->post('staff/bulk-deleteEmployees', 'Staff\Employee::ajax_bulky_delete');
    $routes->get('staff/form/(:any)', 'Staff\Employee::staff_forms/$1');

    /** routes for clients controller */
    $routes->resource('clients/client');
    $routes->post('clients/edit-client/(:any)', 'Clients\Client::update_client/$1');
    $routes->get('client/info/(:any)', 'Clients\Client::client_view/$1');
    $routes->get('clients/generate-clients/(:any)', 'Clients\Client::clients_list/$1');
    $routes->get('clients/clients-report/(:any)', 'Clients\Client::clients_report/$1/$2/$3/$4/$5');
    $routes->post('clients/update-clientStatus/(:any)', 'Clients\Client::update_clientStatus/$1');
    $routes->post('clients/bulk-delete', 'Clients\Client::ajax_bulky_delete');
    $routes->get('clients/all-clients', 'Clients\Client::getClients');
    $routes->get('clients/form/(:num)', 'Clients\Client::client_forms/$1');
    $routes->post('clients/submit-savings', 'Clients\Client::add_clientSaving');

    /** routes for category controller */
    $routes->resource('accounts/categories');
    $routes->post('accounts/edit-chartofaccount/(:any)', 'Accounts\Categories::update_category/$1');
    $routes->get('category/info/(:any)', 'Accounts\Categories::category_view/$1');
    $routes->get('accounts/generate-list/(:any)', 'Accounts\Categories::load_lists/$1/$2');
    $routes->post('accounts/categoriesBulk-delete', 'Accounts\Categories::ajax_bulky_delete');
    $routes->get('accounts/all-categories', 'Accounts\Categories::getCategories');
    $routes->get('accounts/categories-statement/(:any)', 'Accounts\Categories::statement_categories/$1');
    $routes->get('accounts/categoriesform/(:num)', 'Accounts\Categories::category_forms/$1');

    /** routes for subcategory controller */
    $routes->resource('accounts/subcategory');
    $routes->post('accounts/edit-subcategory/(:any)', 'Accounts\Subcategory::update_subcategory/$1');
    $routes->get('subcategory/info/(:any)', 'Accounts\Subcategory::subcategory_view/$1');
    $routes->get('accounts/generate-subcategories/(:any)', 'Accounts\Subcategory::subcategories_list/$1');
    $routes->post('accounts/subCategoryBulk-delete', 'Accounts\Subcategory::ajax_bulky_delete');
    $routes->get('accounts/all-subcategories', 'Accounts\Subcategory::getSubCategories');
    $routes->post('accounts/revenueSubcategories', 'Accounts\Subcategory::revenueSubcategories');
    $routes->post('accounts/subcategories/(:num)', 'Accounts\Subcategory::catSubcategories/$1');
    $routes->get('accounts/subcategoryform/(:num)', 'Accounts\Subcategory::subcategory_forms/$1');

    /** routes for particulars controller */
    $routes->resource('accounts/particular');
    $routes->post('accounts/edit-particular/(:any)', 'Accounts\Particular::update_particular/$1');
    $routes->get('particular/info/(:any)', 'Accounts\Particular::particular_view/$1');
    $routes->get('accounts/generate-particulars/(:any)', 'Accounts\Particular::particulars_list/$1/');
    $routes->post('accounts/particularBulk-delete', 'Accounts\Particular::ajax_bulky_delete');
    $routes->get('accounts/all-particulars', 'Accounts\Particular::getParticulars');
    $routes->get('accounts/category-account-types/(:any)', 'Accounts\Particular::getCategoryAccountTypes/$1/$2');
    $routes->get('accounts/account-types-by-part/(:any)', 'Accounts\Particular::account_types_by_part/$1');
    $routes->get('accounts/cash-flow-types', 'Accounts\Particular::getParticularCashFlowType');
    $routes->get('accounts/charge-options/(:any)', 'Accounts\Particular::particular_chargeOptions/$1');
    $routes->get('accounts/accountType-particulars/(:any)', 'Accounts\Particular::accountType_particulars/$1');
    $routes->get('accounts/payment-method', 'Accounts\Particular::paymet_methods');
    $routes->post('accounts/getSubParticulars/(:num)', 'Accounts\Particular::subParticulars/$1');
    $routes->get('accounts/particularform/(:num)', 'Accounts\Particular::particular_forms/$1');

    /** routes for loan products controller */
    $routes->resource('loans/product');
    $routes->post('loans/edit-product/(:any)', 'Loans\Product::update_product/$1');
    $routes->get('product/info/(:any)', 'Loans\Product::product_view/$1');
    $routes->get('loans/generate-products/(:any)', 'Loans\Product::products_list/$1');
    $routes->get('loans/products-report/(:any)', 'Loans\Product::products_report/$1/$2');
    $routes->post('loans/bulk-delete', 'Loans\Product::ajax_bulky_delete');
    $routes->get('loans/all-products', 'Loans\Product::getProducts');
    $routes->get('loans/productform/(:num)', 'Loans\Product::product_forms/$1');

    /** routes for loan applications controller */
    $routes->resource('loans/application');
    $routes->post('loans/edit-application/(:any)', 'Loans\Application::update_application/$1');
    $routes->get('application/info/(:any)', 'Loans\Application::view_applicant/$1');
    $routes->get('loans/generate-loanApplications/(:alpha)', 'Loans\Application::applications_list/$1');
    $routes->get('loans/applications-report/(:any)', 'Loans\Application::applications_report/$1/$2/$3/$4/$5/$6');
    $routes->post('loans/applicationBulk-delete', 'Loans\Application::ajax_bulky_delete');
    $routes->post('loans/applicationPayments', 'Loans\Application::addApplication_payments');
    $routes->post('application/payables', 'Loans\Application::application_particularsPayables');
    $routes->post('loans/all-applications', 'Loans\Application::getApplications');
    $routes->get('loans/applicationform/(:num)', 'Loans\Application::application_forms/$1');
    $routes->get('loans/applicationactionform/(:alpha)/(:num)', 'Loans\Application::actions_forms/$1/$2');
    $routes->get('loans/pendingApplications-clients', 'Loans\Application::pendingApplications_clients');
    $routes->get('loans/pending-applications/(:num)', 'Loans\Application::client_pendingApplications/$1');
    // application files 
    $routes->get('loans/getApplicationfiles/(:any)', 'Loans\Application::applicationFiles/$1/$2');
    $routes->get('loans/view-file/(:num)', 'Loans\Application::show_file/$1');
    $routes->post('loans/save-applicationFiles/(:any)', 'Loans\Application::new_file/$1');
    $routes->get('loan/application/files/download/(:num)', 'Loans\Application::download/$1');
    $routes->post('files/delete-file/(:num)', 'Loans\Application::delete_file/$1');
    $routes->post('files/fileBulk-delete', 'Loans\Application::ajax_file_bulky_delete');
    // application status n remarks
    $routes->get('loans/get-applicationRemarks/(:any)', 'Loans\Application::getApplicationRemarks/$1');
    $routes->get('applications/get-remark/(:num)', 'Loans\Application::show_remark/$1');
    $routes->get('applications/get-actions', 'Loans\Application::get_applicationActions');
    $routes->get('applications/get-levels', 'Loans\Application::get_applicationLevels');
    $routes->post('loans/application-status', 'Loans\Application::application_status');
    $routes->post('applications/save-remark', 'Loans\Application::save_remark');
    $routes->post('applications/edit-remark', 'Loans\Application::edit_remark');
    $routes->post('remarks/delete-remark/(:num)', 'Loans\Application::delete_remark/$1');
    $routes->post('remarks/bulk-deleteRemarks', 'Loans\Application::ajax_bulky_deleteRemarks');

    /** routes for loan disbursements controller */
    $routes->resource('loans/disbursement');
    $routes->post('loans/edit-disbursement/(:any)', 'Loans\Disbursement::update_disbursement/$1');
    $routes->get('loans/generate-disbursements/(:alpha)', 'Loans\Disbursement::disbursements_list/$1');
    $routes->get('loans/disbursements-report/(:any)', 'Loans\Disbursement::disbursements_report/$1/$2/$3/$4/$5/$6');
    $routes->post('loans/disbursementBulk-delete', 'Loans\Disbursement::ajax_bulky_delete');
    $routes->post('loans/get-disbursement', 'Loans\Disbursement::getDisbursements');
    $routes->get('disbursement/info/(:any)', 'Loans\Disbursement::view_disbursement/$1');
    $routes->get('loans/disbursementform/(:num)', 'Loans\Disbursement::disbursement_forms/$1');
    $routes->post('loans/disbursementPayments', 'Loans\Disbursement::disbursement_payments');
    $routes->get('loans/pendingDisbursements-clients', 'Loans\Disbursement::pendingDisbursements_clients');
    $routes->get('loans/pending-disbursements/(:num)', 'Loans\Disbursement::client_pendingDisbursements/$1');
    $routes->get('loan/agreement/(:any)', 'Loans\Disbursement::loan_agreement/$1/$2');

    /** routes for products controller */
    $routes->resource('products/product');
    $routes->get('products/type/(:any)', 'Products\Product::product_types/$1');
    $routes->get('products/generate-products/(:any)', 'Products\Product::products_list/$1/$2');
    $routes->post('products/edit-product/(:any)', 'Products\Product::update_product/$1');
    $routes->get('product/info/(:any)', 'Products\Product::product_view/$1');
    $routes->get('products/client-products/(:any)', 'Products\Product::get_clientProducts/$1');
    $routes->get('products/products-report/(:any)', 'Products\Product::products_report/$1/$2');
    $routes->post('products/bulk-delete', 'Products\Product::ajax_bulky_delete');
    $routes->get('products/all-products/(:any)', 'Products\Product::getProducts/$1');
    $routes->get('products/productform/(:num)', 'Products\Product::product_forms/$1/$2');
    
    // routes for shares
    $routes->resource('shares/share');
    $routes->get('shares/type/(:alpha)', 'Shares\Share::shares_type/$1');
    $routes->get('shares/shares-clients', 'Shares\Share::clients_withShares');
    $routes->get('shares/client-shares/(:num)', 'Shares\Share::client_sharesParticulars/$1');
 

    // routes for transactions
    $routes->resource('transactions/transaction');
    $routes->post('transactions/edit-transaction/(:any)', 'Transactions\Transaction::update_transaction/$1');
    $routes->get('transactions/type/(:alpha)', 'Transactions\Transaction::view_transations/$1');
    $routes->get('transactions/transactionform/(:any)', 'Transactions\Transaction::transaction_forms/$1');
    $routes->get('transactions/generate-transactions/(:any)', 'Transactions\Transaction::transactions_list/$1');
    $routes->get('transactions/client-transactions/(:any)', 'Transactions\Transaction::client_transactions/$1/$2');
    $routes->get('transactions/transactions-report/(:any)', 'Transactions\Transaction::transactions_report/$1/$2/$3/$4/$5');
    $routes->get('transactions/get-transaction/(:any)', 'Transactions\Transaction::ref_transactionList/$1');
    $routes->get('transaction/info/(:any)', 'Transactions\Transaction::refID_transaction/$1');
    $routes->get('transactions/receipt/(:any)', 'Transactions\Transaction::generate_receipt/$1');
    $routes->post('transactions/transactionsBulk-delete', 'Transactions\Transaction::ajax_bulky_delete');
    $routes->get('transactions/transaction_form/(:num)', 'Transactions\Transaction::transaction_form/$1');
    $routes->get('transactions/applicant-transactions/(:num)', 'Transactions\Transaction::applicant_transactions/$1');
    $routes->post('transactions/transaction-types/(:any)', 'Transactions\Transaction::account_entry_types/$1');
    $routes->get('transactions/type-info/(:any)', 'Transactions\Transaction::entry_type_info/$1');
    $routes->get('transactions/applicant-payments/(:any)', 'Transactions\Transaction::applicant_particularPayments/$1');
    $routes->get('transactions/repayment-history/(:num)', 'Transactions\Transaction::disbursement_transactions/$1');
    $routes->get('transactions/entryMenu-transactions/(:any)', 'Transactions\Transaction::entryMenu_transactions/$1');

    /** routes for statements controller */
    $routes->resource('statements/statement');
    $routes->get('statements/view-statement/(:any)', 'Statements\Statement::statement_view/$1');
    $routes->get('statements/particular-statement/(:any)', 'Statements\Statement::particular_statement/$1/$2/$3');
    $routes->get('statements/generate-statement', 'Statements\Statement::generate_statement');

    $routes->get('statements/particular-entries', 'Statements\Statement::particular_entries');
    $routes->get('statements/export-statement/(:any)', 'Statements\Statement::export_statement/$1');
    $routes->get('statements/particular-statementPDF/(:any)', 'Statements\Statement::particular_statementPDF/$1');
    $routes->get('statements/financial-years', 'Statements\Statement::financial_years');
    $routes->get('statements/get-quarters', 'Statements\Statement::generate_quarters');
    $routes->get('statements/entryYears', 'Statements\Statement::entry_years');
    $routes->get('statements/entryMonths/(:num)', 'Statements\Statement::entry_months/$1');

    /** routes for reports controller */
    $routes->resource('reports/report');
    $routes->match(['get', 'post'], 'reports/view/(:any)', 'Reports\Report::report_view/$1');
    $routes->post('reports/filter-options/(:any)', 'Reports\Report::filter_options/$1/$2');
    $routes->post('reports/monthly-transactions-report/(:any)', 'Reports\Report::monthlyTransactionsTotal/$1');

    # admin module report routes
    $routes->match(['get', 'post'], 'reports/module/report/(:any)', 'Reports\Modules::reportViewType/$1');

    $routes->match(['get', 'post'], 'reports/module/years-of-entries/(:num)', 'Reports\Modules::getEntryYears/$1');
    /*
    Enable this incase you would like to manually set routes for every report
    
    $routes->match(['get', 'post'],'/reports/module/generate-savings-report', 'Reports\Modules::savingsReport');
    
    $routes->match(['get', 'post'],'/reports/module/generate-clients-report', 'Reports\Modules::clientsReport');
    
    $routes->match(['get', 'post'],'/reports/module/generate-staff-report', 'Reports\Modules::staffAccountsReport');
    */
    $routes->match(['get', 'post'], 'reports/module/types/(:any)', 'Reports\Modules::types/$1');

    $routes->match(['get', 'post'], 'reports/module/get-report-type/(:any)', 'Reports\Modules::getReportType/$1');

    # charges as per particulars
    $routes->resource('settings/charges');
    # fetch the charges information
    $routes->get('settings/get-charges-info/(:any)', 'Settings\Charges::charges/$1/$2');
    # bulk delete the charges information
    $routes->post('settings/bulk-delete-charges', 'Settings\Charges::bulkDelete');
    # Client membership charges
    $routes->post('settings/client/charges', 'Settings\Charges::getClientMembershipCharge');

    # fetch various history data
    $routes->post('client/history', '\App\Controllers\Microfinance\BaseController::fetchHistory');
});


# routes for clients, to login, forgot password, reset password
$routes->group("client", ["namespace" => "App\Controllers\Client", 'filter' => 'noauthclient'], function ($routes) {
    # Client login
    $routes->match(['get', 'post'], 'login', 'Auth::index');
    # Client authentication
    $routes->post('account/authentication', 'Auth::authentication');
    # Client forgot password
    $routes->match(['get', 'post'], 'account/password', 'Auth::email');
    # Client authentication
    $routes->post('account/password/recovery', 'Auth::new');
    # Client reset new password
    $routes->match(['get', 'post'], 'account/password/reset/(:num)/(:any)/(:any)', 'Auth::reset/$1/$2/$3');
    # forgot password
    $routes->get('account/client/verify', 'Account::index');
    # save the new client password
    $routes->post('account/password/create', 'Auth::password');
    # registration
    $routes->resource('register');
    # two step or factor authentication
    $routes->get('account/verify', 'Account::index');
    # send the client token otp code
    $routes->post('account/token/sendcode', 'Account::sendTokenCode');
    # ask the client to enter the otp code
    $routes->get('account/token/verification', 'Account::code');
    # authenticate the client otp code
    $routes->post('account/token/authentication', 'Account::authCode');
    # clear session variable for 2nd step verification
    $routes->get('account/clear/sessions', 'Account::clearClientSession');
});


# routes for clients to access the dashboard with all the modules
$routes->group("client", ["namespace" => "App\Controllers\Client", 'filter' => 'authclient'], function ($routes) {
    # Client dashboard
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('profile', 'Dashboard::profile');
    $routes->get('logout', 'Dashboard::logout');
    $routes->post('profile/change-password', 'Dashboard::change_password');
    $routes->post('profile/two-factor-auth/(:any)', 'Dashboard::two_factorAuth/$1');
    # Branches
    $routes->resource('branches');
    $routes->get('company/branches/generate-branches/(:any)', 'Branches::branchesList/$1');
    $routes->get('company/branches/print/(:num)', 'Branches::printExport/$1');

    $routes->post('company/edit-branch/(:any)', 'Branches::update_branch/$1');
    $routes->get('company/branches/info/(:any)', 'Branches::branch_view/$1');

    $routes->get('company/branches/branches-report/(:any)', 'Branches::branches_report/$1/$2');
    $routes->get('company/branches/get-branches', 'Branches::getBranches');
    $routes->post('company/branches/bulk-delete', 'Branches::ajax_bulky_delete');

    # Reports
    $routes->match(['get', 'post'], 'reports/module/(:any)', 'Reports::module/$1');
    $routes->match(['get', 'post'], 'reports/years-of-entries/(:num)', 'Reports::getEntryYears/$1');
    $routes->match(['get', 'post'], 'reports/transactions/(:any)', 'Reports::getEntryTransactions/$1');
    $routes->match(['get', 'post'], 'reports/types/(:any)', 'Reports::types/$1');
    $routes->match(['get', 'post'], 'reports/view/report/(:any)', 'Reports::getReportType/$1');
    
    // Clients
    $routes->resource('clients');
    $routes->post('my-history', 'Clients::fetchHistory');
    $routes->match(['get', 'post'], 'module-particulars/(:any)', 'Clients::get_particularsByModule/$1/$2');
    $routes->post('charges', 'Clients::getClientMembershipCharge');

    # Transactions
    $routes->resource('transactions');
    $routes->match(['get', 'post'], 'get-transactions/(:any)', 'Transactions::module/$1');
    $routes->post('transactions/create', 'Transactions::create');
    $routes->match(['get', 'post'], 'transactions/payment', 'Transactions::payment');
    $routes->get('transactions/entries/(:num)', 'Transactions::getEntriesByApplicationId/$1');
    $routes->get('get-myTransactions/(:any)', 'Transactions::transactions_list/$1/$2');
    $routes->get('my-transactions', 'Transactions::client_transactions');
    
    # Loans
    $routes->match(['get', 'post'], 'loans/module/(:any)', 'Loans::module/$1');
    $routes->resource('applications');
    $routes->post('loan/application/store/(:any)', 'Applications::store/$1');
    $routes->post('loan/application/cancel/(:any)', 'Applications::cancelLoanApplication/$1');
    $routes->get('application/info/(:any)', 'Applications::view_applicant/$1');
    $routes->get('application/files/(:any)', 'Applications::getApplicationFilesAttachments/$1/$2');
    
    # Disbursements
    $routes->resource('disbursements');
    $routes->match(['get', 'post'], 'loans/type/(:any)/(:any)', 'Loans::type/$1/$2');
    $routes->get('disbursement/info/(:num)', 'Disbursements::viewDisbursementInfo/$1');

    $routes->resource('products');

    # Shares
    $routes->resource('shares');
    $routes->get('shares-module/(:alpha)', 'Shares::module/$1');
    $routes->get('shares-clients', 'Shares::clients_withShares');
    $routes->get('my-shares/(:num)', 'Shares::client_sharesParticulars/$1');

});

# Business information
$routes->get('api/client/business', 'Api\BaseController::index');

# routes for clients, to login, forgot password, reset password
$routes->group("api/client", ["namespace" => "App\Controllers\Api\Client", 'filter' => 'NoAuthClientApi'], function ($routes) {
    # Authentication
    $routes->resource('auth');

    # Client login
    $routes->match(['get', 'post'], 'login', 'Auth::index');
    # Client authentication
    $routes->post('account/authentication', 'Auth::create');
    # Client forgot password
    $routes->match(['get', 'post'], 'account/password', 'Auth::email');
    # Client authentication
    $routes->post('account/password/recovery', 'Auth::new');
    # Client reset new password
    $routes->match(['get', 'post'], 'account/password/reset/(:num)/(:any)/(:any)', 'Auth::reset/$1/$2/$3');
    # save the new client password
    $routes->post('account/password/create', 'Auth::password');

    # registration
    $routes->resource('register');
    # two step or factor authentication
    $routes->get('account/verify', 'Account::index');
    # send the client token otp code
    $routes->post('account/token/sendcode', 'Account::sendTokenCode');
    # ask the client to enter the otp code
    $routes->get('account/token/verification', 'Account::code');
    # authenticate the client otp code
    $routes->post('account/token/authentication', 'Account::authCode');
    # clear session variable for 2nd step verification
    $routes->get('account/clear/sessions', 'Account::clearClientSession');
});


# routes for clients to access the dashboard with all the modules
$routes->group("api/client", ["namespace" => "App\Controllers\Api\Client", 'filter' => 'AuthClientApi'], function ($routes) {
    # Client dashboard
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('profile', 'Dashboard::profile');
    $routes->get('logout', 'Dashboard::logout');
    # Branches
    $routes->resource('branches');
    $routes->get('company/branches/generate-branches/(:any)', 'Branches::branchesList/$1');
    $routes->get('company/branches/print/(:num)', 'Branches::printExport/$1');

    $routes->post('company/edit-branch/(:any)', 'Branches::update_branch/$1');
    $routes->get('company/branches/info/(:any)', 'Branches::branch_view/$1');

    $routes->get('company/branches/branches-report/(:any)', 'Branches::branches_report/$1/$2');
    $routes->get('company/branches/get-branches', 'Branches::getBranches');
    $routes->post('company/branches/bulk-delete', 'Branches::ajax_bulky_delete');

    # Reports
    $routes->match(['get', 'post'], 'reports/module/(:any)', 'Reports::module/$1');
    $routes->match(['get', 'post'], 'reports/years-of-entries/(:num)', 'Reports::getEntryYears/$1');
    $routes->match(['get', 'post'], 'reports/transactions/(:any)', 'Reports::getEntryTransactions/$1');
    $routes->match(['get', 'post'], 'reports/types/(:any)', 'Reports::types/$1');
    $routes->match(['get', 'post'], 'reports/view/report/(:any)', 'Reports::getReportType/$1');
    $routes->resource('clients');
    # Transactions
    $routes->match(['get', 'post'], 'transactions/type/(:any)', 'Transactions::module/$1');
    # Create transactions
    $routes->post('transactions/create', 'Transactions::create');
    $routes->match(['get', 'post'], 'transactions/payment', 'Transactions::payment');
    # Loans
    $routes->match(['get', 'post'], 'loans/module/(:any)', 'Loans::module/$1');
    $routes->resource('applications');
    $routes->post('loan/application/store/(:any)', 'Applications::store/$1');
    $routes->post('loan/application/cancel/(:any)', 'Applications::cancelLoanApplication/$1');
    $routes->get('application/info/(:any)', 'Applications::view_applicant/$1');
    $routes->get('transactions/entries/(:num)', 'Transactions::getEntriesByApplicationId/$1');
    $routes->get('application/files/(:any)', 'Applications::getApplicationFilesAttachments/$1/$2');
    # Disbursements
    $routes->resource('disbursements');
    $routes->match(['get', 'post'], 'loans/type/(:any)/(:any)', 'Loans::type/$1/$2');
    $routes->get('disbursement/info/(:num)', 'Disbursements::viewDisbursementInfo/$1');
});
