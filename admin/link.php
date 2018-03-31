<?php
//

// 2012-01-01 K.OHWADA
// PHP 5.3: Assigning the return value of new by reference is now deprecated.

/**
 * Id: link.php 159 2007-12-17 16:44:05Z malanciault
 * Module: SmartShop
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartobject;
use XoopsModules\Smartobject\ObjectColumn;
use XoopsModules\Smartobject\ObjectController;
use XoopsModules\Smartobject\Table;

require_once __DIR__ . '/admin_header.php';
//require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
//require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjectlink.php';
$adminObject = \Xmf\Module\Admin::getInstance();

$smartobjectLinkHandler = Smartobject\Helper::getInstance()->getHandler('Link');

$op = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {

    case 'del':
//        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new XoopsModules\Smartobject\ObjectController($smartobjectLinkHandler);
        $controller->handleObjectDeletion(_AM_SOBJECT_SENT_LINK_DELETE_CONFIRM);

        break;

    case 'view':
        $linkid  = \Xmf\Request::getInt('linkid', 0, GET);
        $linkObj = $smartobjectLinkHandler->get($linkid);

        if ($linkObj->isNew()) {
            redirect_header(SMARTOBJECT_URL . 'admin/link.php', 3, _AM_SOBJECT_LINK_NOT_FOUND);
        }

        Smartobject\Utility::getXoopsCpHeader();

        //Smartobject\Utility::getAdminMenu(1, _AM_SOBJECT_SENT_LINK_DISPLAY);

        Smartobject\Utility::getCollapsableBar('sentlinks', _AM_SOBJECT_SENT_LINK_DISPLAY, _AM_SOBJECT_SENT_LINK_DISPLAY_INFO);

        require_once XOOPS_ROOT_PATH . '/class/template.php';

        // ---
        // 2012-01-01 PHP 5.3: Assigning the return value of new by reference is now deprecated.
        //      $xoopsTpl = new \XoopsTpl();
        $xoopsTpl = new \XoopsTpl();
        //---

        $xoopsTpl->assign('link', $linkObj->toArray());
        $xoopsTpl->display('db:smartobject_sentlink_display.tpl');

        echo '<br>';
        Smartobject\Utility::closeCollapsable('sentlinks');
        echo '<br>';

        break;

    default:

        Smartobject\Utility::getXoopsCpHeader();

        $adminObject->displayNavigation(basename(__FILE__));

        //Smartobject\Utility::getAdminMenu(1, _AM_SOBJECT_SENT_LINKS);

        Smartobject\Utility::getCollapsableBar('sentlinks', _AM_SOBJECT_SENT_LINKS, _AM_SOBJECT_SENT_LINKS_INFO);

//        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
        $objectTable = new Smartobject\Table($smartobjectLinkHandler, null, ['delete']);
        $objectTable->addColumn(new Smartobject\ObjectColumn('date'));
        $objectTable->addColumn(new Smartobject\ObjectColumn(_AM_SOBJECT_SENT_LINKS_FROM, $align = 'left', $width = false, 'getFromInfo'));
        $objectTable->addColumn(new Smartobject\ObjectColumn(_AM_SOBJECT_SENT_LINKS_TO, $align = 'left', $width = false, 'getToInfo'));
        $objectTable->addColumn(new Smartobject\ObjectColumn('link'));

        $objectTable->addCustomAction('getViewItemLink');

        $objectTable->setDefaultSort('date');
        $objectTable->setDefaultOrder('DESC');

        $objectTable->render();

        echo '<br>';
        Smartobject\Utility::closeCollapsable('sentlinks');
        echo '<br>';

        break;
}

//Smartobject\Utility::getModFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
