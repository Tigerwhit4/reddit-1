<?php

namespace Reddit\Entity;

/**
 * @Table(comment_votes)
 */
class CommentVote
{
    /**
     * @Id
     */
    public $id;

    public $status;

    /**
     * @Relation(type=ManyToOne, target=Reddit:Comment, column=comment_id)
     */
    public $comment;

    /**
     * @Relation(type=ManyToOne, target=Reddit:User, column=user_id)
     */
    public $user;
}
