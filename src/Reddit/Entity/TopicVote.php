<?php

namespace Reddit\Entity;

/**
 * @Table(topic_votes)
 */
class TopicVote
{
    /**
     * @Id
     */
    public $id;

    /**
     * @Relation(type=ManyToOne, target=Reddit:Topic, column=topic_id)
     */
    public $topic;

    /**
     * @Relation(type=ManyToOne, target=Reddit:User, column=user_id)
     */
    public $user;

    public $status;
}
