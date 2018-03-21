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
 * @link    http://www.smartfactory.ca The SmartFactory
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @author     XOOPS Development Team
 */

use XoopsModules\Smartobject;

/**
 * Contains the classes for updating database tables
 *
 * @license GNU

 */
/**
 * SmartDbTable class
 *
 * Information about an individual table
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 */
// defined('XOOPS_ROOT_PATH') || die('Restricted access');
if (!defined('SMARTOBJECT_ROOT_PATH')) {
    require_once XOOPS_ROOT_PATH . '/modules/smartobject/include/common.php';
}
/**
 * Include the language constants for the SmartObjectDBUpdater
 */
global $xoopsConfig;
$common_file = SMARTOBJECT_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/smartdbupdater.php';
if (!file_exists($common_file)) {
    $common_file = SMARTOBJECT_ROOT_PATH . 'language/english/smartdbupdater.php';
}
include $common_file;

/**
 * Class SmartDbTable
 */
class SmartDbTable
{
    /**
     * @var string $_name name of the table
     */
    public $_name;
    /**
     * @var string $_structure structure of the table
     */
    public $_structure;

    /**
     * @var array $_data containing valued of each records to be added
     */
    public $_data;

    /**
     * @var array $_alteredFields containing fields to be altered
     */
    public $_alteredFields;

    /**
     * @var array $_newFields containing new fields to be added
     */
    public $_newFields;

    /**
     * @var array $_droppedFields containing fields to be dropped
     */
    public $_droppedFields;

    /**
     * @var array $_flagForDrop flag table to drop it
     */
    public $_flagForDrop = false;

    /**
     * @var array $_updatedFields containing fields which values will be updated
     */
    public $_updatedFields;

    /**
     * @var array $_updatedFields containing fields which values will be updated
     */ //felix
    public $_updatedWhere;

    public $_existingFieldsArray = false;

    /**
     * Constructor
     *
     * @param string $name name of the table
     *
     */
    public function __construct($name)
    {
        $this->_name = $name;
        $this->_data = [];
    }

    /**
     * Return the table name, prefixed with site table prefix
     *
     * @return string table name
     *
     */
    public function name()
    {
        global $xoopsDB;

        return $xoopsDB->prefix($this->_name);
    }

    /**
     * Checks if the table already exists in the database
     *
     * @return bool TRUE if it exists, FALSE if not
     *
     */
    public function exists()
    {
        return smart_TableExists($this->_name);
    }

    /**
     * @return mixed
     */
    public function getExistingFieldsArray()
    {
        global $xoopsDB;
        $result = $xoopsDB->query('SHOW COLUMNS FROM ' . $this->name());
        while (false !== ($existing_field = $xoopsDB->fetchArray($result))) {
            $fields[$existing_field['Field']] = $existing_field['Type'];
            if ('YES' !== $existing_field['Null']) {
                $fields[$existing_field['Field']] .= ' NOT NULL';
            }
            if ($existing_field['Extra']) {
                $fields[$existing_field['Field']] .= ' ' . $existing_field['Extra'];
            }
            if (!(null === $existing_field['Default'])
                && ($existing_field['Default']
                    || '' === $existing_field['Default']
                    || 0 == $existing_field['Default'])) {
                $fields[$existing_field['Field']] .= " default '" . $existing_field['Default'] . "'";
            }
        }

        return $fields;
    }

    /**
     * @param $field
     * @return bool
     */
    public function fieldExists($field)
    {
        $existingFields = $this->getExistingFieldsArray();

        return isset($existingFields[$field]);
    }

    /**
     * Set the table structure
     *
     * Example:
     *
     *      $table->setStructure("`transactionid` int(11) NOT NULL auto_increment,
     *                `date` int(11) NOT NULL default '0',
     *                `status` int(1) NOT NULL default '-1',
     *                `itemid` int(11) NOT NULL default '0',
     *                `uid` int(11) NOT NULL default '0',
     *                `price` float NOT NULL default '0',
     *                `currency` varchar(100) NOT NULL default '',
     *                PRIMARY KEY  (`transactionid`)");
     *
     * @param string $structure table structure
     *
     */
    public function setStructure($structure)
    {
        $this->_structure = $structure;
    }

