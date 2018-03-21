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
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Class SmartobjectCurrency
 */
class SmartobjectCurrency extends Smartobject\BaseSmartObject
{
    public $_modulePlugin = false;

    /**
     * SmartobjectCurrency constructor.
     */
    public function __construct()
    {
        $this->quickInitVar('currencyid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('iso4217', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_CURRENCY_ISO4217, _CO_SOBJECT_CURRENCY_ISO4217_DSC);
        $this->quickInitVar('name', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_CURRENCY_NAME);
        $this->quickInitVar('symbol', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_CURRENCY_SYMBOL);
        $this->quickInitVar('rate', XOBJ_DTYPE_FLOAT, true, _CO_SOBJECT_CURRENCY_RATE, '', '1.0');
        $this->quickInitVar('default_currency', XOBJ_DTYPE_INT, false, _CO_SOBJECT_CURRENCY_DEFAULT, '', false);

        $this->setControl('symbol', [
            'name'      => 'text',
            'size'      => '15',
            'maxlength' => '15'
        ]);

        $this->setControl('iso4217', [
            'name'      => 'text',
            'size'      => '5',
            'maxlength' => '5'
        ]);

        $this->setControl('rate', [
            'name'      => 'text',
            'size'      => '5',
            'maxlength' => '5'
        ]);

        $this->setControl('rate', [
            'name'      => 'text',
            'size'      => '5',
            'maxlength' => '5'
        ]);

        $this->hideFieldFromForm('default_currency');
    }

    /**
     * @param  string $key
     * @param  string $format
     * @return mixed
     */
    public function getVar($key, $format = 's')
    {
        if ('s' === $format && in_array($key, ['rate', 'default_currency'])) {
            //            return call_user_func(array($this, $key));
            return $this->{$key}();
        }

        return parent::getVar($key, $format);
    }

    /**
     * @return mixed
     */
    public function getCurrencyLink()
    {
        $ret = $this->getVar('name', 'e');

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        $ret = $this->getVar('iso4217', 'e');

        return $ret;
    }

    /**
     * @return float|int|mixed|string
     */
    public function rate()
    {
        return smart_currency($this->getVar('rate', 'e'));
    }

    /**
     * @return string
     */
    public function defaultCurrency()
    {
        if (true === $this->getVar('default_currency', 'e')) {
            return _YES;
        } else {
            return _NO;
        }
    }

    /**
     * @return string
     */
    public function getDefaultCurrencyControl()
    {
        $radio_box = '<input name="default_currency" value="' . $this->getVar('currencyid') . '" type="radio"';
        if ($this->getVar('default_currency', 'e')) {
            $radio_box .= 'checked';
        }
        $radio_box .= '>';

        return $radio_box;
    }
}
