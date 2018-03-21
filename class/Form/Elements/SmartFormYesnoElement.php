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
 * Class SmartFormYesnoElement
 * @package XoopsModules\Smartobject\Form\Elements
 */
class SmartFormYesnoElement extends \XoopsFormRadioYN
{
    /**
     * SmartFormYesnoElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        parent::__construct($object->vars[$key]['form_caption'], $key, $object->getVar($key, 'e'));
    }
}