    /**
     * Return the table structure
     *
     * @return string table structure
     *
     */
    public function getStructure()
    {
        return sprintf($this->_structure, $this->name());
    }

    /**
     * Add values of a record to be added
     *
     * @param string $data values of a record
     *
     */
    public function setData($data)
    {
        $this->_data[] = $data;
    }

    /**
     * Get the data array
     *
     * @return array containing the records values to be added
     *
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Use to insert data in a table
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function addData()
    {
        global $xoopsDB;
        foreach ($this->getData() as $data) {
            $query = sprintf('INSERT INTO %s VALUES (%s)', $this->name(), $data);
            $ret   = $xoopsDB->query($query);
            if (!$ret) {
                echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_ADD_DATA_ERR, $this->name()) . '<br>';
            } else {
                echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_ADD_DATA, $this->name()) . '<br>';
            }
        }

        return $ret;
    }

    /**
     * Add a field to be added
     *
     * @param string $name       name of the field
     * @param string $properties properties of the field
     * @param bool   $newname
     * @param bool   $showerror
     */
    public function addAlteredField($name, $properties, $newname = false, $showerror = true)
    {
        $field['name']          = $name;
        $field['properties']    = $properties;
        $field['showerror']     = $showerror;
        $field['newname']       = $newname;
        $this->_alteredFields[] = $field;
    }
    /**
     * Invert values 0 to 1 and 1 to 0
     *
     * @param string $name name of the field
     * @param        $newValue
     * @param        $oldValue
     * @internal param string $old old propertie
     * @internal param string $new new propertie
     */ //felix
    public function addUpdatedWhere($name, $newValue, $oldValue)
    {
        $field['name']         = $name;
        $field['value']        = $newValue;
        $field['where']        = $oldValue;
        $this->_updatedWhere[] = $field;
    }

    /**
     * Add new field of a record to be added
     *
     * @param string $name       name of the field
     * @param string $properties properties of the field
     *
     */
    public function addNewField($name, $properties)
    {
        $field['name']       = $name;
        $field['properties'] = $properties;
        $this->_newFields[]  = $field;
    }

    /**
     * Get fields that need to be altered
     *
     * @return array fields that need to be altered
     *
     */
    public function getAlteredFields()
    {
        return $this->_alteredFields;
    }

    /**
     * Add field for which the value will be updated
     *
     * @param string $name  name of the field
     * @param string $value value to be set
     *
     */
    public function addUpdatedField($name, $value)
    {
        $field['name']          = $name;
        $field['value']         = $value;
        $this->_updatedFields[] = $field;
    }

    /**
     * Get new fields to be added
     *
     * @return array fields to be added
     *
     */
    public function getNewFields()
    {
        return $this->_newFields;
    }

    /**
     * Get fields which values need to be updated
     *
     * @return array fields which values need to be updated
     *
     */
    public function getUpdatedFields()
    {
        return $this->_updatedFields;
    }
    /**
     * Get fields which values need to be updated
     *
     * @return array fields which values need to be updated
     *
     */ //felix
    public function getUpdatedWhere()
    {
        return $this->_updatedWhere;
    }

    /**
     * Add values of a record to be added
     *
     * @param string $name name of the field
     *
     */
    public function addDroppedField($name)
    {
        $this->_droppedFields[] = $name;
    }

    /**
     * Get fields that need to be dropped
     *
     * @return array fields that need to be dropped
     *
     */
    public function getDroppedFields()
    {
        return $this->_droppedFields;
    }

    /**
     * Set the flag to drop the table
     *
     */
    public function setFlagForDrop()
    {
        $this->_flagForDrop = true;
    }

