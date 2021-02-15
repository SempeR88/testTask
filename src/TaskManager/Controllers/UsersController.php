<?php

namespace TaskManager\Controllers;

use TaskManager\Exceptions\InvalidArgumentException;
use TaskManager\Services\UsersAuthService;
use TaskManager\Models\Users\User;

class UsersController extends AbstractController
{
    public function login()
    {
        if (!empty($_POST)) {
	        try {
	            $user = User::login($_POST);
	            UsersAuthService::createToken($user);
	            header('Location: /');
	            exit();
	        } catch (InvalidArgumentException $e) {
	            $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
	            return;
	        }
	    }

	    $this->view->renderHtml('users/login.php');
    }

    public function logout()
    {
        setcookie('token', '', -1, '/', '', false, true);
        header('Location: /');
    }
}