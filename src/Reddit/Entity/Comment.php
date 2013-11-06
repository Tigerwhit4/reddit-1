<?php

namespace Reddit\Entity;

/**
 * @Table(comments)
 * @Timestamps
 */
class Comment
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

    /**
     * @Relation(type=OneToMany, target=Reddit:CommentVote, column=comment_id)
     */
    public $commentVotes;

    public $hasRead;

    public $text;

    public $commentId;

    /**
     * @Timestamp(created)
     */
    public $createdAt;

    /**
     * @Timestamp(updated)
     */
    public $updatedAt;

    public function calculateCommentStatus()
    {
        $status = 0;
        foreach ($this->commentVotes as $vote) {
            $status += $vote->status;
        }
        return $status;
    }
}
