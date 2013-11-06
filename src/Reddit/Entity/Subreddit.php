<?php

namespace Reddit\Entity;

/**
 * @Table(subreddits)
 * @Timestamps
 */
class Subreddit
{
    /**
     * @Id
     */
    public $id;

    public $name;

    public $url;

    /**
     * @Relation(type=OneToMany, target=Reddit:Topic, column=subreddit_id)
     */
    public $topics;

    /**
     * @Timestamp(created)
     */
    public $createdAt;

    /**
     * @Timestamp(updated)
     */
    public $updatedAt;
}
