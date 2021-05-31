<?php


namespace MailMarketing\Facades;

use Illuminate\Support\Facades\Facade;
use MailMarketing\Drivers\MailMarketingInterface;

/**
 * Class MailMarketing
 * @package MailMarketing\Facades
 *
 * @example \MailMarketing\Facades\MailMarketing::instance()
 * @method static MailMarketingInterface driver($driver = null)
 */
class MailMarketing extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mail-marketing';
    }
}
