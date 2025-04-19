<?php

/**
 * Admin Template - Footer Include
 *
 * @package GetSimpleCMS
 * @author John Stray <johnstray@getsimple.info>
 */

if (defined('IN_GS') === false) {
    die('you cannot load this file directly.');
}

# Layout style flags
$GSSTYLE = getDef('GSSTYLE') ? GSSTYLE : '';
$GScontainer = in_array('wide', explode(',', $GSSTYLE)) ? 'container-fluid' : 'container';

?>

            </div><!-- .row.row-gap-5 -->
        </div><!-- .$GScontainer -->

        <footer id="page-footer" class="<?php echo $GScontainer; ?>">

            <?php require GSADMININCPATH . "configuration.php"; ?>

            <div class="p-3 border-top">
                <div class="row small">
                    <div class="col-10">
                        <?php if (cookie_check()) { ?>
                            <nav id="footer-nav" class="nav small">
                                <a href="pages.php"><?php i18n('PAGE_MANAGEMENT'); ?></a> &nbsp;&bull;&nbsp;
                                <a href="upload.php"><?php i18n('FILE_MANAGEMENT'); ?></a> &nbsp;&bull;&nbsp;
                                <a href="theme.php"><?php i18n('THEME_MANAGEMENT'); ?></a> &nbsp;&bull;&nbsp;
                                <a href="backups.php"><?php i18n('BAK_MANAGEMENT'); ?></a> &nbsp;&bull;&nbsp;
                                <a href="plugins.php"><?php i18n('PLUGINS_MANAGEMENT'); ?></a> &nbsp;&bull;&nbsp;
                                <a href="settings.php"><?php i18n('GENERAL_SETTINGS'); ?></a> &nbsp;&bull;&nbsp;
                                <a href="support.php"><?php i18n('SUPPORT'); ?></a> &nbsp;&bull;&nbsp;
                                <a href="share.php?term=<?php i18n('SHARE'); ?>" rel="fancybox_s">
                                    <?php i18n('SHARE'); ?>
                                </a>
                            </nav>
                        <?php }
                        // draw sidebar items if no sidebar
                        $menuitems = getDef('GSNOSIDEBAR', false, true);
                        $current   = get_filename_id();
                        if (in_array($current, $menuitems)) {
                            global $sidemenudefinition,$tabdefinition,$sidemenutitles; // global?
                            if (isset($sidemenudefinition[$current])) {
                                $tab = $sidemenudefinition[$current];
                                if (empty($tab)) {
                                    $tab = $current;
                                }
                                if (isset($tabdefinition[$tab])) {
                                    echo '<nav id="footer-subnav" class="nav small">';
                                    foreach ($tabdefinition[$tab] as $item) {
                                        echo '<a class="nav-link" href="' . $item . '.php">';
                                        echo strip_tags(i18n_r($sidemenutitles[$item]));
                                        echo "</a>";
                                    }
                                    echo "</nav>";
                                }
                            }
                        } ?>
                        <?php if (isAuthPage() === false) { ?>
                            <p class="mb-0">
                                &copy; 2009-<?php echo date('Y'); ?>
                                <a href="https://getsimple.info/" target="_blank" >GetSimple CMS</a>
                                <?php echo '&ndash;' . i18n_r('VERSION') . ' ' . $site_version_no;  ?>
                            </p>
                        <?php } ?>
                    </div>
                    <div class="col-2 text-end">
                        <img src="template/images/getsimple_logo.gif" alt="GetSimple CMS Logo" />
                    </div>
                </div>
            </div>
        </footer>

        <!-- Backend Scripts -->
        <?php get_scripts_backend(); ?>
        <?php //exec_action('footer-pre'); /** @hook footer-pre - Internal use only! */ ?>

        <!-- Footer Hook -->
        <?php exec_action('footer'); /** @hook footer - Footer hook */ ?>

        <!-- Debug Log appears here when enabled -->
        <?php if (isAuthPage() === false && isDebug()) { ?>
            <div class="<?php echo $GScontainer; ?> debug-log my-5 p-3 border rounded bg-light">
                <div class="row">
                    <div class="col-12">
                        <?php outputDebugLog(); ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php exec_action('footer-body-end'); /** @hook footer-body-end - Before HTML body closing */ ?>
    </body>
</html>
<?php exec_action('footer-end'); /** @hook: footer-end the end before php flushes its output */ ?>
