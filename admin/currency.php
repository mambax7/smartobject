<?php

use XoopsModules\Smartobject;
use XoopsModules\Smartobject\ObjectColumn;
use XoopsModules\Smartobject\ObjectController;
use XoopsModules\Smartobject\Table;

/**
 * Module: Class_Booking
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param bool $showmenu
 * @param int  $currencyid
 */

function editclass($showmenu = false, $currencyid = 0)
{
    global $smartobjectCurrencyHandler;

    $currencyObj = $smartobjectCurrencyHandler->get($currencyid);

    if (!$currencyObj->isNew()) {
        if ($showmenu) {
            //Smartobject\Utility::getAdminMenu(5, _AM_SOBJECT_CURRENCIES . " > " . _AM_SOBJECT_EDITING);
        }
        Smartobject\Utility::getCollapsableBar('currencyedit', _AM_SOBJECT_CURRENCIES_EDIT, _AM_SOBJECT_CURRENCIES_EDIT_INFO);

        $sform = $currencyObj->getForm(_AM_SOBJECT_CURRENCIES_EDIT, 'addcurrency');
        $sform->display();
        Smartobject\Utility::closeCollapsable('currencyedit');
    } else {
        if ($showmenu) {
            //Smartobject\Utility::getAdminMenu(5, _AM_SOBJECT_CURRENCIES . " > " . _CO_SOBJECT_CREATINGNEW);
        }

        Smartobject\Utility::getCollapsableBar('currencycreate', _AM_SOBJECT_CURRENCIES_CREATE, _AM_SOBJECT_CURRENCIES_CREATE_INFO);
        $sform = $currencyObj->getForm(_AM_SOBJECT_CURRENCIES_CREATE, 'addcurrency');
        $sform->display();
        Smartobject\Utility::closeCollapsable('currencycreate');
    }
}

require_once __DIR__ . '/admin_header.php';
//require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
//require_once SMARTOBJECT_ROOT_PATH . 'class/currency.php';
$smartobjectCurrencyHandler = Smartobject\Helper::getInstance()->getHandler('Currency');

$op = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {
    case 'mod':
        $currencyid = \Xmf\Request::getInt('currencyid', 0, 'GET');

        Smartobject\Utility::getXoopsCpHeader();

        editclass(true, $currencyid);
        break;

    case 'updateCurrencies':

        if (!isset($_POST['SmartobjectCurrency_objects']) || 0 == count($_POST['SmartobjectCurrency_objects'])) {
            redirect_header($smart_previous_page, 3, _AM_SOBJECT_NO_RECORDS_TO_UPDATE);
        }

        if (isset($_POST['default_currency'])) {
            $newDefaultCurrency = $_POST['default_currency'];
            $sql                = 'UPDATE ' . $smartobjectCurrencyHandler->table . ' SET default_currency=0';
            $smartobjectCurrencyHandler->query($sql);
            $sql = 'UPDATE ' . $smartobjectCurrencyHandler->table . ' SET default_currency=1 WHERE currencyid=' . $newDefaultCurrency;
            $smartobjectCurrencyHandler->query($sql);
        }

        /*
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('currencyid', '(' . implode(', ', $_POST['SmartobjectCurrency_objects']) . ')', 'IN'));
        $currenciesObj = $smartobjectCurrencyHandler->getObjects($criteria, true);

        foreach ($currenciesObj as $currencyid=>$currencyObj) {
            //$bookingObj->setVar('attended', isset($_POST['attended_' . $bookingid]) ? (int)($_POST['attended_' . $bookingid]): 0);
            $smartobjectCurrencyHandler->insert($currencyObj);
        }
        */
        redirect_header($smart_previous_page, 3, _AM_SOBJECT_RECORDS_UPDATED);
        break;

    case 'addcurrency':
//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new ObjectController($smartobjectCurrencyHandler);
        $controller->storeFromDefaultForm(_AM_SOBJECT_CURRENCIES_CREATED, _AM_SOBJECT_CURRENCIES_MODIFIED, SMARTOBJECT_URL . 'admin/currency.php');

        break;

    case 'del':
//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new ObjectController($smartobjectCurrencyHandler);
        $controller->handleObjectDeletion();

        break;

    default:

        Smartobject\Utility::getXoopsCpHeader();

        //Smartobject\Utility::getAdminMenu(5, _AM_SOBJECT_CURRENCIES);

        Smartobject\Utility::getCollapsableBar('createdcurrencies', _AM_SOBJECT_CURRENCIES, _AM_SOBJECT_CURRENCIES_DSC);

//        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
        $objectTable = new Table($smartobjectCurrencyHandler);
        $objectTable->addColumn(new ObjectColumn('name', 'left', false, 'getCurrencyLink'));
        $objectTable->addColumn(new ObjectColumn('rate', 'center', 150));
        $objectTable->addColumn(new ObjectColumn('iso4217', 'center', 150));
        $objectTable->addColumn(new ObjectColumn('default_currency', 'center', 150, 'getDefaultCurrencyControl'));

        $objectTable->addIntroButton('addcurrency', 'currency.php?op=mod', _AM_SOBJECT_CURRENCIES_CREATE);

        $objectTable->addActionButton('updateCurrencies', _SUBMIT, _AM_SOBJECT_CURRENCY_UPDATE_ALL);

        $objectTable->render();

        echo '<br>';
        Smartobject\Utility::closeCollapsable('createdcurrencies');
        echo '<br>';

        break;
}

//Smartobject\Utility::getModFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
