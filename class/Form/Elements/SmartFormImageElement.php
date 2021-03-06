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
 * Class SmartFormImageElement
 * @package XoopsModules\Smartobject\Form\Elements
 */
class SmartFormImageElement extends \XoopsFormElementTray
{
    /**
     * SmartFormImageElement constructor.
     * @param string $object
     * @param string $key
     */
    public function __construct($object, $key)
    {
        $var             = $object->vars[$key];
        $object_imageurl = $object->getImageDir();
        parent::__construct($var['form_caption'], ' ');

        $objectArray['image'] = str_replace('{XOOPS_URL}', XOOPS_URL, $objectArray['image']);

        if ('' !== $object->getVar($key)
            && (0 === strpos($object->getVar($key), 'http')
                || 0 === strpos($object->getVar($key), '{XOOPS_URL}'))) {
            $this->addElement(new \XoopsFormLabel('', "<img src='" . str_replace('{XOOPS_URL}', XOOPS_URL, $object->getVar($key)) . "' alt=''><br><br>"));
        } elseif ('' !== $object->getVar($key)) {
            $this->addElement(new \XoopsFormLabel('', "<img src='" . $object_imageurl . $object->getVar($key) . "' alt=''><br><br>"));
        }

//        require_once SMARTOBJECT_ROOT_PATH . 'class/form/elements/smartformfileuploadelement.php';
        $this->addElement(new SmartFormFileUploadElement($object, $key));

        $this->addElement(new \XoopsFormLabel('<div style="height: 10px; padding-top: 8px; font-size: 80%;">' . _CO_SOBJECT_URL_FILE_DSC . '</div>', ''));
//        require_once SMARTOBJECT_ROOT_PATH . 'class/form/elements/smartformtextelement.php';
//        require_once SMARTOBJECT_ROOT_PATH . 'class/form/elements/smartformcheckelement.php';

        $this->addElement(new \XoopsFormLabel('', '<br>' . _CO_SOBJECT_URL_FILE));
        $this->addElement(new SmartFormTextElement($object, 'url_' . $key));
        $this->addElement(new \XoopsFormLabel('', '<br><br>'));
        $delete_check = new SmartFormCheckElement('', 'delete_' . $key);
        $delete_check->addOption(1, '<span style="color:red;">' . _CO_SOBJECT_DELETE . '</span>');
        $this->addElement($delete_check);
    }
}
