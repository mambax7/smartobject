<?php namespace XoopsModules\Smartobject;

/**
 * Contains the basis classes for managing any objects derived from SmartObjects
 *
 * @license    GNU
 * @author     marcan <marcan@smartfactory.ca>
 * @link       http://smartfactory.ca The SmartFactory
 * @package    SmartObject
 * @subpackage SmartObjectCore
 */

use XoopsModules\Smartobject;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');
//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';

/**
 * SmartObject base Multilanguage-enabled class
 *
 * Base class representing a single SmartObject with multilanguages capabilities
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 */
class MlObject extends Smartobject\BaseSmartObject
{
    /**
     * SmartMlObject constructor.
     */
    public function __construct()
    {
        $this->initVar('language', XOBJ_DTYPE_TXTBOX, 'english', false, null, '', true, _CO_SOBJECT_LANGUAGE_CAPTION, _CO_SOBJECT_LANGUAGE_DSC, true, true);
        $this->setControl('language', 'language');
    }

    /**
     * If object is not new, change the control of the not-multilanguage fields
     *
     * We need to intercept this function from SmartObject because if the object is not new...
     */
    // function getForm() {

    //}

    /**
     * Strip Multilanguage Fields
     *
     * Get rid of all the multilanguage fields to have an object with only global fields.
     * This will be usefull when creating the ML object for the first time. Then we will be able
     * to create translations.
     */
    public function stripMultilanguageFields()
    {
        $objectVars    =& $this->getVars();
        $newObjectVars = [];
        foreach ($objectVars as $key => $var) {
            if (!$var['multilingual']) {
                $newObjectVars[$key] = $var;
            }
        }
        $this->vars = $newObjectVars;
    }

    public function stripNonMultilanguageFields()
    {
        $objectVars    =& $this->getVars();
        $newObjectVars = [];
        foreach ($objectVars as $key => $var) {
            if ($var['multilingual'] || $key == $this->handler->keyName) {
                $newObjectVars[$key] = $var;
            }
        }
        $this->vars = $newObjectVars;
    }

    /**
     * Make non multilanguage fields read only
     *
     * This is used when we are creating/editing a translation.
     * We only want to edit the multilanguag fields, not the global one.
     */
    public function makeNonMLFieldReadOnly()
    {
        foreach ($this->getVars() as $key => $var) {
            //if (($key == 'language') || (!$var['multilingual'] && $key <> $this->handler->keyName)) {
            if (!$var['multilingual'] && $key != $this->handler->keyName) {
                $this->setControl($key, 'label');
            }
        }
    }

    /**
     * @param  bool $onlyUrl
     * @param  bool $withimage
     * @return string
     */
    public function getEditLanguageLink($onlyUrl = false, $withimage = true)
    {
        $controller = new ObjectController($this->handler);

        return $controller->getEditLanguageLink($this, $onlyUrl, $withimage);
    }
}
