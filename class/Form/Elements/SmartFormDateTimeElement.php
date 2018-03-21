<?php namespace XoopsModules\Smartobject\Form\Elements;

/**
 * Contains the SmartObjectControl class
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectForm
 */
use XoopsModules\Smartobject;

/**
 * Class SmartFormDateTimeElement
 * @package XoopsModules\Smartobject\Form\Elements
 */
class SmartFormDateTimeElement extends \XoopsFormDateTime
{
    /**
     * SmartFormDateTimeElement constructor.
     * @param mixed $object
     * @param mixed $key
     */
    public function __construct($object, $key)
    {
        parent::__construct($object->vars[$key]['form_caption'], $key, 15, $object->getVar($key, 'e'));
    }
}