    /**
     * Use to create a table
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function createTable()
    {
        global $xoopsDB;
        $query = $this->getStructure();
        $query = 'CREATE TABLE `' . $this->name() . '` (' . $query . ") ENGINE=MyISAM COMMENT='The SmartFactory <www.smartfactory.ca>'";
        //xoops_debug($query);
        $ret = $xoopsDB->query($query);
        if (!$ret) {
            echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_CREATE_TABLE_ERR, $this->name()) . ' (' . $xoopsDB->error() . ')<br>';
        } else {
            echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_CREATE_TABLE, $this->name()) . '<br>';
        }

        return $ret;
    }

    /**
     * Use to drop a table
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function dropTable()
    {
        global $xoopsDB;
        $query = sprintf('DROP TABLE %s', $this->name());
        $ret   = $xoopsDB->query($query);
        if (!$ret) {
            echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_DROP_TABLE_ERR, $this->name()) . ' (' . $xoopsDB->error() . ')<br>';

            return false;
        } else {
            echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_DROP_TABLE, $this->name()) . '<br>';

            return true;
        }
    }

    /**
     * Use to alter a table
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function alterTable()
    {
        global $xoopsDB;
        $ret = true;

        foreach ($this->getAlteredFields() as $alteredField) {
            if (!$alteredField['newname']) {
                $alteredField['newname'] = $alteredField['name'];
            }

            $query = sprintf('ALTER TABLE `%s` CHANGE `%s` `%s` %s', $this->name(), $alteredField['name'], $alteredField['newname'], $alteredField['properties']);
            $ret   = $ret && $xoopsDB->query($query);
            if ($alteredField['showerror']) {
                if (!$ret) {
                    echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_CHGFIELD_ERR, $alteredField['name'], $this->name()) . ' (' . $xoopsDB->error() . ')<br>';
                } else {
                    echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_CHGFIELD, $alteredField['name'], $this->name()) . '<br>';
                }
            }
        }

        return $ret;
    }

    /**
     * Use to add new fileds in the table
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function addNewFields()
    {
        global $xoopsDB;
        $ret = true;
        foreach ($this->getNewFields() as $newField) {
            $query = sprintf('ALTER TABLE `%s` ADD `%s` %s', $this->name(), $newField['name'], $newField['properties']);
            //echo $query;
            $ret = $ret && $xoopsDB->query($query);
            if (!$ret) {
                echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_NEWFIELD_ERR, $newField['name'], $this->name()) . '<br>';
            } else {
                echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_NEWFIELD, $newField['name'], $this->name()) . '<br>';
            }
        }

        return $ret;
    }

    /**
     * Use to update fields values
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function updateFieldsValues()
    {
        global $xoopsDB;
        $ret = true;
        foreach ($this->getUpdatedFields() as $updatedField) {
            $query = sprintf('UPDATE %s SET %s = %s', $this->name(), $updatedField['name'], $updatedField['value']);
            $ret   = $ret && $xoopsDB->query($query);
            if (!$ret) {
                echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_UPDATE_TABLE_ERR, $this->name()) . ' (' . $xoopsDB->error() . ')<br>';
            } else {
                echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_UPDATE_TABLE, $this->name()) . '<br>';
            }
        }

        return $ret;
    }
    /**
     * Use to update fields values
     *
     * @return bool true if success, false if an error occured
     *
     */ //felix
    public function updateWhereValues()
    {
        global $xoopsDB;
        $ret = true;
        foreach ($this->getUpdatedWhere() as $updatedWhere) {
            $query = sprintf('UPDATE %s SET %s = %s WHERE %s  %s', $this->name(), $updatedWhere['name'], $updatedWhere['value'], $updatedWhere['name'], $updatedWhere['where']);
            //echo $query."<br>";
            $ret = $ret && $xoopsDB->query($query);
            if (!$ret) {
                echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_UPDATE_TABLE_ERR, $this->name()) . ' (' . $xoopsDB->error() . ')<br>';
            } else {
                echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_UPDATE_TABLE, $this->name()) . '<br>';
            }
        }

        return $ret;
    }

    /**
     * Use to drop fields
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function dropFields()
    {
        global $xoopsDB;
        $ret = true;
        foreach ($this->getDroppedFields() as $droppedField) {
            $query = sprintf('ALTER TABLE %s DROP %s', $this->name(), $droppedField);
            $ret   = $ret && $xoopsDB->query($query);
            if (!$ret) {
                echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_DROPFIELD_ERR, $droppedField, $this->name()) . ' (' . $xoopsDB->error() . ')<br>';
            } else {
                echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_DROPFIELD, $droppedField, $this->name()) . '<br>';
            }
        }

        return $ret;
    }
}
