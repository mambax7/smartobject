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
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartplugins.php';

/**
 * Class SmartobjectRating
 */
class Rating extends Smartobject\BaseSmartObject
{
    public $_modulePlugin = false;

    /**
     * SmartobjectRating constructor.
     */
    public function __construct()
    {
        $this->quickInitVar('ratingid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('dirname', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_RATING_DIRNAME);
        $this->quickInitVar('item', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_RATING_ITEM);
        $this->quickInitVar('itemid', XOBJ_DTYPE_INT, true, _CO_SOBJECT_RATING_ITEMID);
        $this->quickInitVar('uid', XOBJ_DTYPE_INT, true, _CO_SOBJECT_RATING_UID);
        $this->quickInitVar('date', XOBJ_DTYPE_LTIME, true, _CO_SOBJECT_RATING_DATE);
        $this->quickInitVar('rate', XOBJ_DTYPE_INT, true, _CO_SOBJECT_RATING_RATE);

        $this->initNonPersistableVar('name', XOBJ_DTYPE_TXTBOX, 'user', _CO_SOBJECT_RATING_NAME);

        $this->setControl('dirname', [
            'handler'  => 'rating',
            'method'   => 'getModuleList',
            'onSelect' => 'submit'
        ]);

        $this->setControl('item', [
            'object' => &$this,
            'method' => 'getItemList'
        ]);

        $this->setControl('uid', 'user');

        $this->setControl('rate', [
            'handler' => 'rating',
            'method'  => 'getRateList'
        ]);
    }

    /**
     * @param  string $key
     * @param  string $format
     * @return mixed
     */
    public function getVar($key, $format = 's')
    {
        if ('s' === $format && in_array($key, ['name', 'dirname'])) {
            //            return call_user_func(array($this, $key));
            return $this->{$key}();
        }

        return parent::getVar($key, $format);
    }

    /**
     * @return string
     */
    public function name()
    {
        $ret = Smartobject\Utility::getLinkedUnameFromId($this->getVar('uid', 'e'), true, []);

        return $ret;
    }

    /**
     * @return mixed
     */
    public function dirname()
    {
        global $smartobjectRatingHandler;
        $moduleArray = $smartobjectRatingHandler->getModuleList();

        return $moduleArray[$this->getVar('dirname', 'n')];
    }

    /**
     * @return mixed
     */
    public function getItemList()
    {
        $plugin = $this->getModulePlugin();

        return $plugin->getItemList();
    }

    /**
     * @return string
     */
    public function getItemValue()
    {
        $moduleUrl      = XOOPS_URL . '/modules/' . $this->getVar('dirname', 'n') . '/';
        $plugin         = $this->getModulePlugin();
        $pluginItemInfo = $plugin->getItemInfo($this->getVar('item'));
        if (!$pluginItemInfo) {
            return '';
        }
        $itemPath = sprintf($pluginItemInfo['url'], $this->getVar('itemid'));
        $ret      = '<a href="' . $moduleUrl . $itemPath . '">' . $pluginItemInfo['caption'] . '</a>';

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getRateValue()
    {
        return $this->getVar('rate');
    }

    /**
     * @return bool
     */
    public function getModulePlugin()
    {
        if (!$this->_modulePlugin) {
            global $smartobjectRatingHandler;
            $this->_modulePlugin = $smartobjectRatingHandler->pluginsObject->getPlugin($this->getVar('dirname', 'n'));
        }

        return $this->_modulePlugin;
    }
}
