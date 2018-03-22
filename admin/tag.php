<?php

use XoopsModules\Smartobject\SmartObjectColumn;
use XoopsModules\Smartobject\SmartObjectController;
use XoopsModules\Smartobject\SmartObjectTable;

/**
 *
 * Module: SmartShop
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 * @param int  $tagid
 * @param bool $language
 * @param bool $fct
 */

function edittag($tagid = 0, $language = false, $fct = false)
{
    global $smartobjectTagHandler;

    $tagObj = $smartobjectTagHandler->get($tagid);

    if ($tagObj->isNew()) {
        $breadcrumb            = _AM_SOBJECT_TAGS . ' > ' . _AM_SOBJECT_TAG_CREATE;
        $title                 = _AM_SOBJECT_TAG_CREATE;
        $info                  = _AM_SOBJECT_TAG_CREATE_INFO;
        $collaps_name          = 'tagcreate';
        $form_name             = _AM_SOBJECT_TAG_CREATE;
        $submit_button_caption = null;
    //$tagObj->stripMultilanguageFields();
    } else {
        if ($language) {
            $breadcrumb            = _AM_SOBJECT_TAGS . ' > ' . _AM_SOBJECT_TAG_EDITING_LANGUAGE;
            $title                 = _AM_SOBJECT_TAG_EDIT_LANGUAGE;
            $info                  = _AM_SOBJECT_TAG_EDIT_LANGUAGE_INFO;
            $collaps_name          = 'tageditlanguage';
            $form_name             = _AM_SOBJECT_TAG_EDIT_LANGUAGE;
            $submit_button_caption = null;
            $tagObj->makeNonMLFieldReadOnly();
        } else {
            $breadcrumb            = _AM_SOBJECT_TAGS . ' > ' . _AM_SOBJECT_EDITING;
            $title                 = _AM_SOBJECT_TAG_EDIT;
            $info                  = _AM_SOBJECT_TAG_EDIT_INFO;
            $collaps_name          = 'tagedit';
            $form_name             = _AM_SOBJECT_TAG_EDIT;
            $submit_button_caption = null;
            $tagObj->stripMultilanguageFields();
        }
    }

    //Smartobject\Utility::getAdminMenu(2, $breadcrumb);

    Smartobject\Utility::getCollapsableBar($collaps_name, $title, $info);

    $sform = $tagObj->getForm($form_name, 'addtag', false, $submit_button_caption);
    $sform->display();
    Smartobject\Utility::closeCollapsable($collaps_name);
}

require_once __DIR__ . '/admin_header.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttag.php';

$smartobjectTagHandler = xoops_getModuleHandler('tag');

$op = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

$tagid    = isset($_GET['tagid']) ? $_GET['tagid'] : 0;
$fct      = isset($_GET['fct']) ? $_GET['fct'] : '';
$language = isset($_GET['language']) ? $_GET['language'] : false;

switch ($op) {

    case 'del':
        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new SmartObjectController($smartobjectTagHandler);
        $controller->handleObjectDeletion(_AM_SOBJECT_TAG_DELETE_CONFIRM);

        break;

    case 'addtag':
        require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobjectcontroller.php';
        $controller = new SmartObjectController($smartobjectTagHandler);
        $tagObj     = $controller->storeSmartObject();
        if ($tagObj->hasError()) {
            redirect_header($smart_previous_page, 3, _CO_SOBJECT_SAVE_ERROR . $tagObj->getHtmlErrors());
        }

        if ($tagObj->hasError()) {
            redirect_header($smart_previous_page, 3, _CO_SOBJECT_SAVE_ERROR . $tagObj->getHtmlErrors());
        } else {
            redirect_header(Smartobject\Utility::getPageBeforeForm(), 3, _CO_SOBJECT_SAVE_SUCCESS);
        }
        exit;
        break;

    case 'mod':
        Smartobject\Utility::getXoopsCpHeader();
        edittag($tagid, $language, $fct);
        break;

    default:

        Smartobject\Utility::getXoopsCpHeader();

        //Smartobject\Utility::getAdminMenu(2, _AM_SOBJECT_TAGS);

        Smartobject\Utility::getCollapsableBar('tags', _AM_SOBJECT_TAGS, _AM_SOBJECT_TAGS_INFO);

        require_once SMARTOBJECT_ROOT_PATH . 'class/smartobjecttable.php';
        $objectTable = new SmartObjectTable($smartobjectTagHandler, false, ['delete']);
        $objectTable->addColumn(new SmartObjectColumn('name'));
        $objectTable->addColumn(new SmartObjectColumn('language'));
        $objectTable->addColumn(new SmartObjectColumn('value'));
        //      $objectTable->addColumn(new SmartObjectColumn(_AM_SOBJECT_SENT_TAGS_FROM, $align='left', $width=false, 'getFromInfo'));

        $objectTable->addFilter('language', 'getLanguages');

        $objectTable->addCustomAction('getEditLanguageLink');
        $objectTable->addCustomAction('getEditItemLink');

        $objectTable->setDefaultSort('tagid');

        $objectTable->addIntroButton('addtag', 'tag.php?op=mod', _AM_SOBJECT_TAG_CREATE);

        $objectTable->render();

        echo '<br>';
        Smartobject\Utility::closeCollapsable('tags');
        echo '<br>';

        break;
}

//Smartobject\Utility::getModFooter();
//xoops_cp_footer();
require_once __DIR__ . '/admin_footer.php';
