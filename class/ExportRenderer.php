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
 * SmartExportRenderer class
 *
 * Class that renders a set of data into a specific export format
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 */
class ExportRenderer
{
    public $data;
    public $format;
    public $filename;
    public $filepath;
    public $options;

    /**
     * Constructor
     *
     * @param array       $data     contains the data to be exported
     * @param bool|string $filename name of the file in which the exported data will be saved
     * @param bool|string $filepath path where the file will be saved
     * @param string      $format   format of the ouputed export. Currently only supports CSV
     * @param array       $options  options of the format to be exported in
     */
    public function __construct(
        $data,
        $filename = false,
        $filepath = false,
        $format = 'csv',
        $options = ['separator' => ';']
    ) {
        $this->data     = $data;
        $this->format   = $format;
        $this->filename = $filename;
        $this->filepath = $filepath;
        $this->options  = $options;
    }

    /**
     * @param         $dataArray
     * @param         $separator
     * @param  string $trim
     * @param  bool   $removeEmptyLines
     * @return string
     */
    public function arrayToCsvString($dataArray, $separator, $trim = 'both', $removeEmptyLines = true)
    {
        if (!is_array($dataArray) || empty($dataArray)) {
            return '';
        }
        switch ($trim) {
            case 'none':
                $trimFunction = false;
                break;
            case 'left':
                $trimFunction = 'ltrim';
                break;
            case 'right':
                $trimFunction = 'rtrim';
                break;
            default: //'both':
                $trimFunction = 'trim';
                break;
        }
        $ret = [];
        foreach ($dataArray as $key => $field) {
            $ret[$key] = $this->valToCsvHelper($field, $separator, $trimFunction);
        }

        return implode($separator, $ret);
    }

    /**
     * @param $val
     * @param $separator
     * @param $trimFunction
     * @return mixed|string
     */
    public function valToCsvHelper($val, $separator, $trimFunction)
    {
        if ($trimFunction) {
            $val = $trimFunction($val);
        }
        //If there is a separator (;) or a quote (") or a linebreak in the string, we need to quote it.
        $needQuote = false;
        do {
            if (false !== strpos($val, '"')) {
                $val       = str_replace('"', '""', $val);
                $needQuote = true;
                break;
            }
            if (false !== strpos($val, $separator)) {
                $needQuote = true;
                break;
            }
            if ((false !== strpos($val, "\n")) || (false !== strpos($val, "\r"))) { // \r is for mac
                $needQuote = true;
                break;
            }
        } while (false);
        if ($needQuote) {
            $val = '"' . $val . '"';
        }

        return $val;
    }

    public function execute()
    {
        $exportFileData = '';

        switch ($this->format) {
            case 'csv':
                $separator      = isset($this->options['separator']) ? $this->options['separator'] : ';';
                $firstRow       = implode($separator, $this->data['columnsHeaders']);
                $exportFileData .= $firstRow . "\r\n";

                foreach ($this->data['rows'] as $cols) {
                    $exportFileData .= $this->arrayToCsvString($cols, $separator) . "\r\n";
                }
                break;
        }
        $this->saveExportFile($exportFileData);
    }

    /**
     * @param $content
     */
    public function saveExportFile($content)
    {
        switch ($this->format) {
            case 'csv':
                $this->saveCsv($content);
                break;
        }
    }

    /**
     * @param $content
     */
    public function saveCsv($content)
    {
        if (!$this->filepath) {
            $this->filepath = XOOPS_UPLOAD_PATH . '/';
        }
        if (!$this->filename) {
            $this->filename .= time();
            $this->filename .= '.csv';
        }

        $fullFileName = $this->filepath . $this->filename;

        if (!$handle = fopen($fullFileName, 'a+')) {
            trigger_error('Unable to open ' . $fullFileName, E_USER_WARNING);
        } elseif (false === fwrite($handle, $content)) {
            trigger_error('Unable to write in ' . $fullFileName, E_USER_WARNING);
        } else {
            $mimeType  = 'text/csv';
            $file      = strrev($this->filename);
            $temp_name = strtolower(strrev(substr($file, 0, strpos($file, '--'))));
            if ('' === $temp_name) {
                $file_name = $this->filename;
            } else {
                $file_name = $temp_name;
            }
            $fullFileName = $this->filepath . stripslashes(trim($this->filename));

            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }

            header('Pragma: public');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            header('Content-Transfer-Encoding: binary');
            if (isset($mimeType)) {
                header('Content-Type: ' . $mimeType);
            }

            header('Content-Disposition: attachment; filename=' . $file_name);

            if (isset($mimeType) && false !== strpos($mimeType, 'text/')) {
                $fp = fopen($fullFileName, 'r');
            } else {
                $fp = fopen($fullFileName, 'rb');
            }
            fpassthru($fp);
            exit();
        }
        fclose($handle);
    }
}
