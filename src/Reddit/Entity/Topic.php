<?php

namespace Reddit\Entity;

/**
 * @Table(topics)
 * @Timestamps
 */
class Topic
{
    /**
     * @Id
     */
    public $id;

    /**
     * @Relation(type=ManyToOne, target=Reddit:Subreddit, column=subreddit_id)
     */
    public $subreddit;

    /**
     * @Relation(type=ManyToOne, target=Reddit:User, column=user_id)
     */
    public $user;

    /**
     * @Relation(type=OneToMany, target=Reddit:TopicVote, column=topic_id)
     */
    public $votes;

    /**
     * @Relation(type=OneToMany, target=Reddit:Comment, column=topic_id)
     */
    public $comments;

    public $title;

    public $text;

    /**
     * @Timestamp(created)
     */
    public $createdAt;

    /**
     * @Timestamp(updated)
     */
    public $updatedAt;

    public function calculateTopicStatus()
    {
        $status = 0;
        foreach ($this->votes as $vote) {
            $status += $vote->status;
        }
        return $status;
    }
}
