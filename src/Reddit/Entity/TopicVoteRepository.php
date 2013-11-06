<?php

namespace Reddit\Entity;

use Dionaea\Arnica\Repository;

class TopicVoteRepository extends Repository
{
    public function getVote(User $user, Topic $topic)
    {
        $votes = $this->getQuery()->setWhere('user_id', '=', $user->id)
            ->setWhere('topic_id', '=', $topic->id)->all();

        if (count($votes) == 0) {
            return null;
        }

        return $votes->first();
    }
}
