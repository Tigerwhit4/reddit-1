<?php

namespace Reddit\Controller;

use Dionaea\Modules\Controller\Controller;
use Reddit\Entity\Subreddit,
    Reddit\Entity\User;

class UserController extends Controller
{
    public function loginAction()
    {
        $session = $this->get('request')->getSession();

        $redirect = $this->param('redirect');
        $username = $this->param('username');
        $password = $this->param('password');

        $user = $this->getEntityManager()
            ->getRepository('Reddit:User')->checkUser($username, $password);

        if ($user === false) {
            $session->set('errors', array('Username and/or password wrong'));
        } else {
            $session->set('user', $user->id);
        }

        return $this->redirect($redirect);
    }

    public function registerAction()
    {
        $session = $this->get('request')->getSession();

        $redirect = $this->param('redirect');
        $username = $this->param('username');
        $password = $this->param('password');

        if (strlen($username) < 2 || strlen($password) < 2) {
            $session->set('errors', array('Username and password need to be at least 2 characters long'));
            return $this->redirect($redirect);
        }

        if ($this->getEntityManager()->getRepository('Reddit:User')->usernameExists($username)) {
            $session->set('errors', array('Username already exists'));
            return $this->redirect($redirect);
        }

        $user = new User();
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_BCRYPT);
        $this->getEntityManager()->save($user);

        $session->set('user', $user->id);
        return $this->redirect($redirect);
    }

    public function logoutAction()
    {
        $session = $this->get('request')->getSession();
        $session->remove('user');
        return $this->redirect('/');
    }

    public function messagesAction()
    {
        $em = $this->getEntityManager();
        $user = $this->get('auth')->getUser();

        $comments = $em->getRepository('Reddit:User')
            ->getCommentMessages($user);

        return $this->render('@Reddit/messages.html', array(
            'comments' => $comments,
        ));
    }

    public function showAction($username)
    {
        $user = $this->getEntityManager()
            ->getRepository('Reddit:User')
            ->getByUsername($username);

        return $this->render('@Reddit/user.html', array(
            'user' => $user,
        ));
    }
}
