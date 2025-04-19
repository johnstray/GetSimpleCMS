<?php

/**
 * Admin Template - Header
 *
 * @package GetSimpleCMS
 * @author John Stray <johnstray@getsimple.info>
 */

if (!defined('IN_GS')) {
    die('you cannot load this file directly.');
}

# We need some data for the Globals
global $SITENAME, $SITEURL, $GSADMIN, $themeslector, $pagetitle, $SESSIONHASH, $SAFEMODE;

# Layout style flags
$GSSTYLE = getDef('GSSTYLE') ? GSSTYLE : '';
$GScontainer = in_array('wide', explode(',', $GSSTYLE)) ? 'container-fluid' : 'container';

if (isPage('index') === false) {
    // @hook admin-pre-header - Backend before header output
    exec_action('admin-pre-header');
}

if (isset($pagetitle) === false || empty($pagetitle)) {
    $pagetitle = i18n_r(get_filename_id() . '_title');
}
$title = $pagetitle . ' &middot; ' . cl($SITENAME);

// JavaScript i18n tokens
$jsi18nkeys = array(
    'ERROR',
    'ERROR_OCCURED',
    'EXPAND_TOP',
    'COLLAPSE_TOP',
    'FILE_EXISTS_PROMPT',
    'CANCELLED',
    'UNSAVED_INFORMATION',
    'UNSAVED_PROMPT',
    'CANNOT_SAVE_EMPTY',
    'COMPONENT_DELETED',
    'CANNOT_SAVE_EMPTY',
    'PAGE_UNSAVED',
    'MINIMIZENOTIFY',
    'SELECT_FILE'
);

// i18n for JavaScript
$jsi18n = array_combine($jsi18nkeys, array_map('i18n_r', $jsi18nkeys));

// load gscodeeditor
if (!getDef('GSNOHIGHLIGHT', true) || getDef('GSNOHIGHLIGHT') != true) {
    queue_script('gscodeeditor', GSBACK);
}

if ((isPage('snippets') || isPage('edit') || isPage('backup-edit')) && getGSVar('HTMLEDITOR')) {
    queue_script('gshtmleditor', GSBACK);
}

// load gsuploader
if ((isPage('upload') || isPage('filebrowser') || isPage('image')) && (getDef('GSUSEGSUPLOADER', true))) {
    queue_script('gsuploader', GSBACK);
}

// load gscrop image editor
if (isPage('image')) {
    queue_script('gscrop', GSBACK);
    queue_style('gscrop', GSBACK);
}

// HTMLEDITOR INIT
// ckeditor contentsCss(editor.css) from theme
if (file_exists(GSTHEMESPATH . getGSVar('TEMPLATE') . "/editor.css")) {
    $CKEcontentsCss = $SITEURL . getRelPath(GSTHEMESPATH) . getGSVar('TEMPLATE') . '/editor.css';
}
// ckeditor contentsCss(contents.css) override from user
if (file_exists(GSTHEMESPATH . getDef('GSEDITORCSSFILE'))) {
    $CKEcontentsCss = $SITEURL . getRelPath(GSTHEMESPATH) . getDef('GSEDITORCSSFILE');
}
// ckeditor customconfig
if (file_exists(GSTHEMESPATH . getDef('GSEDITORCONFIGFILE'))) {
    $CKEconfigjs =  $SITEURL . getRelPath(GSTHEMESPATH) . getDef('GSEDITORCONFIGFILE');
}
// ckeditor stylesheet
if (file_exists(GSTHEMESPATH . getDef('GSEDITORSTYLESFILE'))) {
    $CKEstyleSet = getDef('GSEDITORSTYLESID') . ":" . $SITEURL .
        getRelPath(GSTHEMESPATH) . getDef('GSEDITORSTYLESFILE');
}

