<?php namespace XoopsModules\Smartobject;

/**
 * Contains the class responsible for displaying a single SmartObject
 *
 * @license GNU
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 * @package SmartObject
 */

use XoopsModules\Smartobject;

/**
 * SmartObjectRow class
 *
 * Class representing a single row of a SmartObjectSingleView
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 */
class ObjectRow
{
    public $_keyname;
    public $_align;
    public $_customMethodForValue;
    public $_header;
    public $_class;

    /**
     * SmartObjectRow constructor.
     * @param      $keyname
     * @param bool $customMethodForValue
     * @param bool $header
     * @param bool $class
     */
    public function __construct($keyname, $customMethodForValue = false, $header = false, $class = false)
    {
        $this->_keyname              = $keyname;
        $this->_customMethodForValue = $customMethodForValue;
        $this->_header               = $header;
        $this->_class                = $class;
    }

    public function getKeyName()
    {
        return $this->_keyname;
    }

    /**
     * @return bool
     */
    public function isHeader()
    {
        return $this->_header;
    }
}
