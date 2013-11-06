<?php

namespace Reddit\Controller;

use Dionaea\Modules\Controller\Controller;

class FrontController extends Controller
{
    public function indexAction()
    {
        $topics = $this->getEntityManager()
            ->getRepository('Reddit:Topic')->getLatestTopics(5);

        return $this->render('@Reddit/front.html', array(
            'topics' => $topics,
        ));
    }
}
