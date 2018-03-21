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
 * SmartobjectDbupdater class
 *
 * Class performing the database update for the module
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 */
class SmartobjectDbupdater
{
    public $_dbTypesArray;

    /**
     * SmartobjectDbupdater constructor.
     */
    public function __construct()
    {
        $this->_dbTypesArray[XOBJ_DTYPE_TXTBOX]       = 'varchar(255)';
        $this->_dbTypesArray[XOBJ_DTYPE_TXTAREA]      = 'text';
        $this->_dbTypesArray[XOBJ_DTYPE_INT]          = 'int(11)';
        $this->_dbTypesArray[XOBJ_DTYPE_URL]          = 'varchar(255)';
        $this->_dbTypesArray[XOBJ_DTYPE_EMAIL]        = 'varchar(255)';
        $this->_dbTypesArray[XOBJ_DTYPE_ARRAY]        = 'text';
        $this->_dbTypesArray[XOBJ_DTYPE_OTHER]        = 'text';
        $this->_dbTypesArray[XOBJ_DTYPE_SOURCE]       = 'text';
        $this->_dbTypesArray[XOBJ_DTYPE_STIME]        = 'int(11)';
        $this->_dbTypesArray[XOBJ_DTYPE_MTIME]        = 'int(11)';
        $this->_dbTypesArray[XOBJ_DTYPE_LTIME]        = 'int(11)';
        $this->_dbTypesArray[XOBJ_DTYPE_SIMPLE_ARRAY] = 'text';
        $this->_dbTypesArray[XOBJ_DTYPE_CURRENCY]     = 'text';
        $this->_dbTypesArray[XOBJ_DTYPE_FLOAT]        = 'float';
        $this->_dbTypesArray[XOBJ_DTYPE_TIME_ONLY]    = 'int(11)';
        $this->_dbTypesArray[XOBJ_DTYPE_URLLINK]      = 'int(11)';
        $this->_dbTypesArray[XOBJ_DTYPE_FILE]         = 'int(11)';
        $this->_dbTypesArray[XOBJ_DTYPE_IMAGE]        = 'varchar(255)';
    }

    /**
     * Use to execute a general query
     *
     * @param string $query   query that will be executed
     * @param string $goodmsg message displayed on success
     * @param string $badmsg  message displayed on error
     *
     * @return bool true if success, false if an error occured
     *
     */
    public function runQuery($query, $goodmsg, $badmsg)
    {
        global $xoopsDB;
        $ret = $xoopsDB->query($query);
        if (!$ret) {
            echo "&nbsp;&nbsp;$badmsg<br>";

            return false;
        } else {
            echo "&nbsp;&nbsp;$goodmsg<br>";

            return true;
        }
    }

