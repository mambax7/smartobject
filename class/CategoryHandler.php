<?php namespace XoopsModules\Smartobject;

/**
 * Contains the basic classe for managing a category object based on SmartObject
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectItems
 */

use XoopsModules\Smartobject;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartseoobject.php';


/**
 * Class SmartobjectCategoryHandler
 */
class CategoryHandler extends Smartobject\PersistableObjectHandler
{
    public $allCategoriesObj = false;
    public $_allCategoriesId = false;

    /**
     * SmartobjectCategoryHandler constructor.
     * @param \XoopsDatabase       $db
     * @param                      $modulename
     */
    public function __construct(\XoopsDatabase $db, $modulename)
    {
        parent::__construct($db, 'category', 'categoryid', 'name', 'description', $modulename);
    }

    /**
     * @param  int    $parentid
     * @param  bool   $perm_name
     * @param  string $sort
     * @param  string $order
     * @return array|bool
     */
    public function getAllCategoriesArray($parentid = 0, $perm_name = false, $sort = 'parentid', $order = 'ASC')
    {
        if (!$this->allCategoriesObj) {
            $criteria = new \CriteriaCompo();
            $criteria->setSort($sort);
            $criteria->setOrder($order);
            global $xoopsUser;
            $userIsAdmin = is_object($xoopsUser) && $xoopsUser->isAdmin();

            if ($perm_name && !$userIsAdmin) {
                if (!$this->setGrantedObjectsCriteria($criteria, $perm_name)) {
                    return false;
                }
            }

            $this->allCategoriesObj =& $this->getObjects($criteria, 'parentid');
        }

        $ret = [];
        if (isset($this->allCategoriesObj[$parentid])) {
            foreach ($this->allCategoriesObj[$parentid] as $categoryid => $categoryObj) {
                $ret[$categoryid]['self'] = $categoryObj->toArray();
                if (isset($this->allCategoriesObj[$categoryid])) {
                    $ret[$categoryid]['sub']          = $this->getAllCategoriesArray($categoryid);
                    $ret[$categoryid]['subcatscount'] = count($ret[$categoryid]['sub']);
                }
            }
        }

        return $ret;
    }

    /**
     * @param               $parentid
     * @param  bool         $asString
     * @return array|string
     */
    public function getParentIds($parentid, $asString = true)
    {
        if (!$this->allCategoriesId) {
            $ret = [];
            $sql = 'SELECT categoryid, parentid FROM ' . $this->table . ' AS ' . $this->_itemname . ' ORDER BY parentid';

            $result = $this->db->query($sql);

            if (!$result) {
                return $ret;
            }

            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $this->allCategoriesId[$myrow['categoryid']] = $myrow['parentid'];
            }
        }

        $retArray = [$parentid];
        while (0 != $parentid) {
            $parentid = $this->allCategoriesId[$parentid];
            if (0 != $parentid) {
                $retArray[] = $parentid;
            }
        }
        if ($asString) {
            return implode(', ', $retArray);
        } else {
            return $retArray;
        }
    }
}
