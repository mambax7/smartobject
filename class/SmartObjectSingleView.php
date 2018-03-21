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
 * SmartObjectSingleView base class
 *
 * Base class handling the display of a single object
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://smartfactory.ca The SmartFactory
 */
class SmartObjectSingleView
{
    public $_object;
    public $_userSide;
    public $_tpl;
    public $_rows;
    public $_actions;
    public $_headerAsRow = true;

    /**
     * Constructor
     * @param       $object
     * @param bool  $userSide
     * @param array $actions
     * @param bool  $headerAsRow
     */
    public function __construct($object, $userSide = false, $actions = [], $headerAsRow = true)
    {
        $this->_object      = $object;
        $this->_userSide    = $userSide;
        $this->_actions     = $actions;
        $this->_headerAsRow = $headerAsRow;
    }

    /**
     * @param $rowObj
     */
    public function addRow($rowObj)
    {
        $this->_rows[] = $rowObj;
    }

    /**
     * @param  bool $fetchOnly
     * @param  bool $debug
     * @return mixed|string|void
     */
    public function render($fetchOnly = false, $debug = false)
    {
        require_once XOOPS_ROOT_PATH . '/class/template.php';

        $this->_tpl             = new \XoopsTpl();
        $vars                   = $this->_object->vars;
        $smartobjectObjectArray = [];

        foreach ($this->_rows as $row) {
            $key = $row->getKeyName();
            if ($row->_customMethodForValue && method_exists($this->_object, $row->_customMethodForValue)) {
                $method = $row->_customMethodForValue;
                $value  = $this->_object->$method();
            } else {
                $value = $this->_object->getVar($row->getKeyName());
            }
            if ($row->isHeader()) {
                $this->_tpl->assign('smartobject_single_view_header_caption', $this->_object->vars[$key]['form_caption']);
                $this->_tpl->assign('smartobject_single_view_header_value', $value);
            } else {
                $smartobjectObjectArray[$key]['value']   = $value;
                $smartobjectObjectArray[$key]['header']  = $row->isHeader();
                $smartobjectObjectArray[$key]['caption'] = $this->_object->vars[$key]['form_caption'];
            }
        }
        $action_row = '';
        if (in_array('edit', $this->_actions)) {
            $action_row .= $this->_object->getEditItemLink(false, true, true);
        }
        if (in_array('delete', $this->_actions)) {
            $action_row .= $this->_object->getDeleteItemLink(false, true, true);
        }
        if ($action_row) {
            $smartobjectObjectArray['zaction']['value']   = $action_row;
            $smartobjectObjectArray['zaction']['caption'] = _CO_SOBJECT_ACTIONS;
        }

        $this->_tpl->assign('smartobject_header_as_row', $this->_headerAsRow);
        $this->_tpl->assign('smartobject_object_array', $smartobjectObjectArray);

        if ($fetchOnly) {
            return $this->_tpl->fetch('db:smartobject_singleview_display.tpl');
        } else {
            $this->_tpl->display('db:smartobject_singleview_display.tpl');
        }
    }

    /**
     * @param  bool $debug
     * @return mixed|string|void
     */
    public function fetch($debug = false)
    {
        return $this->render(true, $debug);
    }
}
