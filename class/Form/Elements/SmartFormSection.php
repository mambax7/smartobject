<?php namespace XoopsModules\Smartobject\Form\Elements;
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
 * @author       XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */

use XoopsModules\Smartobject;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class SmartFormSection
 */
class SmartFormSection extends \XoopsFormElement
{
    /**
     * Text
     * @var string
     * @access  private
     */
    public $_value;

    /**
     * SmartFormSection constructor.
     * @param      $sectionname
     * @param bool $value
     */
    public function __construct($sectionname, $value = false)
    {
        $this->setName($sectionname);
        $this->_value = $value;
    }

    /**
     * Get the text
     *
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Prepare HTML for output
     *
     * @return string
     */
    public function render()
    {
        return $this->getValue();
    }
}
