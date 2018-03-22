<?php

/**
 *
 * Module: SmartObject
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */

use XoopsModules\Smartobject;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

Smartobject\Utility::loadCommonLanguageFile();

//require_once SMARTOBJECT_ROOT_PATH . 'class/currency.php';

static $smartobjectCurrenciesObj, $smartobjectCurrenciesArray, $smartobjectDefaultCurrency;

$smartobjectCurrencyHandler = Smartobject\Helper::getInstance()->getHandler('Currency');

if (!$smartobjectCurrenciesObj) {
    $smartobjectCurrenciesObj = $smartobjectCurrencyHandler->getCurrencies();
}
if (!$smartobjectCurrenciesArray) {
    foreach ($smartobjectCurrenciesObj as $currencyid => $currencyObj) {
        if ($currencyObj->getVar('default_currency', 'e')) {
            $smartobjectDefaultCurrency = $currencyObj;
        }
        $smartobjectCurrenciesArray[$currencyid] = $currencyObj->getCode();
    }
}
