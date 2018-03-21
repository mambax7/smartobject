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

use XoopsLists;
use XoopsModules\Smartobject;


/**
 * Class SmartPluginHandler
 */
class SmartPluginHandler
{
    public $pluginPatterns = false;

    /**
     * @param $dirname
     * @return bool|SmartPlugin
     */
    public function getPlugin($dirname)
    {
        $pluginName = SMARTOBJECT_ROOT_PATH . 'plugins/' . $dirname . '.php';
        if (file_exists($pluginName)) {
            require_once $pluginName;
            $function = 'smartobject_plugin_' . $dirname;
            if (function_exists($function)) {
                $array = $function();
                $ret   = new SmartPlugin($array);

                return $ret;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getPluginsArray()
    {
        require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

        $moduleHandler = xoops_getHandler('module');
        $criteria      = new \CriteriaCompo();
        $criteria->add(new \Criteria('isactive', 1));
        $tempModulesObj = $moduleHandler->getObjects($criteria);
        $modulesObj     = [];
        foreach ($tempModulesObj as $moduleObj) {
            $modulesObj[$moduleObj->getVar('dirname')] = $moduleObj;
        }

        $aFiles = XoopsLists::getFileListAsArray(SMARTOBJECT_ROOT_PATH . 'plugins/');
        $ret    = [];
        foreach ($aFiles as $file) {
            if ('.php' === substr($file, strlen($file) - 4, 4)) {
                $pluginName                = str_replace('.php', '', $file);
                $module_xoops_version_file = XOOPS_ROOT_PATH . "/modules/$pluginName/xoops_version.php";
                if (file_exists($module_xoops_version_file) && isset($modulesObj[$pluginName])) {
                    $ret[$pluginName] = $modulesObj[$pluginName]->getVar('name');
                }
            }
        }

        return $ret;
    }
}
