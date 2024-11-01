<?php

namespace App\Controllers\Admin\Company;

use App\Controllers\MasterController;

use \Hermawan\DataTables\DataTable;

class Settings extends MasterController
{

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Company';
        $this->title = 'Settings';
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
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            return view('admin/company/settings/index', [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    function settings_formPDF($id)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem)) {
            $title = "Business Information Form";
            return view('admin/company/settings/settings_formPDF', [
                'title' => $title,
                'id' => $id,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
            ]);
        } else {
            session()->setFlashdata('failed', "Access Denied. You don't have permission to access " . $this->title . " page!");
            return redirect()->to(base_url('admin/dashboard'));
        }
    }
    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = $this->settings->select('settings.*, currencies.currency,  currencies.symbol')->join('currencies', 'currencies.id = settings.currency_id', 'left')->find($id);
        if ($data) {
            return $this->respond($data);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requsted ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
        }
    }
    // get currecny
    public function get_currencies()
    {
        $data = $this->currency->where(['status' => 'Active'])->findAll();
        return $this->respond($data);
    }
    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'create')) {
            $this->_validateSettings("add");
            $data = [
                'system_name' => $this->request->getVar('system_name'),
                'system_abbr' => $this->request->getVar('system_abbr'),
                'system_slogan' => $this->request->getVar('system_slogan'),
                'system_version' => $this->request->getVar('system_version'),
                'business_name' => $this->request->getVar('business_name'),
                'business_abbr' => $this->request->getVar('business_abbr'),
                'business_slogan' => $this->request->getVar('business_slogan'),
                'business_contact' => $this->request->getVar('business_contact'),
                'business_alt_contact' => $this->request->getVar('business_alt_contact'),
                'business_email' => $this->request->getVar('business_email'),
                'business_pobox' => $this->request->getVar('business_pobox'),
                'business_address' => $this->request->getVar('business_address'),
                'business_web' => $this->request->getVar('business_web'),
                'business_about' => $this->request->getVar('business_about'),
                'tax_rate' => $this->request->getVar('tax_rate'),
                'round_off' => $this->request->getVar('round_off'),
                'financial_year_start' => date('d-M', strtotime($this->request->getVar('financial_year_start'))),
                'currency_id' => $this->request->getVar('currency_id'),
                'sms' => ($this->request->getVar('sms')) ? $this->request->getVar('sms') : null,
                'email' => ($this->request->getVar('email')) ? $this->request->getVar('email') : null,
            ];
            if (!empty($_FILES['business_logo']['name'])) {
                $log = $this->upload_businessLog();
                $data['business_logo'] = $log;
            }
            $insert = $this->settings->insert($data);
            if ($insert) {
                // insert into activity logs
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'create',
                    'description' => ucfirst('created ' . $this->title . ' for ' . $data['business_name']),
                    'module' => $this->title,
                    'referrer_id' => $insert,
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => $this->title . ' created successfully',
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => $this->title . ' created successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status'   => 500,
                    'error'    => 'Create ' . $this->title . ' Failed',
                    'messages' => 'Creating ' . $this->title . ' failed, try again later!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to create ' . $this->title . '!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update_settings($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'update')) {
            if (isset($id)) {
                $this->_validateSettings("update");
                $settingsInfo = $this->settings->find($id);
                $data = [
                    'system_name' => $this->request->getVar('system_name'),
                    'system_abbr' => $this->request->getVar('system_abbr'),
                    'system_slogan' => $this->request->getVar('system_slogan'),
                    'system_version' => $this->request->getVar('system_version'),
                    'business_name' => $this->request->getVar('business_name'),
                    'business_abbr' => $this->request->getVar('business_abbr'),
                    'business_slogan' => $this->request->getVar('business_slogan'),
                    'business_contact' => trim($this->request->getVar('business_contact_full')),
                    'business_alt_contact' => trim($this->request->getVar('business_alt_contact_full')),
                    'business_email' => $this->request->getVar('business_email'),
                    'business_pobox' => $this->request->getVar('business_pobox'),
                    'business_address' => $this->request->getVar('business_address'),
                    'business_web' => $this->request->getVar('business_web'),
                    'business_about' => $this->request->getVar('business_about'),
                    'tax_rate' => $this->request->getVar('tax_rate'),
                    'round_off' => $this->request->getVar('round_off'),
                    'financial_year_start' => date('d-M', strtotime($this->request->getVar('financial_year_start'))),
                    'currency_id' => $this->request->getVar('currency_id'),
                    'sms' => ($this->request->getVar('sms')) ? $this->request->getVar('sms') : null,
                    'email' => ($this->request->getVar('email')) ? $this->request->getVar('email') : null,
                ];
                if (!empty($_FILES['business_logo']['name'])) {
                    if (file_exists('uploads/logo/' . $settingsInfo['business_logo']) && $settingsInfo['business_logo']) {
                        unlink('uploads/logo/' . $settingsInfo['business_logo']);
                    }
                    $log = $this->upload_businessLog();
                    $data['business_logo'] = $log;
                }
                $update = $this->settings->update($id, $data);
                if ($update) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'update',
                        'description' => ucfirst('updated ' . $this->title . ', for ' . $data['business_name']),
                        'module' => 'Settings',
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' updated successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' updated successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status'   => 500,
                        'error'    => 'Update ' . $this->title . ' Failed',
                        'messages' => 'Updating ' . $this->title . ' failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'Update Failed. Invalid ID provided, try again!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to update ' . $this->title . '!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'delete')) {
            $data = $this->settings->find($id);
            if ($data) {
                if (file_exists('uploads/logo/' . $data['business_logo']) && $data['business_logo']) {
                    unlink('uploads/logo/' . $data['business_logo']);
                }
                $delete = $this->settings->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', for ' . $data['business_name']),
                        'module' => 'Settings',
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record deleted successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' record deleted successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Delete Failed',
                        'messages' => 'Deleting ' . $this->title . ' failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 404,
                    'error' => 'Not Found',
                    'messages' => 'The requested ' . $this->title . ' resource could not be found!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . '!',
            ];
            return $this->respond($response);
            exit;
        }
    }
    public function ajax_bulky_delete()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            $list_id = $this->request->getVar('id');
            foreach ($list_id as $id) {
                $data = $this->settings->find($id);
                if ($data) {
                    if (file_exists('uploads/logo/' . $data['business_logo']) && $data['business_logo']) {
                        unlink('uploads/logo/' . $data['business_logo']);
                    }
                    $delete = $this->settings->delete($id);
                    if ($delete) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('bulk deleted ' . $this->title . ', for ' . $data['business_name']),
                            'module' => 'Settings',
                            'referrer_id' => $id,
                        ];
                        $activity = $this->insertActivityLog($activityData);
                    } else {
                        $response = [
                            'status'   => 500,
                            'error'    => 'Delete Failed',
                            'messages' => 'Deleting ' . $this->title . ' failed,  try again later!',
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 404,
                        'error' => 'Not Found',
                        'messages' => 'The requsted ' . $this->title . ' resource could not be found!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            }
            if ($activity) {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' record(s) deleted successfully',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' record(s) deleted successfully. loggingFailed'
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to delete ' . $this->title . '!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * upload business log
     */
    private function upload_businessLog()
    {
        $validationRule = [
            'business_logo' => [
                "rules" => "uploaded[business_logo]|max_size[business_logo,3072]|is_image[business_logo]|mime_in[business_logo,image/jpg,image/jpeg,image/png,image/webp]",
                "label" => "Profile Image",
                "errors" => [
                    'max_size' => 'The size of this image is too large. The image must have less than 3MBs size',
                    'mime_in' => 'Your upload does not have a valid image format',
                    'is_image' => 'Your file is not allowed! Please use an image!'
                ]
            ],
        ];
        if (!$this->validate($validationRule)) {
            $data['inputerror'][] = 'business_logo';
            $data['error_string'][] = $this->validator->getError("business_logo") . '!';
            $data['status'] = FALSE;
            echo json_encode($data);
            exit;
        }
        $file = $this->request->getFile('business_logo');
        $logo = $file->getName();
        # Renaming file before upload
        $temp = explode(".", $logo);
        $newfilename = round(microtime(true)) . '.' . end($temp);

        if ($file->move("uploads/logo/", $logo)) {
            return $logo;
        } else {
            $data['inputerror'][] = 'business_logo';
            $data['error_string'][] = "Failed to upload Logo!";
            $data['status'] = FALSE;
            echo json_encode($data);
            exit;
        }
    }

    /**
     * validate form inputs
     */
    private function _validateSettings($method)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $settingsInfo = $this->settings->find($this->request->getVar('id'));

        # trimmed the white space between between country code and phone number
        $business_contact = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('business_contact_country_code'),
            'phone' => $this->request->getVar('business_contact')
        ]);

        # trimmed the white space between between country code and phone number
        $business_alt_contact = $this->trimmedWhiteSpaceFromPhoneNumber([
            'country_code' => $this->request->getVar('business_alt_contact_country_code'),
            'phone' => $this->request->getVar('business_alt_contact')
        ]);

        $name = $this->request->getVar('system_name');
        if ($this->request->getVar('system_name') == '') {
            $data['inputerror'][] = 'system_name';
            $data['error_string'][] = 'System Name is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('system_name'))) {
            if ($this->settings->validateName($name) == TRUE) {
                if (strlen(trim($name)) < 5) {
                    $data['inputerror'][] = 'system_name';
                    $data['error_string'][] = 'Minimum length is 5 characters [' . strlen(trim($name)) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($name) == FALSE) {
                $data['inputerror'][] = 'system_name';
                $data['error_string'][] = 'Valid System Name is required!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('system_abbr') == '') {
            $data['inputerror'][] = 'system_abbr';
            $data['error_string'][] = 'System short name is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('system_abbr'))) {
            $abbr = $this->request->getVar('system_abbr');
            if ($this->settings->validateName($abbr) == TRUE) {
                if (strlen(trim($abbr)) < 2) {
                    $data['inputerror'][] = 'system_abbr';
                    $data['error_string'][] = 'Minimum length is 2 characters [' . strlen(trim($abbr)) . ']!';
                    $data['status'] = FALSE;
                }
                if (strlen(trim($abbr)) > 10) {
                    $data['inputerror'][] = 'system_abbr';
                    $data['error_string'][] = 'Maximum length is 10 characters [' . strlen(trim($abbr)) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($abbr) == FALSE) {
                $data['inputerror'][] = 'system_abbr';
                $data['error_string'][] = 'Valid system Short Name is required!';
                $data['status'] = FALSE;
            }
        }
        /** sys slogan validation */
        /*
        if ($this->request->getVar('system_slogan') == '') {
            $data['inputerror'][] = 'system_slogan';
            $data['error_string'][] = 'System Slogan is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('system_slogan'))) {
            $slogan = $this->request->getVar('system_slogan');
            if ($this->settings->validateName($slogan) == TRUE) {
                if (strlen(trim($slogan)) < 5) {
                    $data['inputerror'][] = 'system_slogan';
                    $data['error_string'][] = 'Minimum length is 5 characters [' . strlen(trim($slogan)) . ']!';
                    $data['status'] = FALSE;
                }
                if (strlen(trim($slogan)) > 50) {
                    $data['inputerror'][] = 'system_slogan';
                    $data['error_string'][] = 'Maximum length is 50 characters [' . strlen(trim($slogan)) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($slogan) == FALSE) {
                $data['inputerror'][] = 'system_slogan';
                $data['error_string'][] = 'Valid System Slogan is required!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('system_version') == '') {
            $data['inputerror'][] = 'system_version';
            $data['error_string'][] = 'System Version is required!';
            $data['status'] = FALSE;
        }
        if (strlen(trim($this->request->getVar('system_version'))) < 2) {
            $data['inputerror'][] = 'system_slogan';
            $data['error_string'][] = 'Minimum length is 2 characters [' . strlen(trim($name)) . ']!';
            $data['status'] = FALSE;
        }
        */

        if ($this->request->getVar('business_name') == '') {
            $data['inputerror'][] = 'business_name';
            $data['error_string'][] = 'Business Name is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('business_name'))) {
            $bname = $this->request->getVar('business_name');
            if ($this->settings->validateName($bname) == TRUE) {
                if (strlen(trim($bname)) < 5) {
                    $data['inputerror'][] = 'business_name';
                    $data['error_string'][] = 'Minimum length is 5 characters [' . strlen(trim($bname)) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($bname) == FALSE) {
                $data['inputerror'][] = 'business_name';
                $data['error_string'][] = 'Valid Business Name is required!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('business_abbr') == '') {
            $data['inputerror'][] = 'business_abbr';
            $data['error_string'][] = 'Business Short Name is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('business_abbr'))) {
            $babbr = $this->request->getVar('business_abbr');
            if ($this->settings->validateName($babbr) == TRUE) {
                if (strlen(trim($babbr)) < 2) {
                    $data['inputerror'][] = 'business_abbr';
                    $data['error_string'][] = 'Minimum length is 2 characters [' . strlen(trim($babbr)) . ']!';
                    $data['status'] = FALSE;
                }
                if (strlen(trim($babbr)) > 10) {
                    $data['inputerror'][] = 'business_abbr';
                    $data['error_string'][] = 'Maximum length is 10 characters [' . strlen(trim($babbr)) . ']!';
                    $data['status'] = FALSE;
                }
            }
            if ($this->settings->validateName($babbr) == FALSE) {
                $data['inputerror'][] = 'business_abbr';
                $data['error_string'][] = 'Valid Business Short Name is required!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('business_slogan') == '') {
            $data['inputerror'][] = 'business_slogan';
            $data['error_string'][] = 'Business Slogan is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('business_slogan'))) {
            $bslogan = $this->request->getVar('business_slogan');
            if ($this->settings->validateAddress($bslogan) == FALSE) {
                $data['inputerror'][] = 'business_slogan';
                $data['error_string'][] = 'Valid Business Slogan is required!';
                $data['status'] = FALSE;
            }
            if ($this->settings->validateAddress($bslogan) == TRUE) {
                if (strlen(trim($bslogan)) < 5) {
                    $data['inputerror'][] = 'business_slogan';
                    $data['error_string'][] = 'Minimum length is 5 characters [' . strlen(trim($bslogan)) . ']!';
                    $data['status'] = FALSE;
                }
                if (strlen(trim($bslogan)) > 255) {
                    $data['inputerror'][] = 'business_slogan';
                    $data['error_string'][] = 'Maximum length is 255 characters [' . strlen(trim($bslogan)) . ']!';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('business_contact') == '') {
            $data['inputerror'][] = 'business_contact';
            $data['error_string'][] = 'Business contact is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('business_contact'))) {
            $this->validPhoneNumber([
                'phone' => $business_contact,
                'input' => 'business_contact',
            ]);

            if ($method == "add") {
                # check phone number existence
                $settingsRow = $this->settings->where(['business_contact' => $business_contact])->first();
                if ($settingsRow) {
                    $data['inputerror'][] = 'business_contact';
                    $data['error_string'][] = $business_contact . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                $settingsInfo['business_contact'] != $business_contact
            ) {
                # check phone number existence
                $settingsRow = $this->settings->where(['business_contact' => $business_contact])->first();
                if ($settingsRow) {
                    $data['inputerror'][] = 'business_contact';
                    $data['error_string'][] = $business_contact . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if (!empty($this->request->getVar('business_alt_contact'))) {
            $this->validPhoneNumber([
                'phone' => $business_alt_contact,
                'input' => 'business_alt_contact',
            ]);

            if ($method == "add") {
                # check phone number existence
                $settingsRow = $this->settings->where(['business_alt_contact' => $business_alt_contact])->first();
                if ($settingsRow) {
                    $data['inputerror'][] = 'business_alt_contact';
                    $data['error_string'][] = $business_alt_contact . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                $settingsInfo['business_alt_contact'] != $business_alt_contact
            ) {
                # check phone number existence
                $settingsRow = $this->settings
                    ->where(['business_alt_contact' => $business_alt_contact])->first();
                if ($settingsRow) {
                    $data['inputerror'][] = 'business_alt_contact';
                    $data['error_string'][] = $business_alt_contact . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('business_email') == '') {
            $data['inputerror'][] = 'business_email';
            $data['error_string'][] = 'Business email is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('business_email'))) {
            # check whether the business_email is valid
            if ($this->settings->validateEmail($this->request->getVar('business_email')) == FALSE) {
                $data['inputerror'][] = 'business_email';
                $data['error_string'][] = 'Valid Email is required';
                $data['status'] = FALSE;
            }
            if ($method == "add") {
                # check email address existence
                $settingsRow = $this->settings
                    ->where(['business_email' => $this->request->getVar('business_email')])->first();
                if ($settingsRow) {
                    $data['inputerror'][] = 'business_email';
                    $data['error_string'][] = $this->request->getVar('business_email') . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                $settingsInfo['business_email'] != $this->request->getVar('business_email')
            ) {
                # check email address existence
                $settingsRow = $this->settings
                    ->where(['business_email' => $this->request->getVar('business_email')])->first();
                if ($settingsRow) {
                    $data['inputerror'][] = 'business_email';
                    $data['error_string'][] = $this->request->getVar('business_email') . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if (!empty($this->request->getVar('business_pobox'))) {
            if (strlen(trim($this->request->getVar('business_pobox'))) < 8) {
                $data['inputerror'][] = 'business_pobox';
                $data['error_string'][] = 'Minimum length is 8 characters [' . strlen(trim($this->request->getVar('business_pobox'))) . ']!';
                $data['status'] = FALSE;
            }
            if ($method == "add") {
                # check email address existence
                $settingsRow = $this->settings
                    ->where(['business_pobox' => $this->request->getVar('business_pobox')])->first();
                if ($settingsRow) {
                    $data['inputerror'][] = 'business_pobox';
                    $data['error_string'][] = $this->request->getVar('business_pobox') . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                $settingsInfo['business_pobox'] != $this->request->getVar('business_pobox')
            ) {
                # check email address existence
                $settingsRow = $this->settings
                    ->where(['business_pobox' => $this->request->getVar('business_pobox')])->first();
                if ($settingsRow) {
                    $data['inputerror'][] = 'business_pobox';
                    $data['error_string'][] = $this->request->getVar('business_pobox') . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('business_address') == '') {
            $data['inputerror'][] = 'business_address';
            $data['error_string'][] = 'Business address is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('business_address'))) {
            if ($this->settings->validateAddress($this->request->getVar('business_address')) == TRUE) {
                if (strlen(trim($this->request->getVar('business_address'))) < 4) {
                    $data['inputerror'][] = 'business_address';
                    $data['error_string'][] = 'Address is too short';
                    $data['status'] = FALSE;
                }
            }
            /*
            if ($this->settings->validateAddress($this->request->getVar('business_address')) == FALSE) {
                $data['inputerror'][] = 'business_address';
                $data['error_string'][] = 'Valid Address is required';
                $data['status'] = FALSE;
            }
            */
        }
        if (!empty($this->request->getVar('business_web'))) {
            if (strlen(trim($this->request->getVar('business_web'))) < 8) {
                $data['inputerror'][] = 'business_web';
                $data['error_string'][] = 'Minimum length is 10 characters [' . strlen(trim($this->request->getVar('business_web'))) . ']!';
                $data['status'] = FALSE;
            }
            if ($method == "add") {
                # check email address existence
                $settingsRow = $this->settings
                    ->where(['business_web' => $this->request->getVar('business_web')])->first();
                if ($settingsRow) {
                    $data['inputerror'][] = 'business_web';
                    $data['error_string'][] = $this->request->getVar('business_web') . ' already added';
                    $data['status'] = FALSE;
                }
            }
            if (
                $method == "update" &&
                $settingsInfo['business_web'] != $this->request->getVar('business_web')
            ) {
                # check email address existence
                $settingsRow = $this->settings
                    ->where(['business_web' => $this->request->getVar('business_web')])->first();
                if ($settingsRow) {
                    $data['inputerror'][] = 'business_web';
                    $data['error_string'][] = $this->request->getVar('business_web') . ' already added';
                    $data['status'] = FALSE;
                }
            }
        }
        if ($this->request->getVar('tax_rate') == '') {
            $data['inputerror'][] = 'tax_rate';
            $data['error_string'][] = 'Tax is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('tax_rate'))) {
            $rate = $this->request->getVar('tax_rate');
            if (!preg_match("/^[0-9.']*$/", $rate)) {
                $data['inputerror'][] = 'tax_rate';
                $data['error_string'][] = 'Only digits and . allowed!';
                $data['status'] = FALSE;
            }
            if ($rate < 0) {
                $data['inputerror'][] = 'tax_rate';
                $data['error_string'][] = 'Minimum percentage exceeded!';
                $data['status'] = FALSE;
            }
            if ($rate > 100) {
                $data['inputerror'][] = 'tax_rate';
                $data['error_string'][] = 'Maximum percentage exceeded!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('round_off') == '') {
            $data['inputerror'][] = 'round_off';
            $data['error_string'][] = 'Round Off[Fee] is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('round_off'))) {
            $round = $this->request->getVar('round_off');
            if (!preg_match("/^[0-9.']*$/", $round)) {
                $data['inputerror'][] = 'round_off';
                $data['error_string'][] = 'Only digits and . allowed!';
                $data['status'] = FALSE;
            }
            if ($round < 100) {
                $data['inputerror'][] = 'round_off';
                $data['error_string'][] = 'Maximum Round Off[Fee] exceeded!';
                $data['status'] = FALSE;
            }
        }
        if ($this->request->getVar('financial_year_start') == '') {
            $data['inputerror'][] = 'financial_year_start';
            $data['error_string'][] = 'Financial year start is required';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('currency_id') == '') {
            $data['inputerror'][] = 'currency_id';
            $data['error_string'][] = 'Currency is required!';
            $data['status'] = FALSE;
        }
        if ($this->request->getVar('business_about') == '') {
            $data['inputerror'][] = 'business_about';
            $data['error_string'][] = 'Business About is required!';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
