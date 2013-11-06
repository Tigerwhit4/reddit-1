<?php

namespace Reddit\Entity;

use Dionaea\Arnica\Repository;

class UserRepository extends Repository
{
    public function checkUser($username, $password)
    {
        $user = $this->getByUsername($username);
        if ($user === null) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }

    public function usernameExists($username)
    {
        $entities = $this->getQuery()->setWhere('username', '=', $username)->all();
        return count($entities) > 0;
    }

    public function getCommentMessages(User $user)
    {
        $repository = $this->getEntityManager()
            ->getRepository('Reddit:Comment');
        $result = array();

        foreach (
            $repository->getQuery()
            ->setWhere('user_id', '=', $user->id)
            ->orderBy('id desc')->all()
            as $comment) {

            $comments = $repository->getQuery()
                ->setWhere('comment_id', '=', $comment->id)
                ->all();
            $result = array_merge($result, $comments->all());
        }

        return $result;
    }

    public function getByUsername($username)
    {
        return $this->getQuery()->setWhere('username', '=', $username)->all()->first();
    }

    public function getUnreadMessages(User $user)
    {
        $result = 0;

        foreach ($this->getCommentMessages($user) as $comment) {
            if ($comment->hasRead == 0) {
                $result++;
            }
        }

        foreach ($user->messages as $message) {
            if ($message->hasRead == 0) {
                $result++;
            }
        }

        return $result;
    }
}
