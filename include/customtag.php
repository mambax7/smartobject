<?php

/**
 *
 * Module: SmartRental
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartobject;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

function smart_customtag_initiate()
{
    global $xoopsTpl, $smartobjectCustomtagHandler;
    if (is_object($xoopsTpl)) {
        foreach ($smartobjectCustomtagHandler->objects as $k => $v) {
            $xoopsTpl->assign($k, $v->render());
        }
    }
}

if (!defined('SMARTOBJECT_URL')) {
    require_once XOOPS_ROOT_PATH . '/modules/smartobject/include/common.php';
}

Smartobject\Utility::loadLanguageFile('smartobject', 'customtag');

//require_once XOOPS_ROOT_PATH . '/modules/smartobject/include/functions.php';
//require_once SMARTOBJECT_ROOT_PATH . 'class/customtag.php';

$smartobjectCustomtagHandler = Smartobject\Helper::getInstance()->getHandler('Customtag');
$smartobjectCustomTagsObj    = $smartobjectCustomtagHandler->getCustomtagsByName();
