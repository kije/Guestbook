<?php


namespace kije\Guestbook\Views;


use kije\Guestbook\Models\Post;
use kije\Layouting\View;

class PostItem extends View
{
    /**
     * @var Post
     */
    public $post;

    public $replies;

    public $is_reply = false;

    public function render($return = false)
    {
        if (!empty($this->post)) {
            $this->replies = $this->post->getReplies();
            if (!empty($this->replies)) {
                foreach ($this->replies as $reply) {
                    $replyView = new self();
                    $replyView->post = $reply;
                    $replyView->is_reply = true;
                    $this->addChild($replyView, 'replies');
                }
            }
        }

        parent::render($return);
    }
}
