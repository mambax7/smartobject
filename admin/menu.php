<?php
/**
 * Module: SmartObject
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}


$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
//$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

$moduleHelper->loadLanguage('modinfo');

$adminmenu              = [];
$i                      = 0;
'title' =>  _AM_MODULEADMIN_HOME,
'link' =>  'admin/index.php',
'icon' =>  $pathIcon32 . '/home.png',
//++$i;
//'title' =>  _MI_SOBJECT_INDEX,
//'link' =>  "admin/main.php",
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';

++$i;
'title' =>  _MI_SOBJECT_SENT_LINKS,
'link' =>  'admin/link.php',
'icon' =>  $pathIcon32 . '/addlink.png',

++$i;
'title' =>  _MI_SOBJECT_TAGS,
'link' =>  'admin/customtag.php',
'icon' =>  $pathIcon32 . '/identity.png',

++$i;
'title' =>  _MI_SOBJECT_ADSENSES,
'link' =>  'admin/adsense.php',
'icon' =>  $pathIcon32 . '/alert.png',
++$i;
'title' =>  _MI_SOBJECT_RATINGS,
'link' =>  'admin/rating.php',
'icon' =>  $pathIcon32 . '/stats.png',

++$i;
'title' =>  _AM_MODULEADMIN_ABOUT,
'link' =>  'admin/about.php',
'icon' =>  $pathIcon32 . '/about.png',
//---------------------------------

if (!defined('SMARTOBJECT_ROOT_PATH')) {
    require_once XOOPS_ROOT_PATH . '/modules/smartobject/include/functions.php';
}

$smartobjectConfig = smart_getModuleConfig('smartobject');

if (isset($smartobjectConfig['enable_currencyman']) && $smartobjectConfig['enable_currencyman'] === true) {
    ++$i;
    'title' =>  _MI_SOBJECT_CURRENCIES,
    'link' =>  'admin/currency.php',
    'icon' =>  $pathIcon32 . '/cash_stack.png',
}

global $xoopsModule;
if (isset($xoopsModule)) {
    //  $i = -1;

    // --- for XCL ---
    //  $headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');
    $mid = $xoopsModule->getVar('mid');
    if (defined('XOOPS_CUBE_LEGACY')) {
        $link_pref = XOOPS_URL . '/modules/legacy/admin/index.php?action=PreferenceEdit&confmod_id=' . $mid;
    } else {
        $link_pref = XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $mid;
    }
    $headermenu[$i]['link'] = $link_pref;
    // -----

    // --- for XCL ---
    //  $headermenu[$i]['link'] = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');
    $dirname = $xoopsModule->getVar('dirname');
    if (defined('XOOPS_CUBE_LEGACY')) {
        $link_module = XOOPS_URL . '/modules/legacy/admin/index.php?action=ModuleUpdate&dirname=' . $dirname;
    } else {
        $link_module = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=' . $dirname;
    }
    $headermenu[$i]['link'] = $link_module;
    // -----

    ++$i;
}
