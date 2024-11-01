<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use \App\Models\UserModel;

class Login extends BaseController
{
    private $user;
    private $session;
    /**
     * constructor
     */
    public function __construct()
    {
        $this->user = new UserModel();
        $this->session = session();
    }

    public function index()
    {
        if ($this->request->getMethod() == 'post') {
            $inputs = $this->validate([
                'email' => 'required|valid_email',
                'password' => 'required|min_length[5]'
            ]);

            if (!$inputs) {
                return view('layout/login', [
                    'title' => 'Admin Login',
                    'validation' => $this->validator
                ]);
            }

            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            $user = $this->user->where('email', $email)->first();

            if ($user) {

                $pass = $user['password'];
                $authPassword = password_verify($password, $pass);

                if ($authPassword) {
                    $sessionData = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'loggedIn' => true,
                    ];

                    $this->session->set($sessionData);
                    # set user information on the session
                    $this->setUserSession($user);
                    # redirect user to access the dashboard
                    return redirect()->to('admin/dashboard');
                }

                session()->setFlashdata('failed', 'Failed! incorrect password');
                return redirect()->to(base_url('admin/login'));
            }

            session()->setFlashdata('failed', 'Failed! incorrect email');
            return redirect()->to(base_url('admin/login'));
        }

        return view('layout/login', [
            'title' => 'Admin Login'
        ]);
    }

    private function setUserSession($user)
    {
        $data = [
            'id' => $user['id'],
            'name' => $user['name'],
            'mobile' => $user['mobile'],
            'email' => $user['email'],
            'loggedIn' => true,
        ];

        session()->set($data);
        return true;
    }
}
