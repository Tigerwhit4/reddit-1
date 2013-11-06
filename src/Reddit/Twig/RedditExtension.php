<?php

namespace Reddit\Twig;

use Dionaea\Arnica\EntityManager;
use Reddit\Entity\User,
    Reddit\Auth\AuthenticationManager;

class RedditExtension extends \Twig_Extension
{
    private $entityManager;

    private $authenticationManager;

    public function __construct(EntityManager $entityManager, AuthenticationManager $authenticationManager)
    {
        $this->entityManager = $entityManager;
        $this->authenticationManager = $authenticationManager;
    }

    public function getName()
    {
        return 'reddit';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('title', array($this, 'parseTitle')),
            new \Twig_SimpleFunction('getSubReddits', array($this, 'getSubReddits')),
            new \Twig_SimpleFunction('diff', array($this, 'diff')),
            new \Twig_SimpleFunction('sortComments', array($this, 'sortComments')),
            new \Twig_SimpleFunction('dump', function($var) { var_dump($var); }),
            new \Twig_SimpleFunction('isAuthenticated', array($this->authenticationManager, 'isAuthenticated')),
            new \Twig_SimpleFunction('getUser', array($this->authenticationManager, 'getUser')),
            new \Twig_SimpleFunction('getUnreadMessages', array($this, 'getUnreadMessages')),
            new \Twig_SimpleFunction('changeToRead', array($this, 'changeToRead')),
        );
    }

    public static function parseTitle($title)
    {
        $title = trim($title);
        $title = str_replace(' ', '_', $title);
        $title = str_replace('?', '', $title);
        $title = str_replace('!', '', $title);
        $title = str_replace('.', '', $title);
        $title = str_replace('/', '', $title);
        return $title;
    }

    public function getSubReddits()
    {
        return $this->entityManager->getRepository('Reddit:Subreddit')->findAll();
    }

    public function diff(\DateTime $date)
    {
        $interval = $date->diff(new \DateTime());
        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;
        $hours = $interval->h;
        $minutes = $interval->i;
        if ($years >= 1)
        {
            return $years .' '. (($years == 1) ? 'year' : 'years') .' ago';
        }
        if ($months >= 1)
        {
            return $months .' '. (($months == 1) ? 'month' : 'months') .' ago';
        }
        if ($days >= 1)
        {
            return $days .' '. (($days == 1) ? 'day' : 'days') .' ago';
        }
        if ($hours >= 1)
        {
            return $hours .' '. (($hours == 1) ? 'hours' : 'hours') .' ago';
        }
        
        return $minutes .' '. (($minutes == 1) ? 'minute' : 'minutes') .' ago';
    }

    public function sortComments($comments, $parentId)
    {
        $sorted = array();
        foreach ($comments as $key => $comment) {
            if ($comment->commentId == $parentId) {
                $comment->children = $this->sortComments($comments, $comment->id);
                $sorted[] = $comment;
            }
        }
        return $sorted;
    }

    public function getUnreadMessages(User $user)
    {
        return $this->entityManager
            ->getRepository('Reddit:User')
            ->getUnreadMessages($user);
    }

    public function changeToRead($comments, $messages)
    {
        foreach ($comments as $comment) {
            $comment->hasRead = 1;
            $this->entityManager->save($comment);
        }

        foreach ($messages as $message) {
            $message->hasRead = 1;
            $this->entityManager->save($message);
        }
    }
}
