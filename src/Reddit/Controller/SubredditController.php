<?php

namespace Reddit\Controller;

use Dionaea\Modules\Controller\Controller;
use Reddit\Entity\Subreddit;

class SubredditController extends Controller
{
    public function showAction($url)
    {
        $subreddit = $this->getEntityManager()
            ->getRepository('Reddit:Subreddit')->findByUrl($url);

        return $this->render('@Reddit/subreddit.html', array(
            'subreddit' => $subreddit,
        ));
    }
}