?><!DOCTYPE html><!-- HTML5 : XHTML Compantible -->
<html lang="<?php echo get_site_lang(true); ?>">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?></title>
        <?php if (isAuthPage() === false) { ?>
            <meta name="generator" content="GetSimple CMS - <?php echo GSVERSION; ?>" />
            <link rel="shortcut icon" href="favicon.png" />
            <link rel="apple-touch-icon" href="apple-touch-icon.png" />
        <?php } ?>
        <meta name="robots" content="noindex, nofollow" />

        <script type="text/javascript">
            /** @todo: Clean this up, use a better bridge to initialize config variables in JS */

            // Init GS namespace and i18n
            var GS = GS || {};
            GS.i18n = <?php echo json_encode($jsi18n); ?>;
            GS.debug = <?php echo (defined('GSDEBUG') && GSDEBUG) ? 'true' : 'false'; ?>;
            GS.siteurl = '<?php echo $SITEURL; ?>';
            GS.uploads = '<?php echo tsl($SITEURL) . getRelPath(GSDATAUPLOADPATH); ?>)';

            // GS tree defaults for page tree
            GS.treeminrow = 10;
            GS.treemindepth = 2;
            GS.treeminheader = 2;

            var uploadSession = '<?php echo $SESSIONHASH; ?>';
            /** @todo: Clean the $_GET['path'] variable below before using it */
            var uploadPath = '<?php echo isset($_GET['path']) ? $_GET['path'] : ''; ?>';

            <?php
            if (isset($_COOKIE['gs_editor_theme'])) {
                $editorTheme = var_out($_COOKIE['gs_editor_theme']);
                echo "            // CodeMirror editor theme\n";
                echo "            var editorTheme = '" . $editorTheme . "';\n";
            }

            if (isPage('edit') && isAutoSave()) {
                $autosaveintvl = getdef('GSAUTOSAVEINTERVAL');
                echo "            // Autosave interval\n";
                echo "            var autosaveintvl = " . (!is_int($autosaveintvl) ? 10 : $autosaveintvl) . ";\n";
            } else {
                echo "            // Autosave interval\n";
                echo "            var autosaveintvl = false;\n";
            }
            ?>

            //CKEditor config object shim for config
            if (typeof CKEDITOR === 'undefined') {
                CKEDITOR = {};
                CKEDITOR.SHIM = true;
                CKEDITOR.ENTER_P = 1;
                CKEDITOR.ENTER_BR = 2;
                CKEDITOR.ENTER_DIV = 3;
            }

            var htmlEditorConfig = {
                language : '<?php echo getGSVar('EDLANG'); ?>',
                contentsCss : '<?php echo isset($CKEcontentsCss) ? $CKEcontentsCss : ''; ?>',
                customConfig : '<?php echo isset($CKEconfigjs) ? $CKEconfigjs : ''; ?>',
                stylesSet : '<?php echo isset($CKEstyleSet) ? $CKEstyleSet : ''; ?>',
                height : '<?php echo isPage('snippets') ? '130px' : getGSVar('EDHEIGHT'); ?>',
                baseHref : '<?php echo getGSVar('SITEURL'); ?>',
                timestamp : '<?php echo getDef('GSCKETSTAMP', true) ? getDef('GSCKETSTAMP') : ''; ?>',
                toolbar : <?php echo getGSVar('EDTOOL') ? returnJsArray(getGSVar('EDTOOL')) : "[]"; ?>
            };

            // Wipe the CKEditor shim, so it does not interfere with the real one
            if (typeof CKEDITOR !== 'undefined') {
                if (CKEDITOR.SHIM == true) {
                    CKEDITOR = null;
                }
            }
        </script>

        <?php get_scripts_backend(); // Load backend scripts after globals set ?>

        <!-- During Development only, will be moved to styles.php -->
        <link rel="stylesheet" type="text/css" href="template/styles/main.css" />
        <!--<link rel="stylesheet" type="text/css"
            href="template/style.php?<?php echo 's=' . $GSSTYLE . '&v=' . GSVERSION; ?>" media="screen" />-->

        <script type="text/javascript">
            jQuery(document).ready(function () {
                // Disable page editing while in safemode
                $('body#edit.safemode :input').attr('disabled', 'disabled');
            })
        </script>

        <?php
        // Plugin hook to allow insertion of stuff into the header
        if (isAuthPage() === false) {
            // @hook header - Backend header output before header closes
            exec_action('header');
        }
        ?>
    </head>
    <body <?php filename_id(); ?>>
