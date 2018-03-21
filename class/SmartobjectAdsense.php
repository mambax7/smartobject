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

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

//require_once XOOPS_ROOT_PATH . '/modules/smartobject/class/smartobject.php';

/**
 * Class SmartobjectAdsense
 */
class SmartobjectAdsense extends Smartobject\BaseSmartObject
{
    /**
     * SmartobjectAdsense constructor.
     */
    public function __construct()
    {
        $this->quickInitVar('adsenseid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, true, _CO_SOBJECT_ADSENSE_DESCRIPTION, _CO_SOBJECT_ADSENSE_DESCRIPTION_DSC);
        $this->quickInitVar('client_id', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_ADSENSE_CLIENT_ID, _CO_SOBJECT_ADSENSE_CLIENT_ID_DSC);
        $this->quickInitVar('tag', XOBJ_DTYPE_TXTBOX, false, _CO_SOBJECT_ADSENSE_TAG, _CO_SOBJECT_ADSENSE_TAG_DSC);
        $this->quickInitVar('format', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_ADSENSE_FORMAT, _CO_SOBJECT_ADSENSE_FORMAT_DSC);
        $this->quickInitVar('border_color', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_ADSENSE_BORDER_COLOR, _CO_SOBJECT_ADSENSE_BORDER_COLOR_DSC);
        $this->quickInitVar('background_color', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_ADSENSE_BACKGROUND_COLOR, _CO_SOBJECT_ADSENSE_BORDER_COLOR_DSC);
        $this->quickInitVar('link_color', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_ADSENSE_LINK_COLOR, _CO_SOBJECT_ADSENSE_LINK_COLOR_DSC);
        $this->quickInitVar('url_color', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_ADSENSE_URL_COLOR, _CO_SOBJECT_ADSENSE_URL_COLOR_DSC);
        $this->quickInitVar('text_color', XOBJ_DTYPE_TXTBOX, true, _CO_SOBJECT_ADSENSE_TEXT_COLOR, _CO_SOBJECT_ADSENSE_TEXT_COLOR_DSC);
        $this->quickInitVar('style', XOBJ_DTYPE_TXTAREA, false, _CO_SOBJECT_ADSENSE_STYLE, _CO_SOBJECT_ADSENSE_STYLE_DSC);

        $this->setControl('format', [
            'handler' => 'adsense',
            'method'  => 'getFormats'
        ]);

        $this->setControl('border_color', [
            'name'      => 'text',
            'size'      => 6,
            'maxlength' => 6
        ]);

        $this->setControl('background_color', [
            'name'      => 'text',
            'size'      => 6,
            'maxlength' => 6
        ]);

        $this->setControl('link_color', [
            'name'      => 'text',
            'size'      => 6,
            'maxlength' => 6
        ]);

        $this->setControl('url_color', [
            'name'      => 'text',
            'size'      => 6,
            'maxlength' => 6
        ]);

        $this->setControl('text_color', [
            'name'      => 'text',
            'size'      => 6,
            'maxlength' => 6
        ]);
    }

    /**
     * @param  string $key
     * @param  string $format
     * @return mixed
     */
    public function getVar($key, $format = 's')
    {
        if ('s' === $format && in_array($key, [])) {
            //            return call_user_func(array($this, $key));
            return $this->{$key}();
        }

        return parent::getVar($key, $format);
    }

    /**
     * @return string
     */
    public function render()
    {
        global $smartobjectAdsenseHandler;
        if ('' !== $this->getVar('style', 'n')) {
            $ret = '<div style="' . $this->getVar('style', 'n') . '">';
        } else {
            $ret = '<div>';
        }

        $ret .= '<script type="text/javascript"><!--
google_ad_client = "' . $this->getVar('client_id', 'n') . '";
google_ad_width = ' . $smartobjectAdsenseHandler->adFormats[$this->getVar('format', 'n')]['width'] . ';
google_ad_height = ' . $smartobjectAdsenseHandler->adFormats[$this->getVar('format', 'n')]['height'] . ';
google_ad_format = "' . $this->getVar('format', 'n') . '";
google_ad_type = "text";
google_ad_channel ="";
google_color_border = "' . $this->getVar('border_color', 'n') . '";
google_color_bg = "' . $this->getVar('background_color', 'n') . '";
google_color_link = "' . $this->getVar('link_color', 'n') . '";
google_color_url = "' . $this->getVar('url_color', 'n') . '";
google_color_text = "' . $this->getVar('text_color', 'n') . '";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>';

        return $ret;
    }

    /**
     * @return string
     */
    public function getXoopsCode()
    {
        $ret = '[adsense]' . $this->getVar('tag', 'n') . '[/adsense]';

        return $ret;
    }

    /**
     * @param $var
     * @return bool
     */
    public function emptyString($var)
    {
        return (strlen($var) > 0);
    }

    /**
     * @return mixed|string
     */
    public function generateTag()
    {
        $title = rawurlencode(strtolower($this->getVar('description', 'e')));
        $title = xoops_substr($title, 0, 10, '');
        // Transformation des ponctuations
        $pattern = [
            '/%09/', // Tab
            '/%20/', // Space
            '/%21/', // !
            '/%22/', // "
            '/%23/', // #
            '/%25/', // %
            '/%26/', // &
            '/%27/', // '
            '/%28/', // (
            '/%29/', // )
            '/%2C/', // ,
            '/%2F/', // /
            '/%3A/', // :
            '/%3B/', // ;
            '/%3C/', // <
            '/%3D/', // =
            '/%3E/', // >
            '/%3F/', // ?
            '/%40/', // @
            '/%5B/', // [
            '/%5C/', // \
            '/%5D/', // ]
            '/%5E/', // ^
            '/%7B/', // {
            '/%7C/', // |
            '/%7D/', // }
            '/%7E/', // ~
            "/\./" // .
        ];
        $rep_pat = [
            '-',
            '-',
            '-',
            '-',
            '-',
            '-100',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-at-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-',
            '-'
        ];
        $title   = preg_replace($pattern, $rep_pat, $title);

        // Transformation des caractères accentués
        $pattern = [
            '/%B0/', // °
            '/%E8/', // è
            '/%E9/', // é
            '/%EA/', // ê
            '/%EB/', // ë
            '/%E7/', // ç
            '/%E0/', // à
            '/%E2/', // â
            '/%E4/', // ä
            '/%EE/', // î
            '/%EF/', // ï
            '/%F9/', // ù
            '/%FC/', // ü
            '/%FB/', // û
            '/%F4/', // ô
            '/%F6/', // ö
        ];
        $rep_pat = ['-', 'e', 'e', 'e', 'e', 'c', 'a', 'a', 'a', 'i', 'i', 'u', 'u', 'u', 'o', 'o'];
        $title   = preg_replace($pattern, $rep_pat, $title);

        $tableau = explode('-', $title); // Transforme la chaine de caract�res en tableau
        $tableau = array_filter($tableau, [$this, 'emptyString']); // Supprime les chaines vides du tableau
        $title   = implode('-', $tableau); // Transforme un tableau en chaine de caract�res s�par� par un tiret

        $title .= time();
        $title = md5($title);

        return $title;
    }

    /**
     * @return string
     */
    public function getCloneLink()
    {
        $ret = '<a href="' . SMARTOBJECT_URL . 'admin/adsense.php?op=clone&adsenseid=' . $this->getVar('adsenseid') . '"><img src="' . SMARTOBJECT_IMAGES_ACTIONS_URL . 'editcopy.png" alt="' . _CO_SOBJECT_ADSENSE_CLONE . '" title="' . _CO_SOBJECT_ADSENSE_CLONE . '"></a>';

        return $ret;
    }
}
