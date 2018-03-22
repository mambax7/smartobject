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

//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/basedurl.php';

/**
 * Class SmartobjectUrlLink
 */
class UrlLink extends Smartobject\BaseSmartObjectBasedUrl
{
    /**
     * SmartobjectUrlLink constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->quickInitVar('urllinkid', XOBJ_DTYPE_TXTBOX, true);
        $this->quickInitVar('target', XOBJ_DTYPE_TXTBOX, true);

        $this->setControl('target', [
            'options' => [
                '_self'  => _CO_SOBJECT_URLLINK_SELF,
                '_blank' => _CO_SOBJECT_URLLINK_BLANK
            ]
        ]);
    }
}
