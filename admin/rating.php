<?php

use XoopsModules\Smartobject\SmartObjectColumn;
use XoopsModules\Smartobject\SmartObjectController;
use XoopsModules\Smartobject\SmartObjectTable;

/**
 *
 * Module: Class_Booking
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param bool $showmenu
 * @param int  $ratingid
 */

function editclass($showmenu = false, $ratingid = 0)
{
    global $smartobjectRatingHandler;

    $ratingObj = $smartobjectRatingHandler->get($ratingid);

    if (!$ratingObj->isNew()) {
        if ($showmenu) {
            //Smartobject\Utility::getAdminMenu(4, _AM_SOBJECT_RATINGS . " > " . _AM_SOBJECT_EDITING);
        }
        Smartobject\Utility::getCollapsableBar('ratingedit', _AM_SOBJECT_RATINGS_EDIT, _AM_SOBJECT_RATINGS_EDIT_INFO);

        $sform = $ratingObj->getForm(_AM_SOBJECT_RATINGS_EDIT, 'addrating');
        $sform->display();
        Smartobject\Utility::closeCollapsable('ratingedit');
    } else {
        $ratingObj->hideFieldFromForm(['item', 'itemid', 'uid', 'date', 'rate']);

        if (isset($_POST['op'])) {
            $controller = new XoopsModules\Smartobject\SmartObjectController($smartobjectRatingHandler);
            $controller->postDataToObject($ratingObj);

            if ('changedField' === $_POST['op']) {
                switch ($_POST['changedField']) {
                    case 'dirname':
                        $ratingObj->showFieldOnForm(['item', 'itemid', 'uid', 'date', 'rate']);
                        break;
                }
            }
        }

        if ($showmenu) {
            //Smartobject\Utility::getAdminMenu(4, _AM_SOBJECT_RATINGS . " > " . _CO_SOBJECT_CREATINGNEW);
        }

        Smartobject\Utility::getCollapsableBar('ratingcreate', _AM_SOBJECT_RATINGS_CREATE, _AM_SOBJECT_RATINGS_CREATE_INFO);
        $sform = $ratingObj->getForm(_AM_SOBJECT_RATINGS_CREATE, 'addrating');
        $sform->display();
        Smartobject\Utility::closeCollapsable('ratingcreate');
    }
}

require_once __DIR__ . '/admin_header.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/rating.php';
$smartobjectRatingHandler = xoops_getModuleHandler('rating');
$indexAdmin               = \Xmf\Module\Admin::getInstance();

$op = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {
    case 'mod':
    case 'changedField':

        $ratingid = isset($_GET['ratingid']) ? (int)$_GET['ratingid'] : 0;

        Smartobject\Utility::getXoopsCpHeader();
        $adminObject->displayNavigation(basename(__FILE__));

        editclass(true, $ratingid);
        break;

    case 'addrating':
        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new XoopsModules\Smartobject\SmartObjectController($smartobjectRatingHandler);
        $controller->storeFromDefaultForm(_AM_SOBJECT_RATINGS_CREATED, _AM_SOBJECT_RATINGS_MODIFIED, SMARTOBJECT_URL . 'admin/rating.php');

        break;

    case 'del':
        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new XoopsModules\Smartobject\SmartObjectController($smartobjectRatingHandler);
        $controller->handleObjectDeletion();

        break;

    default:

        Smartobject\Utility::getXoopsCpHeader();
        $adminObject->displayNavigation(basename(__FILE__));

        //Smartobject\Utility::getAdminMenu(4, _AM_SOBJECT_RATINGS);

        Smartobject\Utility::getCollapsableBar('createdratings', _AM_SOBJECT_RATINGS, _AM_SOBJECT_RATINGS_DSC);

        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
        $objectTable = new XoopsModules\Smartobject\SmartObjectTable($smartobjectRatingHandler);
        $objectTable->addColumn(new XoopsModules\Smartobject\SmartObjectColumn('name', 'left'));
        $objectTable->addColumn(new XoopsModules\Smartobject\SmartObjectColumn('dirname', 'left'));
        $objectTable->addColumn(new XoopsModules\Smartobject\SmartObjectColumn('item', 'left', false, 'getItemValue'));
        $objectTable->addColumn(new XoopsModules\Smartobject\SmartObjectColumn('date', 'center', 150));
        $objectTable->addColumn(new XoopsModules\Smartobject\SmartObjectColumn('rate', 'center', 40, 'getRateValue'));

        //      $objectTable->addCustomAction('getCreateItemLink');
        //      $objectTable->addCustomAction('getCreateAttributLink');

        $objectTable->addIntroButton('addrating', 'rating.php?op=mod', _AM_SOBJECT_RATINGS_CREATE);
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

        $objectTable->render();

        echo '<br>';
        Smartobject\Utility::closeCollapsable('createdratings');
        echo '<br>';

        break;
}

//Smartobject\Utility::getModFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
