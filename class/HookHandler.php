<?php namespace XoopsModules\Smartobject;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 */

use XoopsModules\Smartobject;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class SmartHookHandler
 */
class HookHandler
{
    /**
     * SmartHookHandler constructor.
     */
    public function __construct()
    {
    }

    /**
     * Access the only instance of this class
     *
     * @return \XoopsModules\Smartobject\HookHandler
     *
     * @static
     * @staticvar   object
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * @param $hook_name
     */
    public function executeHook($hook_name)
    {
        $lower_hook_name = strtolower($hook_name);
        $filename        = SMARTOBJECT_ROOT_PATH . 'include/custom_code/' . $lower_hook_name . '.php';
        if (file_exists($filename)) {
            require_once $filename;
            $function = 'smarthook_' . $lower_hook_name;
            if (function_exists($function)) {
                $function();
            }
        }
    }
}
