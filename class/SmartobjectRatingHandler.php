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
 * Class SmartobjectRatingHandler
 */
class SmartobjectRatingHandler extends Smartobject\SmartPersistableObjectHandler
{
    public $_rateOptions = [];
    public $_moduleList  = false;
    public $pluginsObject;

    /**
     * SmartobjectRatingHandler constructor.
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'rating', 'ratingid', 'rate', '', 'smartobject');
        $this->generalSQL = 'SELECT * FROM ' . $this->table . ' AS ' . $this->_itemname . ' INNER JOIN ' . $this->db->prefix('users') . ' AS user ON ' . $this->_itemname . '.uid=user.uid';

        $this->_rateOptions[1] = 1;
        $this->_rateOptions[2] = 2;
        $this->_rateOptions[3] = 3;
        $this->_rateOptions[4] = 4;
        $this->_rateOptions[5] = 5;

        $this->pluginsObject = new SmartPluginHandler();
    }

    /**
     * @return bool
     */
    public function getModuleList()
    {
        if (!$this->_moduleList) {
            $moduleArray          = $this->pluginsObject->getPluginsArray();
            $this->_moduleList[0] = _CO_SOBJECT_MAKE_SELECTION;
            foreach ($moduleArray as $k => $v) {
                $this->_moduleList[$k] = $v;
            }
        }

        return $this->_moduleList;
    }

    /**
     * @return array
     */
    public function getRateList()
    {
        return $this->_rateOptions;
    }

    /**
     * @param $itemid
     * @param $dirname
     * @param $item
     * @return int
     */
    public function getRatingAverageByItemId($itemid, $dirname, $item)
    {
        $sql    = 'SELECT AVG(rate), COUNT(ratingid) FROM ' . $this->table . " WHERE itemid=$itemid AND dirname='$dirname' AND item='$item' GROUP BY itemid";
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($average, $sum) = $this->db->fetchRow($result);
        $ret['average'] = isset($average) ? $average : 0;
        $ret['sum']     = isset($sum) ? $sum : 0;

        return $ret;
    }

    /**
     * @param $item
     * @param $itemid
     * @param $dirname
     * @param $uid
     * @return bool
     */
    public function already_rated($item, $itemid, $dirname, $uid)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('item', $item));
        $criteria->add(new \Criteria('itemid', $itemid));
        $criteria->add(new \Criteria('dirname', $dirname));
        $criteria->add(new \Criteria('user.uid', $uid));

        $ret =& $this->getObjects($criteria);

        if (!$ret) {
            return false;
        } else {
            return $ret[0];
        }
    }
}
