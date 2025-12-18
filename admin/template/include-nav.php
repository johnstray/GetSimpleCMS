<?php

/**
 * Admin Template - Navigation Include
 *
 * @package GetSimpleCMS
 * @author John Stray <johnstray@getsimple.info>
 */

if (!defined('IN_GS')) {
    die('you cannot load this file directly.');
}

# Layout style flags
$GSSTYLE = getDef('GSSTYLE') ? GSSTYLE : '';
$GScontainer = in_array('wide', explode(',', $GSSTYLE)) ? 'container-fluid' : 'container';
$headerclass = getDef('GSHEADERCLASS', true) ? getDef('GSHEADERCLASS') : '';

$tabs = getDef('GSTABS', false, true);
// $tabs  = array_keys($sidemenudefinition); // debug all
$currentTab = get_filename_id();

// If current tab is not in GSTABS, set it's parent tab as current
if (in_array($currentTab, $tabs) === false) {
    if (isset($sidemenudefinition[$currentTab]) && empty($sidemenudefinition[$currentTab]) === false) {
        $currentTab = $sidemenudefinition[$currentTab];
    }
}

?><header class="page-header">
    <div class="secondary-nav">
        <div class="<?php echo $GScontainer; ?> d-flex">
            <nav class="nav flex-fill">
                <?php if (isDebug()) { ?>
                    <a class="nav-link" href="health-check.php#debuginfo"
                        title="<?php i18n('DEBUG_MODE') . ' - ' . i18n('ON'); ?>">
                        <?php echo getIcon('TAB_debugmode'); ?>
                        <span class="d-none d-md-inline"><?php i18n('DEBUG_MODE'); ?></span>
                    </a>
                <?php } ?>
                <?php if (isAlpha() || isBeta()) { ?>
                    <a class="nav-link" href="health-check.php"
                        title="<?php isAlpha() ? i18n('ALPHA') : i18n('BETA'); ?> - <?php i18n('WEB_HEALTH_CHECK'); ?>">
                        <?php echo getIcon('TAB_development'); ?>
                        <span class="d-none d-md-inline">
                            Development:
                            <?php echo isAlpha() ? ucwords(i18n_r('ALPHA')) : ucwords(i18n_r('BETA')); ?>
                        </span>
                    </a>
                <?php } ?>
                <?php if (allowVerCheck()) {
                    $verres = getVerCheck();
                    if (is_object($verres) && $verres->status == 0) {?>
                    <a class="nav-link" href="health-check.php" title="<?php i18n('UPG_NEEDED'); ?>">
                        <?php echo getIcon('TAB_update'); ?>
                        <span class="d-none d-md-inline"><?php i18n('UPG_NEEDED'); ?></span>
                    </a>
                    <?php }
                } ?>
            </nav>
            <nav class="nav flex-fill justify-content-end">
                <a class="nav-link" href="support.php" title="Link to Support information"
                    accesskey="<?php echo find_accesskey(i18n_r('TAB_SUPPORT'));?>">
                    <?php echo getIcon('TAB_support'); ?>
                    <span class="d-none d-md-inline"><?php i18n('TAB_SUPPORT'); ?></span>
                </a>
                <a class="nav-link" href="settings.php" title="Link to Configuration settings">
                    <?php echo getIcon('TAB_settings'); ?>
                    <span class="d-none d-md-inline"><?php i18n('TAB_SETTINGS'); ?></span>
                </a>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo getIcon('TAB_welcome'); ?>
                        <span class="d-none d-md-inline"> <?php echo $USR; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="profile.php">
                                <?php echo getIcon('TAB_welcome'); ?>
                                <?php i18n('USER_PROFILE'); ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="logout.php" title="<?php i18n('TAB_LOGOUT'); ?>"
                                accesskey="<?php echo find_accesskey(i18n_r('TAB_LOGOUT')); ?>">
                                <?php echo getIcon('TAB_logout'); ?>
                                <?php i18n('TAB_LOGOUT'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <nav class="navbar navbar-expand-md navbar-light p-0">
        <div class="<?php echo $GScontainer; ?>">
            <a class="navbar-brand" href="<?php echo $SITEURL; ?>" target="_blank">
                <?php echo cl($SITENAME); ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTabs" aria-controls="navbarTabs" aria-expanded="false"
                aria-label="<?php i18n('NAVBAR_TOGGLE'); ?>">
                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'>
                    <path stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/>
                </svg>
            </button>
            <div class="collapse navbar-collapse" id="navbarTabs">
                <ul class="navbar-nav me-auto">
                    <?php
                    if ($tabs) {
                        foreach ($tabs as $tab) {
                            $tab = trim($tab);
                            if (empty($tab)) {
                                continue;
                            }
                            $tabtitle = i18n_r('TAB_' . uppercase($tab));
                            $class = $tab == $currentTab ? 'active' : '';
                            $icon = "";
                            if (getDef('GSTABICONS', true)) {
                                $icon = getIcon('TAB_' . $tab) . " ";
                            }

                            echo '<li id="tab_' . $tab . '" class="nav-item">';
                            echo '<a class="nav-link ' . $class . '" href="' . $tab . '.php" title="' . $tabtitle .
                                '" accesskey="' . find_accesskey($tabtitle) . '">';
                            echo $icon . $tabtitle . '</a></li>';
                        }
                    }
                    /** @hook: nav-tab - Backend tabs (from plugins) after navigation tab list output */
                    exec_action('nav-tab');
                    ?>
                    <li id="nav_loaderimg" class="nav-item">
                        <img class="toggle" id="loader" src="template/images/ajax.gif" alt="" />
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <?php include 'toast-messages.php'; ?>
</div>

<div class="<?php echo $GScontainer; ?> py-5">
    <?php require 'template/error_checking.php'; ?>
    <div class="row row-gap-5">
