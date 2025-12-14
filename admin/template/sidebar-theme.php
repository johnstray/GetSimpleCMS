<?php

/**
 * Admin Template - Themes Sidebar
 *
 * @package GetSimpleCMS
 * @subpackage ThemeManagement
 * @author John Stray <john.stray@getsimple.info>
 */

if (defined('IN_GS') === false) {
    die('You cannot load this file directly.');
} ?>

<nav class="nav flex-column">
    <a id="sb_theme" class="nav-link<?php echo isPage('theme') ? ' active' : ''; ?>" href="theme.php"
        accesskey="<?php echo find_accesskey(i18n_r('SIDE_CHOOSE_THEME')); ?>">
        <?php i18n('SIDE_CHOOSE_THEME'); ?>
    </a>
    <a id="sb_themeedit" class="nav-link<?php echo isPage('theme-edit') ? ' active' : ''; ?>" href="theme-edit.php"
        accesskey="<?php echo find_accesskey(i18n_r('SIDE_EDIT_THEME')); ?>">
        <?php i18n('SIDE_EDIT_THEME'); ?>
    </a>
    <a id="sb_components" class="nav-link<?php echo isPage('components') ? ' active' : ''; ?>" href="components.php"
        accesskey="<?php echo find_accesskey(i18n_r('SIDE_COMPONENTS')); ?>">
        <?php i18n('SIDE_COMPONENTS'); ?>
    </a>
    <a id="sb_snippets" class="nav-link<?php echo isPage('snippets') ? ' active' : ''; ?>" href="snippets.php"
        accesskey="<?php echo find_accesskey(i18n_r('SIDE_SNIPPETS')); ?>">
        <?php i18n('SIDE_SNIPPETS'); ?>
    </a>
    <?php if (!getDef('GSNOSITEMAP')) { ?>
        <a id="sb_sitemap" class="nav-link<?php echo isPage('sitemap') ? ' active' : ''; ?>" href="sitemap.php"
            accesskey="<?php echo find_accesskey(i18n_r('SIDE_VIEW_SITEMAP')); ?>">
            <?php i18n('SIDE_VIEW_SITEMAP'); ?>
        </a>
    <?php } ?>
    <?php exec_action("theme-sidebar"); // @hook theme-sidebar sidebar list html output  ?>
</nav>

<p id="js_submit_line"></p>
