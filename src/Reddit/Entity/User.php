<?php

namespace Reddit\Entity;

/**
 * @Table(users)
 * @Timestamps
 */
class User
{
    /**
     * @Id
     */
    public $id;

    public $username;

    public $password;

    public $emailadres;

    /**
     * @Relation(type=OneToMany, target=Reddit:Topic, column=user_id)
     */
    public $topics;

    /**
     * @Relation(type=OneToMany, target=Reddit:Comment, column=user_id)
     */
    public $comments;

    /**
     * @Relation(type=OneToMany, target=Reddit:TopicVote, column=user_id)
     */
    public $votes;

    /**
     * @Relation(type=OneToMany, target=Reddit:CommentVote, column=user_id)
     */
    public $commentVotes;

    /**
     * @Relation(type=OneToMany, target=Reddit:Message, column=to_id)
     */
    public $messages;

    /**
     * @Timestamp(created)
     */
    public $createdAt;

    /**
     * @Timestamp(updated)
     */
    public $updatedAt;

    public function getVoteStatusByTopic(Topic $topic)
    {
        foreach ($this->votes as $vote) {
            if ($vote->topic->id == $topic->id) {
                return $vote->status;
            }
        }

        return 0;
    }

    public function getVoteStatusByComment(Comment $comment)
    {
        foreach ($this->commentVotes as $vote) {
            if ($vote->comment->id == $comment->id) {
                return $vote->status;
            }
        }

        return 0;
    }
}
