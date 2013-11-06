<?php

namespace Reddit\Entity;

use Dionaea\Arnica\Repository;

class CommentVoteRepository extends Repository
{
    public function getVote(User $user, Comment $comment)
    {
        $votes = $this->getQuery()->setWhere('user_id', '=', $user->id)
            ->setWhere('comment_id', '=', $comment->id)->all();

        if (count($votes) == 0) {
            return null;
        }

        return $votes->first();
    }
}
