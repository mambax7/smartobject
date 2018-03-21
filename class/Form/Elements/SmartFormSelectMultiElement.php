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

//require_once SMARTOBJECT_ROOT_PATH . 'class/form/elements/smartformselectelement.php';

/**
 * Class SmartFormSelectMultiElement
 */
class SmartFormSelectMultiElement extends Smartobject\Form\Elements\SmartFormSelectElement
{
    /**
     * SmartFormSelectMultiElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $this->multiple = true;
        parent::__construct($object, $key);
    }
}
