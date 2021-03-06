<?php

/**
 *
 * Module: Class_Booking
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param bool $showmenu
 * @param int  $adsenseid
 * @param bool $clone
 */

use XoopsModules\Smartobject;

/**
 * @param bool $showmenu
 * @param int  $adsenseid
 * @param bool $clone
 */
function editclass($showmenu = false, $adsenseid = 0, $clone = false)
{
    global $smartobjectAdsenseHandler;

    $adsenseObj = $smartobjectAdsenseHandler->get($adsenseid);

    if (!$clone && !$adsenseObj->isNew()) {
        if ($showmenu) {
            //Smartobject\Utility::getAdminMenu(3, _AM_SOBJECT_ADSENSES . " > " . _AM_SOBJECT_EDITING);
        }
        Smartobject\Utility::getCollapsableBar('adsenseedit', _AM_SOBJECT_ADSENSES_EDIT, _AM_SOBJECT_ADSENSES_EDIT_INFO);

        $sform = $adsenseObj->getForm(_AM_SOBJECT_ADSENSES_EDIT, 'addadsense');
        $sform->display();
        Smartobject\Utility::closeCollapsable('adsenseedit');
    } else {
        $adsenseObj->setVar('adsenseid', 0);
        $adsenseObj->setVar('tag', '');

        if ($showmenu) {
            //Smartobject\Utility::getAdminMenu(3, _AM_SOBJECT_ADSENSES . " > " . _CO_SOBJECT_CREATINGNEW);
        }

        Smartobject\Utility::getCollapsableBar('adsensecreate', _AM_SOBJECT_ADSENSES_CREATE, _AM_SOBJECT_ADSENSES_CREATE_INFO);
        $sform = $adsenseObj->getForm(_AM_SOBJECT_ADSENSES_CREATE, 'addadsense', false, false, false, true);
        $sform->display();
        Smartobject\Utility::closeCollapsable('adsensecreate');
    }
}

require_once __DIR__ . '/admin_header.php';
//require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/adsense.php';

//$helper = \XoopsModules\Smartobject\Helper::getInstance();
//$helper = Smartobject\Helper::getInstance();

$smartobjectAdsenseHandler = $helper->getHandler('Adsense');
$helper->loadLanguage('adsense');
$adminObject = \Xmf\Module\Admin::getInstance();

$op = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {
    case 'mod':

        $adsenseid = \Xmf\Request::getInt('adsenseid', 0, 'GET');

        Smartobject\Utility::getXoopsCpHeader();
        $adminObject->displayNavigation(basename(__FILE__));

        editclass(true, $adsenseid);
        break;

    case 'clone':

        $adsenseid = \Xmf\Request::getInt('adsenseid', 0, 'GET');

        Smartobject\Utility::getXoopsCpHeader();
        $adminObject->displayNavigation(basename(__FILE__));

        editclass(true, $adsenseid, true);
        break;

    case 'addadsense':
        if (@require_once SMARTOBJECT_ROOT_PATH . 'include/captcha/captcha.php') {
            $xoopsCaptcha = XoopsCaptcha::getInstance();
            if (!$xoopsCaptcha->verify()) {
                redirect_header('javascript:history.go(-1);', 3, $xoopsCaptcha->getMessage());
            }
        }
//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new XoopsModules\Smartobject\ObjectController($smartobjectAdsenseHandler);
        $controller->storeFromDefaultForm(_AM_SOBJECT_ADSENSES_CREATED, _AM_SOBJECT_ADSENSES_MODIFIED);
        break;

    case 'del':

//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new XoopsModules\Smartobject\ObjectController($smartobjectAdsenseHandler);
        $controller->handleObjectDeletion();

        break;

    default:

        Smartobject\Utility::getXoopsCpHeader();
        $adminObject->displayNavigation(basename(__FILE__));

        //Smartobject\Utility::getAdminMenu(3, _AM_SOBJECT_ADSENSES);

        Smartobject\Utility::getCollapsableBar('createdadsenses', _AM_SOBJECT_ADSENSES, _AM_SOBJECT_ADSENSES_DSC);

//        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
        $objectTable = new XoopsModules\Smartobject\Table($smartobjectAdsenseHandler);
        $objectTable->addColumn(new XoopsModules\Smartobject\ObjectColumn('description', 'left'));
        $objectTable->addColumn(new XoopsModules\Smartobject\ObjectColumn(_AM_SOBJECT_ADSENSE_TAG, 'center', 200, 'getXoopsCode'));

        //      $objectTable->addCustomAction('getCreateItemLink');
        //      $objectTable->addCustomAction('getCreateAttributLink');

        $objectTable->addIntroButton('addadsense', 'adsense.php?op=mod', _AM_SOBJECT_ADSENSES_CREATE);
        /*
                $criteria_upcoming = new \CriteriaCompo();
                $criteria_upcoming->add(new \Criteria('start_date', time(), '>'));
                $objectTable->addFilter(_AM_SOBJECT_FILTER_UPCOMING, array(
                                            'key' => 'start_date',
                                            'criteria' => $criteria_upcoming
                ));

                $criteria_last7days = new \CriteriaCompo();
                $criteria_last7days->add(new \Criteria('start_date', time() - 30 *(60 * 60 * 24), '>'));
                $criteria_last7days->add(new \Criteria('start_date', time(), '<'));
                $objectTable->addFilter(_AM_SOBJECT_FILTER_LAST7DAYS, array(
                                            'key' => 'start_date',
                                            'criteria' => $criteria_last7days
                ));

                $criteria_last30days = new \CriteriaCompo();
                $criteria_last30days->add(new \Criteria('start_date', time() - 30 *(60 * 60 * 24), '>'));
                $criteria_last30days->add(new \Criteria('start_date', time(), '<'));
                $objectTable->addFilter(_AM_SOBJECT_FILTER_LAST30DAYS, array(
                                            'key' => 'start_date',
                                            'criteria' => $criteria_last30days
                ));
        */
        $objectTable->addQuickSearch(['title', 'summary', 'description']);
        $objectTable->addCustomAction('getCloneLink');

        $objectTable->render();

        echo '<br>';
        Smartobject\Utility::closeCollapsable('createdadsenses');
        echo '<br>';

        break;
}

//Smartobject\Utility::getModFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
