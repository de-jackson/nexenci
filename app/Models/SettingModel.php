<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'author',
        'system_name',
        'system_abbr',
        'system_slogan',
        'system_version',
        'business_name',
        'business_abbr',
        'business_slogan',
        'business_contact',
        'business_alt_contact',
        'business_email',
        'business_pobox',
        'business_address',
        'business_web',
        'business_logo',
        'business_about',
        'google_map_iframe',
        'whatsapp',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'linkedin',
        'tax_rate',
        'round_off',
        'currency_id',
        'description',
        'background_logo',
        'email_template_logo',
        'account_id',
        'website',
        'sms',
        'email',
        'financial_year_start',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected $_internet_connection = FALSE;
    protected $builder;

    public function __construct()
    {
        parent::__construct();
        # load database connection
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        # check internet connection
        if ($this->_checkInternetConnection()) {
            $this->_internet_connection = true;
        }
    }

    public function checkNetworkConnection()
    {
        if ($this->_internet_connection) {
            #Internet connection OK
            return true;
        } else {
            #Internet connection problem
            return false;
        }
    }

    private function _checkInternetConnection($sCheckHost = 'www.google.com')
    {
        return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 5);
    }

    public function validateName($name)
    {
        return preg_match('/^[-a-zA-Z . ]+$/', trim($name)) ? TRUE : FALSE;
    }

    public function validateAddress($address)
    {
        return preg_match('/^[-a-zA-Z0-9 #:!().,-\/]+$/', $address) ? TRUE : FALSE;
    }

    public function validatePhoneNumber($mobile)
    {
        return (!preg_match("/^[0-9*#+]+$/", trim($mobile))) ? FALSE : TRUE;
    }

    public function validateEmail($email)
    {
        return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", trim($email)) ? TRUE : FALSE;
    }

    public function sendMail($data, $subject, $token, $password = null, $menu = null)
    {
        $email = \Config\Services::email();
        $positionModel = new PositionModel();
        // $email->setTo($sendTo);
        $email->setTo($data["email"]);
        $email->setFrom('nexensystem@gmail.com', 'Nexen');
        $email->setSubject($subject);
        # $email->setMessage($data);
        if (strtolower($token) == 'registration' && $menu == 'staff') {
            if (strtolower($data['account_type']) == 'employee' || strtolower($data['account_type']) == 'administrator') {
                $staffDepartment = $positionModel->select('positions.*, departments.department_name')
                    ->join('departments', 'departments.id = positions.department_id')->find($data['position_id']);
                $data['department_name'] = $staffDepartment['department_name'];
                $data['position'] = $staffDepartment['position'];
            }
        }
        $model = new SettingModel();
        $settingsID = 1;
        if ($token) {
            # here is for sending password reset mail using custom template
            $template = view("admin/auth/templates/emails", [
                'menu' => $menu,
                'data' => $data,
                'token' => $token,
                'password' => $password,
                'settings' => $model->select('settings.*, currencies.currency,  currencies.symbol')->join('currencies', 'currencies.id = settings.currency_id')->find($settingsID),
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
        } else {
            $data = $email->printDebugger(['headers']);
            $response = [
                'status' => 201,
                'error' => 'error',
                'messages' => "Email send failed"
            ];
        }
        return $response;
    }

    public function sendMailNotify($message, $subject, $sendTo, $attachments = null)
    {
        $email = \Config\Services::email();
        // $email->setTo($sendTo);
        $email->setTo($sendTo);
        $email->setFrom('toshitech4@gmail.com', 'Nexen');
        $email->setSubject($subject);
        # $email->setMessage($data);

        $model = new SettingModel();
        $settingsID = 1;


        $template = view("admin/auth/templates/account", [
            'subject' => $subject,
            'message' => $message,
            'settings' => $model->select('settings.*, currencies.currency,  currencies.symbol')->join('currencies', 'currencies.id = settings.currency_id')->find($settingsID),
        ]);


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
        } else {
            $data = $email->printDebugger(['headers']);
            $response = [
                'status' => 201,
                'error' => 'error',
                'messages' => "Email send failed"
            ];
        }
        return $response;
    }

    public function _doUploadPhoto($menu, $photo)
    {
        $path = "uploads/";
        switch (strtolower($menu)) {
            case "user":
                $path = "uploads/users/passports/";
                break;
            case "client":
                $path = "uploads/clients/passports/";
                break;
            case "employee":
                $path = "uploads/staffs/employees/passports/";
                break;
            case "admin":
                $path = "uploads/staffs/admins/passports/";
                break;
            default:
                $path;
                break;
        }
        $validationRule = [
            'photo' => [
                "rules" => "uploaded[photo]|max_size[photo,3072]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]",
                "label" => "Profile Image",
                "errors" => [
                    'max_size' => 'The size of this image is too large. The image must have less than 3MB size',
                    'mime_in' => 'Your upload does not have a valid image format',
                    'is_image' => 'Your file is not allowed! Please use an image!'
                ]
            ],
        ];
        if (!$this->validate($validationRule)) {
            $data['inputerror'][] = 'photo';
            $data['error_string'][] = $this->validator->getError("photo");
            $data['status'] = FALSE;
            return $data;
        }
        $file = $photo;
        $file_image = $file->getName();
        # Renaming file before upload
        $temp = explode(".", $file_image);
        $newfilename = $this->generateRandomNumbers(10) . '.' . end($temp);

        if ($file->move($path, $newfilename)) {
            return $newfilename;
        } else {
            $data['inputerror'][] = 'photo';
            $data['error_string'][] = "Failed to upload Image";
            $data['status'] = FALSE;
            return $data;
        }
    }

    public function _doUploadIdPhoto($menu, $face, $photo)
    {
        $path = "uploads/";
        switch (strtolower($menu)) {
            case "user":
                $path = "uploads/users/ids/";
                break;
            case "client":
                $path = "uploads/clients/ids/";
                break;
            case "employee":
                $path = "uploads/staffs/employees/ids/";
                break;
            case "admin":
                $path = "uploads/staffs/admins/ids/";
                break;
            default:
                $path;
                break;
        }
        // append face of id beign uploaded to the upload path
        $path .= $face . '/';
        $validationRule = [
            'id_photo_' . strtolower($face) => [
                "rules" => "uploaded[id_photo_" . strtolower($face) . "]|max_size[id_photo_" . strtolower($face) . ",3072]|is_image[id_photo_" . strtolower($face) . "]|mime_in[id_photo_" . strtolower($face) . ",image/jpg,image/jpeg,image/png]",
                "label" => ucfirst($face) . " ID Photo",
                "errors" => [
                    'max_size' => 'The size of this image is too large. The image must have less than 3MB size',
                    'mime_in' => 'Your upload does not have a valid image format',
                    'is_image' => 'Your file is not allowed! Please use an image!'
                ]
            ],
        ];
        if (!$this->validate($validationRule)) {
            $data['inputerror'][] = 'id_photo_' . strtolower($face);
            $data['error_string'][] = $this->validator->getError("id_photo_" . strtolower($face));
            $data['status'] = FALSE;
            return $data;
        }
        $file = $photo;
        $file_image = $file->getName();
        # Renaming file before upload
        $temp = explode(".", $file_image);
        $newfilename = $this->generateRandomNumbers(10) . '.' . end($temp);

        if ($file->move($path, $newfilename)) {
            return $newfilename;
        } else {
            $data['inputerror'][] = 'id_photo_' . strtolower($face);
            $data['error_string'][] = "Failed to upload Image";
            $data['status'] = FALSE;
            return $data;
        }
    }

    public function _doUploadSignature($menu, $signature)
    {
        $path = "uploads/";
        switch (strtolower($menu)) {
            case "client":
                $path = "uploads/clients/signatures/";
                break;
            case "employee":
                $path = "uploads/staffs/employees/signatures/";
                break;
            case "admin":
                $path = "uploads/staffs/admins/signatures/";
                break;
            case "application":
                $path = "uploads/applications/signatures/";
                break;
            default:
                $path;
                break;
        }

        $validationRule = [
            'signature' => [
                "rules" => "uploaded[signature]|max_size[signature,1024]|is_image[signature]|mime_in[signature,image/jpg,image/jpeg,image/png]",
                "label" => "Profile Image",
                "errors" => [
                    'max_size' => 'The size of this image is too large. The image must have less than 1MB size',
                    'mime_in' => 'Your upload does not have a valid image format',
                    'is_image' => 'Your file is not allowed! Please use an image!'
                ]
            ],
        ];
        if (!$this->validate($validationRule)) {
            $data['inputerror'][] = 'signature';
            $data['error_string'][] = $this->validator->getError("signature");
            $data['status'] = FALSE;
            return $data;
        }
        $file = $signature;
        $file_name = $file->getName();
        # Renaming file before upload
        $temp = explode(".", $file_name);
        $newfilename = $this->generateRandomNumbers(10) . '.' . end($temp);

        if ($file->move($path, $newfilename)) {
            return $newfilename;
        } else {
            $data['inputerror'][] = 'signature';
            $data['error_string'][] = "Failed to upload Image";
            $data['status'] = FALSE;
            return $data;
        }
    }

    public function generateUniqueNo($account, $reg_date = Null)
    {
        if ($reg_date) {
            $dateMonth = date("md", strtotime($reg_date));
        } else {
            $dateMonth = date("md");
        }
        switch (strtolower($account)) {
            case 'client':
                $model = new ClientModel();
                $table = 'clients';
                $var = "C";
                $where = ['account_type' => 'Client'];
                break;
            case 'employee':
                $model = new StaffModel();
                $table = 'staffs';
                $var = "S";
                $where = ['account_type' => 'Employee'];
                break;
            case 'administrator':
                $model = new StaffModel();
                $table = 'staffs';
                $var = "A";
                $where = ['account_type' => 'Administrator'];
                break;
            case 'application':
                $model = new LoanApplicationModel();
                $table = 'loanapplications';
                $var = "A";
                $where = ['DATE_FORMAT(created_at, "%Y-%m")' => date('Y-m')];
                break;
            case 'disbursement':
                $model = new DisbursementModel();
                $table = 'disbursements';
                $var = "L";
                $where = ['DATE_FORMAT(created_at, "%Y-%m")' => date('Y-m')];
                break;
            default:
                break;
        }

        # $num = $model->where($where)->countAllResults();
        $results = $this->db->table($table)
            ->where($where)
            ->get()->getResultArray();
        $num = count($results);
        $interval = (intval($num) + 1);
        $interval = (intval($num) + 1);
        if ($interval < 10) {
            $str = "000" . $interval;
        } elseif ($interval >= 10 && $interval < 100) {
            $str = "00" . $interval;
        } elseif ($interval >= 100 && $interval < 1000) {
            $str = "0" . $interval;
        } else {
            $str = $interval;
        }
        $AccountNumber = $var . date("y") . $dateMonth . $str;

        return $AccountNumber;
    }

    public function generateToken()
    {
        $token_chars = "abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNOPQRSTUVWXYZ0123456789";
        $token = substr(str_shuffle($token_chars), 0, 32);
        return $token;
    }

    public function generateRandomNumbers($length = 8, $type = null)
    {
        if (!empty($type)) {
            $chars = "0123456789";
        } else {
            $chars = "abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNOPQRSTUVWXYZ0123456789";
        }
        $random = substr(str_shuffle($chars), 0, $length);
        return $random;
    }

    public function generateTitles()
    {
        return [
            'Mr.' => 'Mr.',
            'Mrs.' => 'Mrs.',
            'Miss.' => 'Miss',
            'Dr.' => 'Dr.',
            // 'Ms.' => 'Ms.',
            'Tr.' => 'Tr.',
            'Fr.' => 'Fr.',
            'Rev.' => 'Rev.',
            // 'Hon.' => 'Hon.',
            'Sir' => 'Sir',
        ];
    }
    public function generateGender()
    {
        return [
            'Female' => 'Female',
            'Male' => 'Male',
            // 'Others' => 'Others',
        ];
    }

    public function generateOccupations()
    {
        return [
            'Teacher' => 'Teacher',
            'Lawyer' => 'Lawyer',
            'Doctor' => 'Doctor',
            'Nurse' => 'Nurse',
            'Engineer' => 'Engineer',
            'Politician' => 'Politician',
            'Entrepreneur' => 'Entrepreneur',

        ];
    }

    public function generateMaritalStatus()
    {
        return [
            'Single' => 'Single',
            'Married' => 'Married',
            'Divorced' => 'Divorced',
            'Widowed' => 'Widowed',
            'Others' => 'Others'
        ];
    }

    public function generateReligion()
    {
        return [
            'Protestant' => 'Protestant',
            'Catholic' => 'Catholic',
            'Muslim' => 'Muslim',
            'Advent' => 'Advent',
            'Born-Again' => 'Born-Again',
            'Othodox' => 'Othodox',
            'Others' => 'Others',
        ];
    }

    public function generateNationality()
    {
        return [
            'Afghan' => 'Afghan',
            'Albanian' => 'Albanian',
            'Algerian' => 'Algerian',
            'American' => 'American',
            'Andorran' => 'Andorran',
            'Angolan' => 'Angolan',
            'Anguillan' => 'Anguillan',
            'Argentine' => 'Argentine',
            'Armenian' => 'Armenian',
            'Australian' => 'Australian',
            'Austrian' => 'Austrian',
            'Azerbaijani' => 'Azerbaijani',
            'Bahamian' => 'Bahamian',
            'Bahraini' => 'Bahraini',
            'Bangladeshi' => 'Bangladeshi',
            'Barbadian' => 'Barbadian',
            'Belarusian' => 'Belarusian',
            'Belgian' => 'Belgian',
            'Belizean' => 'Belizean',
            'Beninese' => 'Beninese',
            'Bermudian' => 'Bermudian',
            'Bhutanese' => 'Bhutanese',
            'Bolivian' => 'Bolivian',
            'Botswanan' => 'Botswanan',
            'Brazilian' => 'Brazilian',
            'British' => 'British',
            'British Virgin Islander' => 'British Virgin Islander',
            'Bruneian' => 'Bruneian',
            'Bulgarian' => 'Bulgarian',
            'Burkinan' => 'Burkinan',
            'Burmese' => 'Burmese',
            'Burundian' => 'Burundian',
            'Cambodian' => 'Cambodian',
            'Cameroonian' => 'Cameroonian',
            'Canadian' => 'Canadian',
            'Cape Verdean' => 'Cape Verdean',
            'Cayman Islander' => 'Cayman Islander',
            'Central African' => 'Central African',
            'Chadian' => 'Chadian',
            'Chilean' => 'Chilean',
            'Chinese' => 'Chinese',
            'Citizen of Antigua and Barbuda' => 'Citizen of Antigua and Barbuda',
            'Citizen of Bosnia and Herzegovina' => 'Citizen of Bosnia and Herzegovina',
            'Citizen of Guinea-Bissau' => 'Citizen of Guinea-Bissau',
            'Citizen of Kiribati' => 'Citizen of Kiribati',
            'Citizen of Seychelles' => 'Citizen of Seychelles',
            'Citizen of the Dominican Republic' => 'Citizen of the Dominican Republic',
            'Citizen of Vanuatu' => 'Citizen of Vanuatu',
            'Colombian' => 'Colombian',
            'Comoran' => 'Comoran',
            'Congolese (Congo)' => 'Congolese (Congo)',
            'Congolese (DRC)' => 'Congolese (DRC)',
            'Cook Islander' => 'Cook Islander',
            'Costa Rican' => 'Costa Rican',
            'Croatian' => 'Croatian',
            'Cuban' => 'Cuban',
            'Cymraes' => 'Cymraes',
            'Cymro' => 'Cymro',
            'Cypriot' => 'Cypriot',
            'Czech' => 'Czech',
            'Danish' => 'Danish',
            'Djiboutian' => 'Djiboutian',
            'Dominican' => 'Dominican',
            'Dutch' => 'Dutch',
            'East Timorese' => 'East Timorese',
            'Ecuadorean' => 'Ecuadorean',
            'Egyptian' => 'Egyptian',
            'Emirati' => 'Emirati',
            'English' => 'English',
            'Equatorial Guinean' => 'Equatorial Guinean',
            'Eritrean' => 'Eritrean',
            'Estonian' => 'Estonian',
            'Ethiopian' => 'Ethiopian',
            'Faroese' => 'Faroese',
            'Fijian' => 'Fijian',
            'Filipino' => 'Filipino',
            'Finnish' => 'Finnish',
            'French' => 'French',
            'Gabonese' => 'Gabonese',
            'Gambian' => 'Gambian',
            'Georgian' => 'Georgian',
            'German' => 'German',
            'Ghanaian' => 'Ghanaian',
            'Gibraltarian' => 'Gibraltarian',
            'Greek' => 'Greek',
            'Greenlandic' => 'Greenlandic',
            'Grenadian' => 'Grenadian',
            'Guamanian' => 'Guamanian',
            'Guatemalan' => 'Guatemalan',
            'Guinean' => 'Guinean',
            'Guyanese' => 'Guyanese',
            'Haitian' => 'Haitian',
            'Honduran' => 'Honduran',
            'Hong Konger' => 'Hong Konger',
            'Hungarian' => 'Hungarian',
            'Icelandic' => 'Icelandic',
            'Indian' => 'Indian',
            'Indonesian' => 'Indonesian',
            'Iranian' => 'Iranian',
            'Iraqi' => 'Iraqi',
            'Irish' => 'Irish',
            'Israeli' => 'Israeli',
            'Italian' => 'Italian',
            'Ivorian' => 'Ivorian',
            'Jamaican' => 'Jamaican',
            'Japanese' => 'Japanese',
            'Jordanian' => 'Jordanian',
            'Kazakh' => 'Kazakh',
            'Kenyan' => 'Kenyan',
            'Kittitian' => 'Kittitian',
            'Kosovan' => 'Kosovan',
            'Kuwaiti' => 'Kuwaiti',
            'Kyrgyz' => 'Kyrgyz',
            'Lao' => 'Lao',
            'Latvian' => 'Latvian',
            'Lebanese' => 'Lebanese',
            'Liberian' => 'Liberian',
            'Libyan' => 'Libyan',
            'Liechtenstein citizen' => 'Liechtenstein citizen',
            'Lithuanian' => 'Lithuanian',
            'Luxembourger' => 'Luxembourger',
            'Macanese' => 'Macanese',
            'Macedonian' => 'Macedonian',
            'Malagasy' => 'Malagasy',
            'Malawian' => 'Malawian',
            'Malaysian' => 'Malaysian',
            'Maldivian' => 'Maldivian',
            'Malian' => 'Malian',
            'Maltese' => 'Maltese',
            'Marshallese' => 'Marshallese',
            'Martiniquais' => 'Martiniquais',
            'Mauritanian' => 'Mauritanian',
            'Mauritian' => 'Mauritian',
            'Mexican' => 'Mexican',
            'Micronesian' => 'Micronesian',
            'Moldovan' => 'Moldovan',
            'Monegasque' => 'Monegasque',
            'Mongolian' => 'Mongolian',
            'Montenegrin' => 'Montenegrin',
            'Montserratian' => 'Montserratian',
            'Moroccan' => 'Moroccan',
            'Mosotho' => 'Mosotho',
            'Mozambican' => 'Mozambican',
            'Namibian' => 'Namibian',
            'Nauruan' => 'Nauruan',
            'Nepalese' => 'Nepalese',
            'New Zealander' => 'New Zealander',
            'Nicaraguan' => 'Nicaraguan',
            'Nigerian' => 'Nigerian',
            'Nigerien' => 'Nigerien',
            'Niuean' => 'Niuean',
            'North Korean' => 'North Korean',
            'Northern Irish' => 'Northern Irish',
            'Norwegian' => 'Norwegian',
            'Omani' => 'Omani',
            'Pakistani' => 'Pakistani',
            'Palauan' => 'Palauan',
            'Palestinian' => 'Palestinian',
            'Panamanian' => 'Panamanian',
            'Papua New Guinean' => 'Papua New Guinean',
            'Paraguayan' => 'Paraguayan',
            'Peruvian' => 'Peruvian',
            'Pitcairn Islander' => 'Pitcairn Islander',
            'Polish' => 'Polish',
            'Portuguese' => 'Portuguese',
            'Prydeinig' => 'Prydeinig',
            'Puerto Rican' => 'Puerto Rican',
            'Qatari' => 'Qatari',
            'Romanian' => 'Romanian',
            'Russian' => 'Russian',
            'Rwandan' => 'Rwandan',
            'Salvadorean' => 'Salvadorean',
            'Sammarinese' => 'Sammarinese',
            'Samoan' => 'Samoan',
            'Sao Tomean' => 'Sao Tomean',
            'Saudi Arabian' => 'Saudi Arabian',
            'Scottish' => 'Scottish',
            'Senegalese' => 'Senegalese',
            'Serbian' => 'Serbian',
            'Sierra Leonean' => 'Sierra Leonean',
            'Singaporean' => 'Singaporean',
            'Slovak' => 'Slovak',
            'Slovenian' => 'Slovenian',
            'Solomon Islander' => 'Solomon Islander',
            'Somali' => 'Somali',
            'South African' => 'South African',
            'South Korean' => 'South Korean',
            'South Sudanese' => 'South Sudanese',
            'Spanish' => 'Spanish',
            'Sri Lankan' => 'Sri Lankan',
            'St Helenian' => 'St Helenian',
            'St Lucian' => 'St Lucian',
            'Stateless' => 'Stateless',
            'Sudanese' => 'Sudanese',
            'Surinamese' => 'Surinamese',
            'Swazi' => 'Swazi',
            'Swedish' => 'Swedish',
            'Swiss' => 'Swiss',
            'Syrian' => 'Syrian',
            'Taiwanese' => 'Taiwanese',
            'Tajik' => 'Tajik',
            'Tanzanian' => 'Tanzanian',
            'Thai' => 'Thai',
            'Togolese' => 'Togolese',
            'Tongan' => 'Tongan',
            'Trinidadian' => 'Trinidadian',
            'Tristanian' => 'Tristanian',
            'Tunisian' => 'Tunisian',
            'Turkish' => 'Turkish',
            'Turkmen' => 'Turkmen',
            'Turks and Caicos Islander' => 'Turks and Caicos Islander',
            'Tuvaluan' => 'Tuvaluan',
            'Ugandan' => 'Ugandan',
            'Ukrainian' => 'Ukrainian',
            'Uruguayan' => 'Uruguayan',
            'Uzbek' => 'Uzbek',
            'Vatican citizen' => 'Vatican citizen',
            'Venezuelan' => 'Venezuelan',
            'Vietnamese' => 'Vietnamese',
            'Vincentian' => 'Vincentian',
            'Wallisian' => 'Wallisian',
            'Welsh' => 'Welsh',
            'Yemeni' => 'Yemeni',
            'Zambian' => 'Zambian',
            'Zimbabwean' => 'Zimbabwean',
        ];
    }

    public function generateRelationships()
    {
        return [
            'Spouse' => 'Spouse',
            'Mother' => 'Mother',
            'Father' => 'Father',
            'Brother' => 'Brother',
            'Sister' => 'Sister',
            'Uncle' => 'Uncle',
            'Aunt' => 'Aunt',
            'Son' => 'Son',
            'Daughter' => 'Daughter',
            'Friend' => 'Friend',
            'Colleague' => 'Colleague',
            'Others' => 'Others',
        ];
    }

    public function generateIDTypes()
    {
        return [
            'National ID' => 'National ID',
            'Refugee ID' => 'Refugee ID',
            'Passport' => 'Passport',
            'Driver License' => 'Driver License',
        ];
    }

    public function generateAccountStatus()
    {
        return [
            'Active' => 'Active',
            'Inactive' => 'Inactive',
        ];
    }

    public function generateAccountTypes()
    {
        return [
            'Administrator' => 'Administrator',
            'Employee' => 'Employee',
            'Client' => 'Client',
        ];
    }

    public function generateStaffAccountTypes()
    {
        return [
            'Administrator' => 'Administrator',
            'Employee' => 'Employee',
            'Super Administrator' => 'Super Administrator',
        ];
    }

    public function generateInterestTypes()
    {
        return [
            'Flat' => 'Flat',
            'Reducing' => 'Reducing',
        ];
    }

    public function loanInterestPeriods()
    {
        return [
            'day'    => 'Per Day',
            'week'   => 'Per Week',
            'month'  => 'Per Month',
            'year'   => 'Per Year'
        ];
    }

    public function getLoanFrequencyDurations()
    {
        return [
            'days'    => 'days',
            'weeks'   => 'weeks',
            'months'  => 'months',
            'years'   => 'years'
        ];
    }

    public function generateLoanRepaymentDurations()
    {
        return [
            'day(s)'    => 'day(s)',
            'week(s)'   => 'week(s)',
            'month(s)'  => 'month(s)',
            'year(s)'   => 'year(s)'
        ];
    }

    public function generateLoanApplicationLevels()
    {
        return [
            'Credit Officer' => 'Credit Officer',
            'Supervisor' => 'Supervisor',
            'Operations Officer' => 'Operations Officer',
            'Accounts Officer' => 'Accounts Officer',
        ];
    }

    public function generateLoanApplicationActions()
    {
        return [
            // 'Processing' => 'Processing',
            // 'Review' => 'Review',
            'Approved' => 'Approved',
            // 'Disbursed' => 'Disbursed',
            'Declined' => 'Declined',
            // 'Cancelled' => 'Cancelled'
        ];
    }

    public function generateLoanApplicationStatus()
    {
        return [
            // 'Pending' => 'Pending',
            // 'Processing' => 'Processing',
            'Declined' => 'Declined',
            'Approved' => 'Approved',
            // 'Disbursed' => 'Disbursed',
            // 'Cancelled' => 'Cancelled'
        ];
    }

    public function generateDisbursementStatus()
    {
        return [
            'Running' => 'Running',
            'Arrears' => 'Arrears',
            'Cleared' => 'Cleared',
            'Expired' => 'Expired',
        ];
    }

    public function  generateAppointments()
    {
        return [
            'Full-Time' => 'Full-Time',
            'Part-Time' => 'Part-Time',
            'Others' => 'Others',
        ];
    }

    public function generateRepayments()
    {
        return [
            'Daily' => 'Daily',
            'Weekly' => 'Weekly',
            'Bi-Weekly' => 'Bi-Weekly',
            'Monthly' => 'Monthly',
            'Bi-Monthly' => 'Bi-Monthly',
            'Quarterly' => 'Quarterly',
            'Termly' => 'Termly',
            'Bi-Annual' => 'Bi-Annual',
            'Annually' => 'Annually',
        ];
    }

    public function generateChargeOptions($option)
    {
        if ($option == 'mode') {
            return [
                'Manual' => 'Manual',
                'Auto' => 'Auto',
            ];
        }
        if ($option == 'frequency') {
            return [
                'One-Time' => 'One-Time',
                'Weekly' => 'Weekly',
                // 'Bi-Weekly' => 'Bi-Weekly',
                'Monthly' => 'Monthly',
                // 'Bi-Monthly' => 'Bi-Monthly',
                // 'Quarterly' => 'Quarterly',
                // 'Termly' => 'Termly',
                // 'Bi-Annual' => 'Bi-Annual',
                'Annually' => 'Annually',
            ];
        }

        if ($option == 'status') {
            return [
                'Active' => 'Active',
                'Inactive' => 'Inactive',
            ];
        }

        if ($option == 'method') {
            return [
                'Amount' => 'Amount',
                'Percent' => 'Percent',
            ];
        }
    }

    public function generateIntervals($frequency)
    {
        switch (strtolower($frequency)) {
            case 'daily':
                $interval = 1;
                $grace_period = 1;
                break;
            case 'weekly':
                $interval = 1 / 4;
                $grace_period = 7;
                break;
            case 'bi-weekly':
                $interval = 1 / 2;
                $grace_period = 14;
                break;
            case 'monthly':
                $interval = 1;
                $grace_period = 30;
                break;
            case 'bi-monthly':
                $interval = 2;
                $grace_period = 60;
                break;
            case 'quarterly':
                $interval = 3;
                $grace_period = 90;
                break;
            case 'termly':
                $interval = 4;
                $grace_period = 120;
                break;
            case 'bi-annual':
                $interval = 6;
                $grace_period = 180;
                break;
            case 'annually':
                $interval = 12;
                $grace_period = 365;
                break;
        }
        $data = [
            'interval' => $interval,
            'grace_period' => $grace_period,
        ];
        return $data;
    }

    public function generateReference($length = 8)
    {
        $nums = date('YmdHis');
        return substr(str_shuffle($nums), 0, $length);
    }

    public function countResults($table, $condition)
    {
        // set desired table 
        $builder = $this->db->table($table);
        // count all results from desired table that meet the condition
        $count = $builder->where($condition)->countAllResults();
        return $count;
    }

    public function sum_column($table, $column, $where, $menu = null)
    {
        $builder = $this->db->table($table);
        $builder->selectSum($column)->where($where);
        $result = $builder->get()->getRow();
        $finalSum = 0;
        if ($result) {
            $sum = $result->$column;
            # check the menu
            if (!empty($menu)) {
                $finalSum = $sum;
            } else {
                if ($sum >= 1000000000) {
                    $sum /= 1000000000;
                    $finalSum = round($sum, 4) . 'Bn';
                }
                if ($sum >= 1000000) {
                    $sum /= 1000000;
                    $finalSum = round($sum, 4) . 'M';
                }
                if ($sum >= 1000) {
                    # $sum /= 1000;
                    $finalSum = number_format($sum);
                }
            }
        }

        return $finalSum;
    }

    public function get_topPerformers($table, $column, $limit = 5)
    {
        // set desired table
        $builder = $this->db->table($table);
        /**
         * select desired column, count the times it appears based on id for its row in the table
         * group the rows in the table based on the column
         * order the results based on column with highest count
         * then limit by the top limit
         */
        $builder->select($column . ', COUNT(id) AS rowCount')->groupBy($column)
            ->orderBy('rowCount', 'DESC')->limit($limit);

        // fetch the results fitting the above
        $results = $builder->get()->getResult();
        $topResults = [];
        if ($results) {
            // store the column and its counter in $topResults
            foreach ($results as $item) {
                $itemId = $item->$column;
                $count = $item->rowCount;

                // Check if the key already exists in $topResults
                if (!isset($topResults[$itemId])) {
                    $topResults[$itemId] = $count;
                }
            }
        }

        return $topResults;
    }

    public function convert_number($number)
    {
        if (($number < 0) || ($number > 999999999)) {
            return "Number is out of range";
        }
        $Gn = floor($number / 1000000);
        /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);
        /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        /* Tens (deca) */
        $n = $number % 10;
        /* Ones */
        $res = "";
        if ($Gn) {
            $res .= $this->convert_number($Gn) .  "Million";
        }
        if ($kn) {
            $res .= (empty($res) ? "" : " ") . $this->convert_number($kn) . " Thousand";
        }
        if ($Hn) {
            $res .= (empty($res) ? "" : " ") . $this->convert_number($Hn) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }
            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];
                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }
        if (empty($res)) {
            $res = "zero";
        }
        return $res;
    }
}
