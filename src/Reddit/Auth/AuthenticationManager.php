<?php

namespace Reddit\Auth;

use Sarracenia\Http\Request;
use Dionaea\Arnica\Repository;

class AuthenticationManager
{
    const USER_SESSION_KEY = 'user';

    private $request;

    private $repository;

    private $user;

    private $authenticated = false;

    public function __construct(Request $request, Repository $repository)
    {
        $this->request = $request;
        $this->repository = $repository;
    }

    public function isAuthenticated()
    {
        if ($this->authenticated) {
            return true;
        }

        return $this->checkAuthentication();
    }

    public function getUser()
    {
        $this->isAuthenticated();
        return $this->user;
    }

    private function checkAuthentication()
    {
        $session = $this->request->getSession();
        if (!$session->has(self::USER_SESSION_KEY)) {
            return false;
        }

        $userId = $session->get(self::USER_SESSION_KEY);
        $this->user = $this->repository->find($userId);
        return true;
    }
}
