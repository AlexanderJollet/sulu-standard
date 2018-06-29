<?php

namespace AppBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Navigation\Navigation;
use Sulu\Bundle\AdminBundle\Navigation\NavigationItem;

/**
 * Class ContactMessageAdmin
 * @package AppBundle\Admin
 *
 * @author Alexander jollet <alexander.jollet3@gmail.com>
 */
class ContactMessageAdmin extends Admin
{
    /**
     * ContactMessageAdmin constructor.
     *
     * @param $title
     */
    public function __construct($title)
    {
        $rootNavigationItem = new NavigationItem($title);
        $global = new NavigationItem('navigation.modules', $rootNavigationItem);
        $news = new NavigationItem('navigation.messages.title', $global);
        $news->setAction('messages/list');
        $news->setIcon('pencil-square-o');
        $news->setPosition(25);
        $this->setNavigation(new Navigation($rootNavigationItem));
    }

    /**
     * @return string
     */
    public function getJsBundleName()
    {
        return 'app';
    }
}
