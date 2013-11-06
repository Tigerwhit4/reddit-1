<?php

namespace Reddit\Entity;

use Dionaea\Arnica\Repository;

class TopicRepository extends Repository
{
    public function getLatestTopics($number)
    {
        return $this->getQuery()->orderBy('created_at desc')
            ->setLimit($number)->all();
    }
}
