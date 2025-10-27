<?php

namespace App\controllers;

use App\Request;
use App\Session;
use App\models\DB;
use App\Auth;
use Exception;
use Respect\Validation\Validator;

class auth_controller
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }
    public function showLoginForm()
    {
        Auth::guest();
        $cwd = getcwd();
        include $cwd . '/src/login.php';
    }

    public function signin()
    {

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $db = (new DB());

        try {
            $user = $db->retrieveUser($username, $password);
        } catch (Exception $e) {
            Session::set('error', "User not found " . $e->getMessage());
            Request::redirect('/login');
            return;
        }

        consoleLog(json_encode($user));

        //verifies the password against the password hash stored in the database
        if (password_verify($password, $user['password'])) {
            Session::set('user', [
                'username' => $user['username'] //add the current authenticated user's username into the session if success
            ]);
            Request::redirect('/dashboard');  //redirect the user into the dashboard page if password checking is successful
        } else {
            //actually redirect back into the login page with validation error
            Session::set('error', 'Mismatch Credentials'); //Dont make this as a toast, instead this should be shown in the form card.
            Request::redirect('/error');
        }

    }


    public function signup(): void
    {
        $cwd = getcwd();
        $cwd = getcwd();
        include $cwd . '/src/views/signup.php';
        include $cwd . '/src/views/signup.php';
    }

    public function register(): void
    {
        $success = $this->request->validate([
            'username' => Validator::stringVal()->min(1),
            'email' => Validator::email()->min(1),
            'password' => Validator::stringVal()->min(1),
            'confirm_password' => Validator::stringVal()->min(1),
        ]);
        if (!$success) {
            Request::redirect('/signup');
        }

        $db = (new DB());
        $requestData = $this->request->validated();
        $success = $db->createUser($requestData['username'], $requestData['password'], $requestData['email']);
        if ($success) {
            Session::set('user', [
                'username' => $requestData['username'] //add the current authenticated user's username into the session if success
            ]);
            Request::redirect('/dashboard'); //redirect user to the dashboard if successful authentication
        } else {
            Request::redirect('/error'); //redirect user to the error page if unsuccessful authentication
        }
    }

    public function logout(): void
    {
        Session::unset('user');
        Request::redirect('/login');
    }

}
