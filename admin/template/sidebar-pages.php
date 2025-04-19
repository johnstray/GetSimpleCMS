<?php

/**
 * Admin Template - Pages Sidebar
 *
 * @package GetSimpleCMS
 * @subpackage PageManagement
 * @author John Stray <johnstray@getsimple.info>
 */

if (defined('IN_GS') === false) {
    die('you cannot load this file directly.');
} ?>

<nav class="nav flex-column">
    <a id="sb_pages" class="nav-link<?php echo isPage('pages') ? ' active' : ''; ?>"
        href="pages.php" accesskey="<?php echo find_accesskey(i18n_r('SIDE_VIEW_PAGES'));?>">
        <?php i18n('SIDE_VIEW_PAGES'); ?>
    </a>
    <a id="sb_newpage" class="nav-link<?php echo isPage('edit') && !isset($_GET['id']) ? ' active' : ''; ?>"
        href="edit.php" accesskey="<?php echo find_accesskey(i18n_r('SIDE_CREATE_NEW'));?>">
        <?php i18n('SIDE_CREATE_NEW'); ?>
    </a>
    <?php if (isset($_GET['id']) && $_GET['id'] != '' && isPage('edit')) { ?>
        <a id="sb_pageedit" class="nav-link active"
            href="#" accesskey="<?php echo find_accesskey(i18n_r('EDITPAGE_TITLE'));?>">
            <?php i18n('EDITPAGE_TITLE'); ?>
        </a>
    <?php } ?>
    <a id="sb_menumanager" class="nav-link<?php echo isPage('menu-manager') ? ' active' : ''; ?>"
        href="menu-manager.php" accesskey="<?php echo find_accesskey(i18n_r('MENU_MANAGER'));?>">
        <?php i18n('MENU_MANAGER'); ?>
    </a>
    <?php exec_action("pages-sidebar"); /** @hook: pages-sidebar sidebar list html output */ ?>
</nav>

<div id="js_submit_line"></div>

<?php if (isPage('edit')) { ?>
    <?php if (getDef('GSAUTOSAVE', true)) { ?>
        <p id="autosavestatus"><?php echo sprintf(i18n_r("AUTOSAVE_STATUS"), (int) getDef('GSAUTOSAVEINTERVAL')); ?></p>
        <p id="autosavenotify"></p>
    <?php }
} ?>
