<?php

namespace Reddit\Entity;

/**
 * @Table(messages)
 * @Timestamps
 */
class Message
{
    /**
     * @Id
     */
    public $id;

    /**
     * @Relation(type=ManyToOne, target=Reddit:User, column=from_id)
     */
    public $from;

    /**
     * @Relation(type=ManyToOne, target=Reddit:User, column=to_id)
     */
    public $to;

    public $title;

    public $text;

    public $hasRead;

    /**
     * @Timestamp(created)
     */
    public $createdAt;

    /**
     * @Timestamp(updated)
     */
    public $updatedAt;
}
