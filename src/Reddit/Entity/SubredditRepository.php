<?php

namespace Reddit\Entity;

use Dionaea\Arnica\Repository;

class SubredditRepository extends Repository
{
    public function findByUrl($url)
    {
        $entities = $this->getQuery()->setWhere('url', '=', '/'. $url)->all();

        if (count($entities) != 1) {
            throw new \RuntimeException(sprintf(
                'No subreddit found with url "%s"', $url
            ));
        }

        return $entities->first();
    }
}