    /**
     * Use to rename a table
     *
     * @param string $from name of the table to rename
     * @param string $to   new name of the renamed table
     *
     * @return bool true if success, false if an error occured
     */
    public function renameTable($from, $to)
    {
        global $xoopsDB;
        $from  = $xoopsDB->prefix($from);
        $to    = $xoopsDB->prefix($to);
        $query = sprintf('ALTER TABLE %s RENAME %s', $from, $to);
        $ret   = $xoopsDB->query($query);
        if (!$ret) {
            echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_RENAME_TABLE_ERR, $from) . '<br>';

            return false;
        } else {
            echo '&nbsp;&nbsp;' . sprintf(_SDU_MSG_RENAME_TABLE, $from, $to) . '<br>';

            return true;
        }
    }

    /**
     * Use to update a table
     *
     * @param object $table {@link SmartDbTable} that will be updated
     *
     * @see SmartDbTable
     *
     * @return bool true if success, false if an error occured
     */
    public function updateTable($table)
    {
        global $xoopsDB;
        $ret = true;
        // If table has a structure, create the table
        if ($table->getStructure()) {
            $ret = $table->createTable() && $ret;
        }
        // If table is flag for drop, drop it
        if ($table->_flagForDrop) {
            $ret = $table->dropTable() && $ret;
        }
        // If table has data, insert it
        if ($table->getData()) {
            $ret = $table->addData() && $ret;
        }
        // If table has new fields to be added, add them
        if ($table->getNewFields()) {
            $ret = $table->addNewFields() && $ret;
        }
        // If table has altered field, alter the table
        if ($table->getAlteredFields()) {
            $ret = $table->alterTable() && $ret;
        }
        // If table has updated field values, update the table
        if ($table->getUpdatedFields()) {
            $ret = $table->updateFieldsValues($table) && $ret;
        }
        // If table has dropped field, alter the table
        if ($table->getDroppedFields()) {
            $ret = $table->dropFields($table) && $ret;
        }
        //felix
        // If table has updated field values, update the table
        if ($table->getUpdatedWhere()) {
            $ret = $table->UpdateWhereValues($table) && $ret;
        }

        return $ret;
    }

    /**
     * @param $module
     * @param $item
     */
    public function automaticUpgrade($module, $item)
    {
        if (is_array($item)) {
            foreach ($item as $v) {
                $this->upgradeObjectItem($module, $v);
            }
        } else {
            $this->upgradeObjectItem($module, $item);
        }
    }

    /**
     * @param $var
     * @return string
     */
    public function getFieldTypeFromVar($var)
    {
        $ret = isset($this->_dbTypesArray[$var['data_type']]) ? $this->_dbTypesArray[$var['data_type']] : 'text';

        return $ret;
    }

    /**
     * @param         $var
     * @param  bool   $key
     * @return string
     */
    public function getFieldDefaultFromVar($var, $key = false)
    {
        if ($var['value']) {
            return $var['value'];
        } else {
            if (in_array($var['data_type'], [
                XOBJ_DTYPE_INT,
                XOBJ_DTYPE_STIME,
                XOBJ_DTYPE_MTIME,
                XOBJ_DTYPE_LTIME,
                XOBJ_DTYPE_TIME_ONLY,
                XOBJ_DTYPE_URLLINK,
                XOBJ_DTYPE_FILE
            ])) {
                return '0';
            } else {
                return '';
            }
        }
    }

    /**
     * @param $module
     * @param $item
     * @return bool
     */
    public function upgradeObjectItem($module, $item)
    {
        $moduleHandler = xoops_getModuleHandler($item, $module);
        if (!$moduleHandler) {
            return false;
        }

        $table      = new SmartDbTable($module . '_' . $item);
        $object     = $moduleHandler->create();
        $objectVars = $object->getVars();

        if (!$table->exists()) {
            // table was never created, let's do it
            $structure = '';
            foreach ($objectVars as $key => $var) {
                if ($var['persistent']) {
                    $type = $this->getFieldTypeFromVar($var);
                    if ($key == $moduleHandler->keyName) {
                        $extra = 'auto_increment';
                    } else {
                        $default = $this->getFieldDefaultFromVar($var);
                        $extra   = "default '$default'
";
                    }
                    $structure .= "`$key` $type not null $extra,
";
                }
            }
            $structure .= 'PRIMARY KEY  (`' . $moduleHandler->keyName . '`)
';
            $table->setStructure($structure);
            if (!$this->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        } else {
            $existingFieldsArray = $table->getExistingFieldsArray();
            foreach ($objectVars as $key => $var) {
                if ($var['persistent']) {
                    if (!isset($existingFieldsArray[$key])) {
                        // the fiels does not exist, let's create it
                        $type    = $this->getFieldTypeFromVar($var);
                        $default = $this->getFieldDefaultFromVar($var);
                        $table->addNewField($key, "$type not null default '$default'");
                    } else {
                        // if field already exists, let's check if the definition is correct
                        $definition = strtolower($existingFieldsArray[$key]);
                        $type       = $this->getFieldTypeFromVar($var);
                        if ($key == $moduleHandler->keyName) {
                            $extra = 'auto_increment';
                        } else {
                            $default = $this->getFieldDefaultFromVar($var, $key);
                            $extra   = "default '$default'";
                        }
                        $actual_definition = "$type not null $extra";
                        if ($definition != $actual_definition) {
                            $table->addAlteredField($key, $actual_definition);
                        }
                    }
                }
            }

            // check to see if there are some unused fields left in the table
            foreach ($existingFieldsArray as $key => $v) {
                if (!isset($objectVars[$key]) || !$objectVars[$key]['persistent']) {
                    $table->addDroppedField($key);
                }
            }

            if (!$this->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }
    }

    /**
     * @param $module
     * @return bool
     */
    public function moduleUpgrade(\XoopsModule $module)
    {
        $dirname = $module->getVar('dirname');

        ob_start();

        $table = new SmartDbTable($dirname . '_meta');
        if (!$table->exists()) {
            $table->setStructure("
              `metakey` varchar(50) NOT NULL default '',
              `metavalue` varchar(255) NOT NULL default '',
              PRIMARY KEY (`metakey`)");
            $table->setData("'version',0");
            if (!$this->updateTable($table)) {
                /**
                 * @todo trap the errors
                 */
            }
        }

        $dbVersion = smart_GetMeta('version', $dirname);
        if (!$dbVersion) {
            $dbVersion = 0;
        }
        $newDbVersion = constant(strtoupper($dirname . '_db_version')) ?: 0;
        echo 'Database version: ' . $dbVersion . '<br>';
        echo 'New database version: ' . $newDbVersion . '<br>';

        if ($newDbVersion > $dbVersion) {
            for ($i = $dbVersion + 1; $i <= $newDbVersion; ++$i) {
                $upgrade_function = $dirname . '_db_upgrade_' . $i;
                if (function_exists($upgrade_function)) {
                    $upgrade_function();
                }
            }
        }

        echo '<code>' . _SDU_UPDATE_UPDATING_DATABASE . '<br>';

        // if there is a function to execute for this DB version, let's do it
        //$function_

        $module_info = smart_getModuleInfo($dirname);
        $this->automaticUpgrade($dirname, $module_info->modinfo['object_items']);

        echo '</code>';

        $feedback = ob_get_clean();
        if (method_exists($module, 'setMessage')) {
            $module->setMessage($feedback);
        } else {
            echo $feedback;
        }
        smart_SetMeta('version', $newDbVersion, $dirname); //Set meta version to current

        return true;
    }
}
