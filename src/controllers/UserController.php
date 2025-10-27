<?php

namespace App\controllers;

use App\Request;
use App\Session;
use App\Views;
use App\models\User;
use Exception;
use Respect\Validation\Validator;

class UserController
{
    private $request;
    public function __construct()
    {
        $this->request = new Request();
    }

    public function index()
    {

        $userModel = new User();
        $users = $userModel->getUsers();

        consoleLog("Hello world");
        consoleLog(json_encode($users));


        Views::render('users.php', ['users' => $users]);
    }
    public function show()
    {
        Views::render('users_add.php');

    }
    public function indexDestroy()
    {
        Views::render('users_delete.php');
    }
    public function indexEdit()
    {
        Views::render('users_edit.php');
    }

    public function store()
    {
        $success = $this->request->validate([
            'password' => Validator::stringVal()->min(1),
            'email' => Validator::email()->min(1),
            'username' => Validator::stringVal()->min(1),
        ]);

        if (!$success) {
            consoleLog("Error in data validation");
            Request::redirect('/error');
        }
        $userModel = new User();
        try {
            $validatedData = $this->request->validated();
            $userModel->addUser($validatedData);
            Request::redirect('/users');
        } catch (Exception $e) {
            consoleLog($e->getMessage());
        }

    }

    public function destroy()
    {

        $success = $this->request->validate([
            'user_id' => Validator::numericVal()->min(1),
        ]);
        if (!$success) {
            consoleLog("Error in data validation within user delete");
            Request::redirect('/users/delete');
        }
        $validatedData = $this->request->validated();
        $userModel = new User();

        try {
            $userModel->deleteUser($validatedData['user_id']);
            Request::redirect('/users');
            return;
        } catch (Exception $e) {
            consoleLog($e->getMessage());
            Session::set('error', 'Error deleting user');
        }
        Request::redirect('/users/delete');
    }
    public function update()
    {

        $success = $this->request->validate([
            'id' => Validator::numericVal()->min(1),
            'email' => Validator::email()->min(1),
            'username' => Validator::stringVal()->min(1),
        ]);
        if (!$success) {
            consoleLog("Error in data validation within user delete");
            Request::redirect('/users/edit');
        }
        $validatedData = $this->request->validated();
        $userModel = new User();

        try {
            $userModel->updateUser($validatedData);
            Request::redirect('/users');
            return;
        } catch (Exception $e) {
            consoleLog($e->getMessage());
            Session::set('error', 'Error updating user');
            Request::redirect('/users/edit');
        }

    }

}
