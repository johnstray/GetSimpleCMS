<?php

/**
 * Sidebar Plugins Template
 *
 * @package GetSimple
 * @subpackage Plugins
 */

if (defined('IN_GS') === false) {
    die('You cannot load this file directly!');
} ?>

<nav class="nav flex-column">
    <a id="sb_plugins" class="nav-link<?php echo isPage('plugins') ? ' active' : ''; ?>"
        href="plugins.php" accesskey="<?php echo find_accesskey(i18n_r('SHOW_PLUGINS'));?>" >
        <?php i18n('SHOW_PLUGINS'); ?>
    </a>
    <a id="sb_extend" class="nav-link" href="<?php echo $site_link_back_url; ?>extend/" target="_blank"
        accesskey="<?php echo find_accesskey(i18n_r('GET_PLUGINS_LINK'));?>" >
        <?php i18n('GET_PLUGINS_LINK'); ?>
    </a>

    <?php exec_action('plugins-sidebar'); // @hook sidebar-navigation additional sidebar navigation links ?>
</nav>

<p id="js_submit_line" ></p>
