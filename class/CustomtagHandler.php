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
 * Class SmartobjectCustomtagHandler
 */
class CustomtagHandler extends Smartobject\PersistableObjectHandler
{
    public $objects = false;

    /**
     * SmartobjectCustomtagHandler constructor.
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, Customtag::class, 'customtagid', 'name', 'description', 'smartobject');
        $this->addPermission('view', _CO_SOBJECT_CUSTOMTAG_PERMISSION_VIEW, _CO_SOBJECT_CUSTOMTAG_PERMISSION_VIEW_DSC);
    }

    /**
     * @return array|bool
     */
    public function getCustomtagsByName()
    {
        if (!$this->objects) {
            global $xoopsConfig;

            $ret = [];

            $criteria = new \CriteriaCompo();

            $criteria_language = new \CriteriaCompo();
            $criteria_language->add(new \Criteria('language', $xoopsConfig['language']));
            $criteria_language->add(new \Criteria('language', 'all'), 'OR');
            $criteria->add($criteria_language);

            $smartobjectPermissionsHandler = new PermissionHandler($this);
            $granted_ids                   = $smartobjectPermissionsHandler->getGrantedItems('view');

            if ($granted_ids && count($granted_ids) > 0) {
                $criteria->add(new \Criteria('customtagid', '(' . implode(', ', $granted_ids) . ')', 'IN'));
                $customtagsObj =& $this->getObjects($criteria, true);
                foreach ($customtagsObj as $customtagObj) {
                    $ret[$customtagObj->getVar('name')] = $customtagObj;
                }
            }
            $this->objects = $ret;
        }

        return $this->objects;
    }
}
