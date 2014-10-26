<?php
/**
 * Guestbook
 * @author: kije
 * @date: 9/7/14
 */

namespace kije\Guestbook\Routes;


use kije\Guestbook\Filters\LoggedIn;
use kije\Guestbook\inc\Guestbook;
use kije\Guestbook\Models\Post;
use kije\Guestbook\Views\Overview as OverviewView;
use kije\Guestbook\Views\PostItem;

class Overview extends Route
{

    /**
     * @var OverviewView
     */
    protected $view;

    public function __construct($uri = '/', $alias_uris = null, $alias_redirect = true)
    {
        parent::__construct($uri, new LoggedIn($uri), $alias_uris, $alias_redirect);
        $this->view = new OverviewView();
    }

    public function handle()
    {
        Guestbook::getLayout()->appendData('title', 'Overview', ' - ');
        Guestbook::getLayout()->addChild($this->view);

        $this->view->posts = Post::query()->where()->isNull('reply_to')->orderBy()->add('date', true)->findMany();
        if (!empty($this->view->posts)) {
            foreach ($this->view->posts as $post) {
                $postView = new PostItem();
                $postView->post = $post;
                $this->view->addChild($postView);
            }
        }
    }
}
