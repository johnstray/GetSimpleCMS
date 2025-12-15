<?php

/**
 * Sidebar Backups Template
 *
 * @package GetSimple
 * @subpackage Backups
 */

if (defined('IN_GS') === false) {
    die('you cannot load this file directly.');
} ?>

<nav class="nav flex-column">
    <a id="sb_backups" class="nav-link<?php echo isPage('backups') ? ' active' : ''; ?>"
        href="backups.php" accesskey="<?php echo find_accesskey(i18n_r('SIDE_PAGE_BAK'));?>">
        <?php i18n('SIDE_PAGE_BAK'); ?>
    </a>
    <?php if (isPage('backup-edit')) { ?>
        <a id="sb_viewbackup" class="nav-link<?php echo isPage('backup-edit') ? ' active' : ''; ?>"
            href="#" accesskey="<?php echo find_accesskey(i18n_r('SIDE_VIEW_BAK'));?>">
            <?php i18n('SIDE_VIEW_BAK'); ?>
        </a>
    <?php } ?>
    <a id="sb_archives" class="nav-link<?php echo isPage('archive') ? ' active' : ''; ?>"
        href="archive.php" accesskey="<?php echo find_accesskey(i18n_r('SIDE_WEB_ARCHIVES'));?>">
        <?php i18n('SIDE_WEB_ARCHIVES'); ?>
    </a>
    <?php exec_action("backups-sidebar"); // @hook backups-sidebar sidebar list html output ?>
</nav>

<p id="js_submit_line" ></p>
