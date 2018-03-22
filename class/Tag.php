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
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartmlobject.php';

/**
 * Class SmartobjectTag
 */
class Tag extends MlObject
{
    /**
     * SmartobjectTag constructor.
     */
    public function __construct()
    {
        $this->initVar('tagid', XOBJ_DTYPE_INT, '', true);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, '', true, 255, '', false, _CO_SOBJECT_TAG_TAGID_CAPTION, _CO_SOBJECT_TAG_TAGID_DSC, true);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '', true, null, '', false, _CO_SOBJECT_TAG_DESCRIPTION_CAPTION, _CO_SOBJECT_TAG_DESCRIPTION_DSC);
        $this->initVar('value', XOBJ_DTYPE_TXTAREA, '', true, null, '', true, _CO_SOBJECT_TAG_VALUE_CAPTION, _CO_SOBJECT_TAG_VALUE_DSC);

        // call parent constructor to get Multilanguage field initiated
        $this->SmartMlObject();
    }
}
