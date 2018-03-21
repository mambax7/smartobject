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
 * Class SmartPersistableMlObjectHandler
 */
class SmartPersistableMlObjectHandler extends Smartobject\SmartPersistableObjectHandler
{
    /**
     * @param  null|\CriteriaElement  $criteria
     * @param  bool $id_as_key
     * @param  bool $as_object
     * @param  bool $debug
     * @param  bool $language
     * @return array
     */
    public function getObjects(
        \CriteriaElement $criteria = null,
        $id_as_key = false,
        $as_object = true,
        $debug = false,
        $language = false
    ) {
        // Create the first part of the SQL query to join the "_text" table
        $sql = 'SELECT * FROM ' . $this->table . ' AS ' . $this->_itemname . ' INNER JOIN ' . $this->table . '_text AS ' . $this->_itemname . '_text ON ' . $this->_itemname . '.' . $this->keyName . '=' . $this->_itemname . '_text.' . $this->keyName;

        if ($language) {
            // If a language was specified, then let's create a WHERE clause to only return the objects associated with this language

            // if no criteria was previously created, let's create it
            if (!$criteria) {
                $criteria = new \CriteriaCompo();
            }
            $criteria->add(new \Criteria('language', $language));

            return parent::getObjects($criteria, $id_as_key, $as_object, $debug, $sql);
        }

        return parent::getObjects($criteria, $id_as_key, $as_object, $debug, $sql);
    }

    /**
     * @param  mixed $id
     * @param  bool  $language
     * @param  bool  $as_object
     * @param  bool  $debug
     * @return mixed
     */
    public function &get($id, $language = false, $as_object = true, $debug = false)
    {
        if (!$language) {
            return parent::get($id, $as_object, $debug);
        } else {
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('language', $language));

            return parent::get($id, $as_object, $debug, $criteria);
        }
    }

    public function changeTableNameForML()
    {
        $this->table = $this->db->prefix($this->_moduleName . '_' . $this->_itemname . '_text');
    }
}
