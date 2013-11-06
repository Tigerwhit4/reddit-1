<?php

namespace Reddit\Controller;

use Dionaea\Modules\Controller\Controller;
use Reddit\Entity\Comment,
    Reddit\Entity\CommentVote,
    Reddit\Entity\Message,
    Reddit\Entity\Topic,
    Reddit\Entity\TopicVote,
    Reddit\Twig\RedditExtension;

class ApiController extends Controller
{
    public function voteTopic()
    {
        $em = $this->getEntityManager();

        $user = $this->get('auth')->getUser();
        $topic = $em->getRepository('Reddit:Topic')
            ->find($this->param('topicid'));

        $status = $this->param('status');

        $vote = $em->getRepository('Reddit:TopicVote')
            ->getVote($user, $topic);

        if ($vote === null) {
            $vote = new TopicVote();
            $vote->user = $user;
            $vote->topic = $topic;
            $vote->status = $status;

            $changeStatus = $status;
        } else {
            $voteStatus = (int) $vote->status;
            if ($voteStatus == 0) {
                $changeStatus = $status;
                $vote->status = $status;
            } else {
                $changeStatus = -1 * $voteStatus;
                $vote->status = 0;
            }
        }
        $em->save($vote);

        return $this->json(array(
            'status' => $vote->status,
            'changeStatus' => $changeStatus
        ));
    }

    public function voteComment()
    {
        $em = $this->getEntityManager();

        $user = $this->get('auth')->getUser();
        $comment = $em->getRepository('Reddit:Comment')
            ->find($this->param('commentid'));

        $status = $this->param('status');

        $vote = $em->getRepository('Reddit:CommentVote')
            ->getVote($user, $comment);

        if ($vote === null) {
            $vote = new CommentVote();
            $vote->user = $user;
            $vote->comment = $comment;
            $vote->status = $status;

            $changeStatus = $status;
        } else {
            $voteStatus = (int) $vote->status;
            if ($voteStatus == 0) {
                $changeStatus = $status;
                $vote->status = $status;
            } else {
                $changeStatus = -1 * $voteStatus;
                $vote->status = 0;
            }
        }
        $em->save($vote);

        return $this->json(array(
            'status' => $vote->status,
            'changeStatus' => $changeStatus
        ));
    }

    public function addComment()
    {
        $em = $this->getEntityManager();
        $session = $this->get('request')->getSession();

        $user = $this->get('auth')->getUser();
        $topic = $em->getRepository('Reddit:Topic')
            ->find($this->param('topic_id'));
        $parentId = $this->param('comment_id');
        $text = $this->param('text');
        $redirect = $this->param('redirect');

        if (strlen($text) < 2) {
            $session->set('errors', array('Text field needs to be at least 2 characters long'));
            return $this->redirect($redirect);
        }

        $comment = new Comment();
        $comment->commentId = $parentId;
        $comment->topic = $topic;
        $comment->user = $user;
        $comment->text = $text;
        $comment->hasRead = 0;
        $em->save($comment);
        return $this->redirect($redirect);
    }

    public function addPost()
    {
        $em = $this->getEntityManager();
        $session = $this->get('request')->getSession();

        $user = $this->get('auth')->getUser();
        $subreddit = $em->getRepository('Reddit:Subreddit')
            ->find($this->param('subreddit_id'));
        $title = $this->param('title');
        $text = $this->param('text');
        $redirect = $this->param('redirect');

        if (strlen($title) < 5 || strlen($text) < 2) {
            $session->set('errors', array('Title needs to be at least 5 characters long',
                'Text field needs to be at least 2 characters long'));
            return $this->redirect($redirect);
        }

        $topic = new Topic();
        $topic->subreddit = $subreddit;
        $topic->user = $user;
        $topic->title = $title;
        $topic->text = $text;
        $em->save($topic);
        return $this->redirect(
            $subreddit->url .'/'.
            RedditExtension::parseTitle($title)
            .'/'. $topic->id
        );
    }

    public function sendPmAction()
    {
        $em = $this->getEntityManager();
        $session = $this->get('request')->getSession();

        $to = $em->getRepository('Reddit:User')
            ->find($this->param('user_id'));
        $title = $this->param('title');
        $text = $this->param('text');
        $redirect = $this->param('redirect');

        if (strlen($title) < 5 || strlen($text) < 2) {
            $session->set('errors', array('Title needs to be at least 5 characters long',
                'Text field needs to be at least 2 characters long'));
            return $this->redirect($redirect);
        }

        $message = new Message();
        $message->from = $this->get('auth')->getUser();
        $message->to = $to;
        $message->title = $title;
        $message->text = $text;
        $message->hasRead = 0;
        $em->save($message);

        $session->set('alerts', array('Message send'));
        return $this->redirect($redirect);
    }
}
