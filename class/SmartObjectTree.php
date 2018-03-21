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

require_once XOOPS_ROOT_PATH . '/class/tree.php';

/**
 * Class smartobjecttree
 */
class SmartObjectTree extends \XoopsObjectTree
{
    public function _initialize()
    {
        foreach (array_keys($this->_objects) as $i) {
            $key1                         = $this->_objects[$i]->getVar($this->_myId);
            $this->tree[$key1]['obj']     = $this->_objects[$i];
            $key2                         = $this->_objects[$i]->getVar($this->_parentId, 'e');
            $this->tree[$key1]['parent']  = $key2;
            $this->tree[$key2]['child'][] = $key1;
            if (isset($this->_rootId)) {
                $this->tree[$key1]['root'] = $this->_objects[$i]->getVar($this->_rootId);
            }
        }
    }
}
