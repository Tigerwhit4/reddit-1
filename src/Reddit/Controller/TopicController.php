<?php

namespace Reddit\Controller;

use Dionaea\Modules\Controller\Controller;
use Reddit\Entity\Subreddit,
    Reddit\Entity\Topic;

class TopicController extends Controller
{
    public function showAction($url, $title, $id)
    {
        $subreddit = $this->getEntityManager()
            ->getRepository('Reddit:Subreddit')->findByUrl($url);

        $topic = $this->getEntityManager()
            ->getRepository('Reddit:Topic')->find($id);

        return $this->render('@Reddit/showtopic.html', array(
            'subreddit' => $subreddit,
            'topic' => $topic,
        ));
    }
}
