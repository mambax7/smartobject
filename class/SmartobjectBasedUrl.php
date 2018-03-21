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

//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';

/**
 * Class SmartobjectBasedUrl
 */
class SmartobjectBasedUrl extends Smartobject\BaseSmartObject
{
    /**
     * SmartobjectBasedUrl constructor.
     */
    public function __construct()
    {
        $this->quickInitVar('caption', XOBJ_DTYPE_TXTBOX, false);
        $this->quickInitVar('description', XOBJ_DTYPE_TXTBOX, false);
        $this->quickInitVar('url', XOBJ_DTYPE_TXTBOX, false);
    }

    /**
     * @param  string $key
     * @param  string $format
     * @return mixed
     */
    public function getVar($key, $format = 'e')
    {
        if (0 === strpos($key, 'url_')) {
            return parent::getVar('url', $format);
        } elseif (0 === strpos($key, 'caption_')) {
            return parent::getVar('caption', $format);
        } else {
            return parent::getVar($key, $format);
        }
    }
}
