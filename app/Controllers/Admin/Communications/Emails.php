<?php

namespace App\Controllers\Admin\Communications;

use App\Controllers\MasterController;

class Emails extends MasterController
{
    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Communications';
        $this->title = 'Emails';
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
            return view('admin/communications/mails/index', [
                'title' => $this->title,
                'menu' => $this->menu,
                'settings' => $this->settingsRow,
                'user' => $this->userRow,
                'userMenu' => $this->load_menu(),
                'tags' => $this->emailTags->where(['status' => 'active'])->findAll(),
                'tagColors' => $this->email->tagColors(),
                'accounts' => $this->settings->generateAccountTypes(),
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
        $data = $this->email->select('emails.*, emailtags.tag_name, emailtags.slug, emailtags.color')->join('emailtags', 'emailtags.id = emails.tag_id', 'left')->find($id);
        if ($data) {
            // recipient data
            $recipientData = $this->get_userRow($data['recipient_id'], $data['recipient_account']);
            //add recipient data from recipiet table to the mail data from mails table
            if ($recipientData) {
                $finalData = array_merge($data, $recipientData);
            } else {
                $finalData = $data;
            }
            // get email attachments
            $attachments = $this->attachment->where(['email_id' => $id])->findAll();
            if ($attachments) {
                $finalData['attachments'] = $attachments;
            } else {
                $finalData['attachments'] = null;
            }
            return $this->respond(($finalData));
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'The requested ' . $this->title . ' resource could not be found!',
            ];
            return $this->respond($response);
        }
    }
    // get user data
    private function get_userRow($id, $account = null) {
        if ($account && (strtolower($account) == 'client')) {
            $row = $this->client->select('clients.name, clients.branch_id, clients.staff_id, clients.account_no, clients.account_type, clients.account_balance, clients.email, clients.mobile, clients.alternate_no, clients.gender, clients.dob, clients.marital_status, clients.religion, clients.nationality, clients.occupation, clients.job_location, clients.residence, clients.id_type, clients.id_number, clients.id_expiry_date, clients.next_of_kin_name, clients.next_of_kin_relationship, clients.next_of_kin_contact, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.photo, clients.id_photo_front, clients.id_photo_back, clients.signature, clients.access_status, branches.branch_name, branches.branch_email, branches.branch_mobile, branches.branch_address')
                ->join('branches', 'branches.id = clients.branch_id', 'left')
                ->join('staffs', 'staffs.id = clients.staff_id', 'left')
                ->find($id);
        } else {
            $row = $this->staff->select('staffs.staffID, staffs.staff_name as name, staffs.mobile, staffs.alternate_mobile, staffs.email, staffs.gender, staffs.marital_status, staffs.religion, staffs.nationality, staffs.date_of_birth, staffs.address, staffs.photo, staffs.account_type, staffs.access_status, branches.branch_name, branches.branch_email, branches.branch_mobile, branches.branch_address')
                ->join('branches', 'branches.id = staffs.branch_id', 'left')
                ->find($id);
            if(!$row){
                $row = $this->client->select('clients.name, clients.branch_id, clients.staff_id, clients.account_no, clients.account_type, clients.account_balance, clients.email, clients.mobile, clients.alternate_no, clients.gender, clients.dob, clients.marital_status, clients.religion, clients.nationality, clients.occupation, clients.job_location, clients.residence, clients.id_type, clients.id_number, clients.id_expiry_date, clients.next_of_kin_name, clients.next_of_kin_relationship, clients.next_of_kin_contact, clients.next_of_kin_alternate_contact, clients.nok_email, clients.nok_address, clients.photo, clients.id_photo_front, clients.id_photo_back, clients.signature, clients.access_status, branches.branch_name, branches.branch_email, branches.branch_mobile, branches.branch_address')
                ->join('branches', 'branches.id = clients.branch_id', 'left')
                ->join('staffs', 'staffs.id = clients.staff_id', 'left')
                ->find($id);
            }
        }

        return $row;
    }
    // download attachment
    public function download($id)
    {
        helper("download");
        $attachmentData = $this->attachment->find($id);
        if ($attachmentData) {
            if (file_exists("uploads/mailings/attachments/" . $attachmentData['attachment']) && $attachmentData['attachment']) {
                $file = base_url('uploads/mailings/attachments/' . $attachmentData['attachment']);

                $data = file_get_contents($file);
                force_download($attachmentData['attachment'], $data);

                session()->setFlashdata('success', "Attachment Downloaded successfully!");
                return redirect()->to(base_url('/admin/communications/mails/emails'));
            } else {
                session()->setFlashdata('failed', "Attachment File Not Found on this server!");
                return redirect()->to(base_url('/admin/communications/mails/emails'));
            }
        } else {
            session()->setFlashdata('failed', "Attachment Data Not Found on this server!");
            return redirect()->to(base_url('/admin/communications/mails/emails'));
        }
    }
    // get all emails by their label
    public function fetch_mails($key = 'label', $value = 'inbox')
    {
        if (strtolower($key) == 'label') {
            switch (strtolower($value)) {
                case 'inbox':
                    $where = ['recipient_id' => $this->userRow['id'], 'deleted_at' => null];
                    break;
                case 'sent':
                    $where = ['sender_id' => $this->userRow['id'], 'deleted_at' => null];
                    break;
                case 'unread':
                    $where = ['recipient_id' => $this->userRow['id'], 'status' => 'unread', 'deleted_at' => null];
                    break;
                default:
                    $where = ['label' => strtolower($value), 'deleted_at' => null];
                    break;
            }
        }

        if (strtolower($key) == 'tag') {
            $where = ['tag_id' => $value, 'deleted_at' => null];
        }

        $mails = $this->email->where($where)->orderBy('id', 'DESC')->findAll();
        if ($mails) {
            $emails = []; // final mails data
            // get each recipient data
            foreach ($mails as $mail) {
                //mail data as it is in the emails table
                $data = $this->email->select('emails.*, emailtags.tag_name, emailtags.slug, emailtags.color')->join('emailtags', 'emailtags.id = emails.tag_id', 'left')->find($mail['id']);
                // recipient data
                $recipientData = $this->get_userRow($mail['recipient_id'], $data['recipient_account']);

                //add recipient data from recipient table to the mail data from mails table
                if ($recipientData) {
                    $finalData = array_merge($data, $recipientData);
                } else {
                    $finalData = $data;
                }

                //add overall data from recipient' & mails' tables to final mails data
                $emails[] = $finalData;
            }
            return $this->respond(($emails));
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'No ' . $this->title . ' found!',
            ];
            return $this->respond($response);
        }
    }
    // generate email addresses per account type
    public function fetch_mailAddresses($account)
    {
        switch (strtolower($account)) {
            case 'client':
                $mails = $this->client->select('id, email')->where(['access_status' => 'Active'])->findAll();
                break;
            case 'administrator':
                $mails = $this->staff->select('id, email')->where(['account_type' => 'Administrator', 'access_status' => 'Active'])->findAll();
                break;
            case 'employee':
                $mails = $this->staff->select('id, email')->where(['account_type' => 'Employee', 'access_status' => 'Active'])->findAll();
                break;
            default:
                $mails = $this->user->select('id, email')->where(['access_status' => 'active'])->findAll();
                break;
        }
        if ($mails) {
            return $this->respond($mails);
        } else {
            $response = [
                'status' => 404,
                'error' => 'Not Found',
                'messages' => 'No ' . $this->title . ' found!',
            ];
            return $this->respond($response);
        }
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'create')) {
            $this->_validateEmail();
            $id = trim($this->request->getVar('id'));
            $action = trim($this->request->getVar('action')); //
            $sender = $this->userRow['id'];
            $sender_account = $this->userRow['account_type'];
            $recipients = $this->request->getVar('recipient_id[]');
            $type = trim($this->request->getVar('type'));
            $tag_id = trim($this->request->getVar('tag_id'));
            $subject = trim($this->request->getVar('subject'));
            $message = trim($this->request->getVar('message'));
            // get sender data
            $senderInfo = $this->get_userRow($this->userRow['id'], $sender_account);

            // check internet connection
            $checkInternet = $this->settings->checkNetworkConnection();
            if ($checkInternet) {
                // if new email
                if (strtolower($action) == 'compose') {
                    if (count($recipients) > 0) {
                        foreach ($recipients as $recipient) {
                            // get recipient data
                            $recipientInfo = $this->get_userRow($recipient);
                            // db data
                            $data = [
                                // 'label' => trim($this->request->getVar('label')),
                                'sender_id' => $sender,
                                'sender_account' => $sender_account,
                                'recipient_id' => $recipient,
                                'recipient_account' => $recipientInfo['account_type'],
                                'type' => $type,
                                'tag_id' => (!empty($tag_id) ? $tag_id : null),
                                'subject' => $subject,
                                'message' => $message,
                            ];
                            // save into db
                            $email_id = $this->email->insert($data);
                            $uploaded = $attachments = false;
                            # upload email attachments
                            if (!empty($_FILES['attachment[]']['name'])) {
                                $uploaded = $this->_doUploadEmailattachments($email_id);
                            }
                            // this email's attachments
                            if ($uploaded) {
                                $attachments = $this->attachment->where(['email_id' => $email_id])->findAll();
                            }
                        }
                    }
                }
                // replying to an email
                if (strtolower($action) == 'reply') {
                    // replied email data
                    $emailData = $this->email->find($id);
                    if ($emailData) {
                        // get recipient data
                        $recipientInfo = $this->get_userRow($emailData['sender_id'], $emailData['recipient_account']);
                        $data = [
                            // 'label' => trim($this->request->getVar('label')),
                            'sender_id' => $sender,
                            'sender_account' => $sender_account,
                            'recipient_id' => $emailData['sender_id'],
                            'recipient_account' => $emailData['recipient_account'],
                            'type' => $emailData['type'],
                            'tag_id' => (!empty($emailData['tag_id']) ? $emailData['tag_id'] : null),
                            'subject' => $subject,
                            'message' => $message,
                            'account_id' => $this->userRow['account_id'],
                        ];
                        // insert into database
                        $email_id = $this->email->insert($data);
                        # upload email attachments
                        if (!empty($_FILES['attachment[]']['name'])) {
                            $uploaded = $this->_doUploadEmailattachments($email_id);
                        } else {
                            $uploaded = [
                                'status' => 200,
                                'error' => null,
                                'messages' => "No attachment provided",
                            ];
                        }
                        // this email's attachments
                        if ($uploaded['error'] == null) {
                            $attachments = $this->attachment->where(['email_id' => $email_id])->findAll();
                        } else {
                            $attachments = null;
                        }
                    }
                }
                // insert into emails table
                if ($email_id) {
                    // send email
                    $this->send_mail($senderInfo, $recipientInfo, $subject, $message, $attachments);
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'send',
                        'description' => ucfirst('Sent' . $this->title),
                        'module' => strtolower('emails'),
                        'referrer_id' => $email_id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' sent successfully',
                        ];
                        return $this->respond($response);
                        exit;
                    } else {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => $this->title . ' sent successfully. loggingFailed'
                        ];
                        return $this->respond($response);
                        exit;
                    }
                } else {
                    $response = [
                        'status' => 500,
                        'error' => 'Send Failed',
                        'messages' => 'Send ' . $this->title . ' failed, try again later!',
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'No Internet',
                    'messages' => "Email could not be sent No Internet"
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized to Send ' . $this->title . '!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function update_status($id = null)
    {
        $emailRow = $this->email->find($id);
        if ($emailRow) {
            $key = $this->request->getVar('key');
            $value = $this->request->getVar('value');
            // update email label
            if (strtolower($key) == 'label') {
                if (strtolower($emailRow['label']) != strtolower($value)) {
                    $data = [
                        'label' => $value,
                    ];
                } else {
                    $data = [
                        'label' => null
                    ];
                }
            }
            // update email status
            if (strtolower($key) == 'status') {
                $data = [
                    'status' => $value,
                ];
            }

            $update = $this->email->update($id, $data);
            if ($update) {
                // insert into activity logs
                $activityData = [
                    'user_id' => $this->userRow['id'],
                    'action' => 'update',
                    'description' => ucfirst('updated ' . $this->title . ' status, email: ' . $emailRow['id']),
                    'module' => strtolower('emails'),
                    'referrer_id' => $id,
                ];
                $activity = $this->insertActivityLog($activityData);
                if ($activity) {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => 'Email Status updated successfully.'
                    ];
                    return $this->respond($response);
                    exit;
                } else {
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => 'Email Status updated successfully. loggingFailed'
                    ];
                    return $this->respond($response);
                    exit;
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => 'Update Failed',
                    'messages' => 'Cancelling application failed, try again later!',
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 404,
                'error'    => 'Not Found',
                'messages' => 'Email not found',
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
            $data = $this->email->find($id);
            if ($data) {
                $delete = $this->email->delete($id);
                if ($delete) {
                    // insert into activity logs
                    $activityData = [
                        'user_id' => $this->userRow['id'],
                        'action' => 'delete',
                        'description' => ucfirst('deleted ' . $this->title . ', ' . $data['id']),
                        'module' => strtolower('emails'),
                        'referrer_id' => $id,
                    ];
                    $activity = $this->insertActivityLog($activityData);
                    if ($activity) {
                        $response = [
                            'status'   => 200,
                            'error'    => null,
                            'messages' => 'Email deleted successfully',
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
                        'messages' => 'Deleting ' . $this->title . ' record failed, try again later!',
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
                'messages' => 'You are not authorized to delete ' . $this->title . ' record!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     */
    public function ajax_bulky_change()
    {
        if ($this->checkPermissions($this->userPermissions, $this->menuItem, 'bulkDelete')) {
            $list_id = $this->request->getVar('id');
            $key = $this->request->getVar('action');
            $value = $this->request->getVar('value');
            foreach ($list_id as $id) {
                $data = $this->email->find($id);
                if ($data) {
                    if (strtolower($key) == 'label') {
                        if (strtolower($data['label']) != strtolower($value)) {
                            $data = [
                                'label' => $value,
                            ];
                        } else {
                            $data = [
                                'label' => '',
                            ];
                        }
                        $query = $this->email->update($id, $data);
                    }
                    if (strtolower($key) == 'status') {
                        $data = [
                            'status' => $value,
                        ];
                        $query = $this->email->update($id, $data);
                    }
                    if (strtolower($key) == 'delete') {
                        $query = $this->email->delete($id);
                    }
                    if ($query) {
                        // insert into activity logs
                        $activityData = [
                            'user_id' => $this->userRow['id'],
                            'action' => 'bulk-delete',
                            'description' => ucfirst('changed ' . $this->title . ', ' . $key . ' ' . $value),
                            'module' => strtolower('emails'),
                            'referrer_id' => $id,
                        ];
                        $activity = $this->insertActivityLog($activityData);
                    } else {
                        $response = [
                            'status' => 500,
                            'error' => 'Action Failed',
                            'messages' => 'Failed to implement, try again later!',
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
            }
            if ($activity) {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' action completed successfully',
                ];
                return $this->respond($response);
                exit;
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => $this->title . ' action completed successfully. loggingFailed'
                ];
                return $this->respond($response);
                exit;
            }
        } else {
            $response = [
                'status'   => 403,
                'error'    => 'Access Denied',
                'messages' => 'You are not authorized!',
            ];
            return $this->respond($response);
            exit;
        }
    }

    // upload mail attachments
    private function _doUploadEmailattachments($email_id)
    {
        $attachmentFiles = $this->request->getFileMultiple('attachment');
        // loop thru attachments
        foreach ($attachmentFiles as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                // Get file extension and size
                $fileExt = $file->getExtension();
                $fileSize = $file->getSize();

                // Generate a unique filename
                $newFileName = $this->settings->generateRandomNumbers(10) . '.' . $fileExt;

                // Move the uploaded file to the desired directory
                $file->move("uploads/mailings/attachments/", $newFileName);

                // Insert file information into the database
                $data[] = [
                    'email_id' => $email_id,
                    'attachment' => $newFileName,
                    'extension' => $fileExt,
                    'size' => $fileSize,
                ];
            }
        }
        $upload = $this->attachment->insertBatch($data);

        if ($upload) {
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => "Uploading attachment was Successful"
            ];
            return $response;
        } else {
            $response = [
                'status' => 500,
                'error' => 'error',
                'messages' => "Uploading attachment Failed"
            ];
            return $this->respond($response);
            exit;
        }
    }

    // send mail
    public function send_mail($sender, $recipient, $subject, $message, $attachments = null)
    {
        // echo json_encode($recipient['email']); exit;
        $email = \Config\Services::email();
        $email->setTo($recipient['email']);
        $email->setFrom($sender['email'], $sender['name']);
        $email->setSubject($subject);
        # $email->setMessage($data);
        $token = 'Notification';
        if ($token) {
            # here is for sending password reset mail using custom template
            $template = view("admin/auth/templates/emails", [
                'token' => $token,
                'sender' => $sender,
                'recipient' => $recipient,
                'settings' => $this->settingsRow,
                'data' => $message,
                'attachments' => $attachments,
            ]);
        }

        $email->setMessage($template);

        /*
        $email->setCC('another@emailHere');//CC
        $email->setBCC('thirdEmail@emialHere');// and BCC
        */

        if ($email->send()) {
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => "Email successfully sent"
            ];
            return $response;
        } else {
            $data = $email->printDebugger(['headers']);
            $response = [
                'status' => 500,
                'error' => 'error',
                'messages' => "Email send failed"
            ];
            return $this->respond($response);
            exit;
        }
    }

    /**
     * validate form inputs
     */
    private function _validateEmail()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        $action = $this->request->getVar('action');
        if ($this->request->getVar('action') == '') {
            $data['inputerror'][] = 'action';
            $data['error_string'][] = 'Action is required';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('action'))) {
            if (strtolower($action) == 'compose') {
                if ($this->request->getVar('sender_id') == '') {
                    $data['inputerror'][] = 'sender_id';
                    $data['error_string'][] = 'Sender email is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('sender_id'))) {
                    # check whether the email is valid
                    if ($this->settings->validateEmail($this->request->getVar('sender_id')) == FALSE) {
                        $data['inputerror'][] = 'sender_id';
                        $data['error_string'][] = 'Valid Email Address is required';
                        $data['status'] = FALSE;
                    }
                }

                if ($this->request->getVar('recipient_id[]') == '') {
                    $data['inputerror'][] = 'recipient_id[]';
                    $data['error_string'][] = 'Recipient is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('recipient_id[]'))) {
                    // recipient ids
                    $recipients = $this->request->getVar('recipient_id[]');
                    if (count($recipients) > 0) {
                        # check whether the email is valid
                        foreach ($recipients as $key => $email) {
                            $recipientRow = $this->get_userRow($email);
                            # code...
                            if ($this->settings->validateEmail($recipientRow['email']) == FALSE) {
                                $data['inputerror'][] = 'recipient_id[]';
                                $data['error_string'][] = 'Email ' . $recipientRow['email'] . ' is invalid';
                                $data['status'] = FALSE;
                            }
                        }
                    }
                }

                // if ($this->request->getVar('tag_id') == '') {
                //     $data['inputerror'][] = 'tag_id';
                //     $data['error_string'][] = 'Email Tag is required';
                //     $data['status'] = FALSE;
                // }
        
                if ($this->request->getVar('subject') == '') {
                    $data['inputerror'][] = 'subject';
                    $data['error_string'][] = 'Email Subject is required';
                    $data['status'] = FALSE;
                }
                if (!empty($this->request->getVar('subject'))) {
                    if ($this->settings->validateAddress($this->request->getVar('subject')) == TRUE) {
                        if (strlen(trim($this->request->getVar('subject'))) < 4) {
                            $data['inputerror'][] = 'subject';
                            $data['error_string'][] = 'Subject is too short';
                            $data['status'] = FALSE;
                        }
                    }
                    /*
                    if ($this->settings->validateAddress($this->request->getVar('subject')) == FALSE) {
                        $data['inputerror'][] = 'subject';
                        $data['error_string'][] = 'Valid Subject is required';
                        $data['status'] = FALSE;
                    }
                    */
                }
            }
        }

        if ($this->request->getVar('message') == '') {
            $data['inputerror'][] = 'message';
            $data['error_string'][] = 'Email Message is required!';
            $data['status'] = FALSE;
        }
        if (!empty($this->request->getVar('message'))) {
            if (strlen($this->request->getVar('message')) < 4) {
                $data['inputerror'][] = 'message';
                $data['error_string'][] = 'Minimum length should is 4!';
                $data['status'] = FALSE;
            }
        }

        if (!empty($_FILES['attachment[]']['name'])) {
            if ($this->request->getFileMultiple('attachment')) {
                // Define the allowed file extensions for images and documents
                // $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                // $allowedDocumentExtensions = ['pdf', 'docx', 'txt', 'xlsx', 'csv', 'ppt'];
                # set validation rule
                $validationRule = [
                    'attachment[]' => [
                        "rules" => "uploaded[attachment]|max_size[attachment, 15360]",
                        "label" => "attachment",
                        "errors" => [
                            'max_size' => 'The size of this image(s) is too large. The image must have less than 15MB size',
                        ]
                    ],
                ];
                if (!$this->validate($validationRule)) {
                    $data['inputerror'][] = 'attachment[]';
                    $data['error_string'][] = $this->validator->getError("attachment[]");
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit;
                }
                if (count($this->request->getFileMultiple('attachment')) > 5) {
                    $data['inputerror'][] = 'attachment[]';
                    $data['error_string'][] = "Maximum 5 attachment[]s allowed!";
                    $data['status'] = FALSE;
                    echo json_encode($data);
                    exit;
                }
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit;
        }
    }
}
