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
 * @package
 * @since
 * @author     XOOPS Development Team
 */

use CriteriaElement;
use XoopsModules\Smartobject;

/**
 * Contains the classes for easily exporting data
 *
 * @license GNU
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 * @package SmartObject
 */

/**
 * SmartObjectExport class
 *
 * Class to easily export data from SmartObjects
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 */
class SmartObjectExport
{
    public $handler;
    public $criteria;
    public $fields;
    public $format;
    public $filename;
    public $filepath;
    public $options;
    public $outputMethods = false;
    public $notDisplayFields;

    /**
     * Constructor
     *
     * @param SmartPersistableObjectHandler $objectHandler SmartObjectHandler handling the data we want to export
     * @param \CriteriaElement               $criteria      containing the criteria of the query fetching the objects to be exported
     * @param array|bool                    $fields        fields to be exported. If FALSE then all fields will be exported
     * @param bool|string                   $filename      name of the file to be created
     * @param bool|string                   $filepath      path where the file will be saved
     * @param string                        $format        format of the ouputed export. Currently only supports CSV
     * @param array|bool                    $options       options of the format to be exported in
     */
    public function __construct(
        SmartPersistableObjectHandler $objectHandler,
        \CriteriaElement $criteria = null,
        $fields = false,
        $filename = false,
        $filepath = false,
        $format = 'csv',
        $options = false
    ) {
        $this->handler          = $objectHandler;
        $this->criteria         = $criteria;
        $this->fields           = $fields;
        $this->filename         = $filename;
        $this->format           = $format;
        $this->options          = $options;
        $this->notDisplayFields = false;
    }

    /**
     * Renders the export
     * @param $filename
     */
    public function render($filename)
    {
        $this->filename = $filename;

        $objects        = $this->handler->getObjects($this->criteria);
        $rows           = [];
        $columnsHeaders = [];
        $firstObject    = true;
        foreach ($objects as $object) {
            $row = [];
            foreach ($object->vars as $key => $var) {
                if ((!$this->fields || in_array($key, $this->fields)) && !in_array($key, $this->notDisplayFields)) {
                    if ($this->outputMethods && isset($this->outputMethods[$key])
                        && method_exists($object, $this->outputMethods[$key])) {
                        $method    = $this->outputMethods[$key];
                        $row[$key] = $object->$method();
                    } else {
                        $row[$key] = $object->getVar($key);
                    }
                    if ($firstObject) {
                        // then set the columnsHeaders array as well
                        $columnsHeaders[$key] = $var['form_caption'];
                    }
                }
            }
            $firstObject = false;
            $rows[]      = $row;
            unset($row);
        }
        $data                   = [];
        $data['rows']           = $rows;
        $data['columnsHeaders'] = $columnsHeaders;
        $smartExportRenderer    = new SmartExportRenderer($data, $this->filename, $this->filepath, $this->format, $this->options);
        $smartExportRenderer->execute();
    }

    /**
     * Set an array contaning the alternate methods to use instead of the default getVar()
     *
     * $outputMethods array example: 'uid' => 'getUserName'...
     * @param $outputMethods
     */
    public function setOuptutMethods($outputMethods)
    {
        $this->outputMethods = $outputMethods;
    }

    /*
     * Set an array of fields that we don't want in export
     */
    /**
     * @param $fields
     */
    public function setNotDisplayFields($fields)
    {
        if (!$this->notDisplayFields) {
            if (is_array($fields)) {
                $this->notDisplayFields = $fields;
            } else {
                $this->notDisplayFields = [$fields];
            }
        } else {
            if (is_array($fields)) {
                $this->notDisplayFields = array_merge($this->notDisplayFields, $fields);
            } else {
                $this->notDisplayFields[] = $fields;
            }
        }
    }
}
