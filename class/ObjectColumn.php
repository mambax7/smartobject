<?php namespace XoopsModules\Smartobject;

/**
 * Contains the classes responsible for displaying a simple table filled with records of SmartObjects
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectTable
 */

use XoopsModules\Smartobject;

/**
 * SmartObjectColumn class
 *
 * Class representing a single column of a SmartObjectTable
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 */
class ObjectColumn
{
    public $_keyname;
    public $_align;
    public $_width;
    public $_customMethodForValue;
    public $_extraParams;
    public $_sortable;
    public $_customCaption;

    /**
     * SmartObjectColumn constructor.
     * @param        $keyname
     * @param string $align
     * @param bool   $width
     * @param bool   $customMethodForValue
     * @param bool   $param
     * @param bool   $customCaption
     * @param bool   $sortable
     */
    public function __construct(
        $keyname,
        $align = 'left',
        $width = false,
        $customMethodForValue = false,
        $param = false,
        $customCaption = false,
        $sortable = true
    ) {
        $this->_keyname              = $keyname;
        $this->_align                = $align;
        $this->_width                = $width;
        $this->_customMethodForValue = $customMethodForValue;
        $this->_sortable             = $sortable;
        $this->_param                = $param;
        $this->_customCaption        = $customCaption;
    }

    public function getKeyName()
    {
        return $this->_keyname;
    }

    /**
     * @return string
     */
    public function getAlign()
    {
        return $this->_align;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return $this->_sortable;
    }

    /**
     * @return bool|string
     */
    public function getWidth()
    {
        if ($this->_width) {
            $ret = $this->_width;
        } else {
            $ret = '';
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function getCustomCaption()
    {
        return $this->_customCaption;
    }
}
