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

/**
 * Class SmartPlugin
 */
class Plugin
{
    public $_infoArray;

    /**
     * SmartPlugin constructor.
     * @param $array
     */
    public function __construct($array)
    {
        $this->_infoArray = $array;
    }

    /**
     * @param $item
     * @return bool
     */
    public function getItemInfo($item)
    {
        if (isset($this->_infoArray['items'][$item])) {
            return $this->_infoArray['items'][$item];
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getItemList()
    {
        $itemsArray = $this->_infoArray['items'];
        foreach ($itemsArray as $k => $v) {
            $ret[$k] = $v['caption'];
        }

        return $ret;
    }

    /**
     * @return bool|int|string
     */
    public function getItem()
    {
        $ret = false;
        foreach ($this->_infoArray['items'] as $k => $v) {
            $search_str = str_replace('%u', '', $v['url']);
            if (strpos($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'], $search_str) > 0) {
                $ret = $k;
                break;
            }
        }

        return $ret;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function getItemIdForItem($item)
    {
        return $_REQUEST[$this->_infoArray['items'][$item]['request']];
    }
}
