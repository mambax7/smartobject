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

require_once XOOPS_ROOT_PATH . '/modules/smartobject/include/projax/projax.php';

/**
 * Class SmartJax
 */
class Jax extends \Projax
{
    public function initiateFromUserside()
    {
        global $xoTheme;
        $xoTheme->addScript(SMARTOBJECT_URL . 'include/projax/js/prototype.js');
        $xoTheme->addScript(SMARTOBJECT_URL . 'include/projax/js/scriptaculous.js');
    }
}
