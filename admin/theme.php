<?php

// phpcs:disable Generic.Files.LineLength.TooLong

/**
 * Themes
 *
 * Allows selection of site theme
 *
 * @package GetSimple
 * @subpackage Theme
 */

# setup inclusions
$load['plugin'] = true;
include('inc/common.php');
login_cookie_check();

exec_action('load-theme');

# was the form submitted?
if ((isset($_POST['submitted'])) && (isset($_POST['template']))) {
    check_for_csrf("activate");

    # get passed value from form
    $newTemplate = var_in($_POST['template']);

    if (!path_is_safe(GSTHEMESPATH . $newTemplate, GSTHEMESPATH)) {
        die();
    }

    # backup old GSWEBSITEFILE (website.xml) file
    backup_datafile(GSDATAOTHERPATH . GSWEBSITEFILE);

    # udpate GSWEBSITEFILE (website.xml) file with new theme
    $xml = getXML(GSDATAOTHERPATH . GSWEBSITEFILE);
    $xml->editAddCData('TEMPLATE', $newTemplate);
    $status = XMLsave($xml, GSDATAOTHERPATH . GSWEBSITEFILE);

    $success = i18n_r('THEME_CHANGED');

    $TEMPLATE = $newTemplate; // set new global
}

# get available themes, using folder match required contain template.php
$themes = getDirs(GSTHEMESPATH, GSTEMPLATEFILE);
$theme_options = '';

foreach ($themes as $theme) {
    $sel = $TEMPLATE == $theme ? 'selected' : '';
    $themename = $TEMPLATE == $theme ? $theme . ' (' . i18n_r('ACTIVE') . ')' : $theme;
    $theme_options .= '<option ' . $sel . ' value="' . $theme . '" >' . $themename . '</option>';
}

$pagetitle = i18n_r('THEME_MANAGEMENT');
get_template('header');

?>

<?php include('template/include-nav.php'); ?>

    <main id="maincontent" class="col-lg-9">
        <div class="main">
            <h3 class="floated"><?php i18n('CHOOSE_THEME');?></h3>
            <div class="edit-nav clearfix" >
                <?php exec_action(get_filename_id() . '-edit-nav'); ?>
            </div>

            <?php exec_action(get_filename_id() . '-body'); ?>

            <form action="<?php myself(); ?>" method="post" accept-charset="utf-8" >
                <input id="nonce" name="nonce" type="hidden" value="<?php echo get_nonce("activate"); ?>" />
                <div class="row g-3 align-items-center border-bottom pb-3 mb-5">
                    <div class="col-auto">
                        <label for="theme_select" class="col-form-label"><?php i18n('THEME_MANAGEMENT'); ?></label>
                    </div>
                    <div class="col">
                        <select id="theme_select" class="form-select text"  name="template" ><?php echo $theme_options; ?></select>
                    </div>
                    <div class="col-auto">
                        &nbsp;&nbsp;&nbsp;<input class="btn btn-default-outline submit" type="submit" name="submitted" value="<?php i18n('ACTIVATE_THEME'); ?>" />
                    </div>
                </div>
            </form>

            <section id="theme-details" class="row py-5 row-cols-1 row-cols-md-2 g-4 align-items-center">
                <div class="col text-center">
                    <div class="theme-preview w-75">
                        <?php if (file_exists(GSTHEMESPATH . $TEMPLATE . '/images/screenshot.png')) { ?>
                            <?php $theme_path = str_replace(GSROOTPATH, '', GSTHEMESPATH); ?>
                            <img id="theme_preview" class="w-100" src="../<?php echo $theme_path . $TEMPLATE; ?>/images/screenshot.png" alt="<?php i18n('THEME_SCREENSHOT'); ?>" />
                        <?php } else { ?>
                            <div id="theme_no_img" class="border p-5">
                                <p><em><?php i18n_r('NO_THEME_SCREENSHOT'); ?></em></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col">
                    <h4>
                        <?php echo $TEMPLATE; ?> Theme
                        <small style="color:var(--primary-light);float:right;">v1.0.0</small>
                    </h4>
                    <p>
                        <strong>Author:</strong> Theme Author Name<br />
                        <strong>Website:</strong> <a href="https://example.com" target="_blank">https://example.com</a><br />
                    </p>
                    <p>
                        This is a placeholder description for the <?php echo $TEMPLATE; ?> theme. Theme authors can provide information about the design, features, and intended use cases of their themes here.
                    </p>
                </div>
            </section>

            <!--<section id="theme-config" class="mt-5">
                <h5>Theme Configuration</h5>
                <p>
                    Instructions for using and customizing the <?php echo $TEMPLATE; ?> theme will be provided here.
                    This may include details on available templates, customization options, and any special features
                    included with the theme.
                </p>
                <div class="row row-cols-1 row-cols-lg-2">
                    <div class="col">
                        <label for="exampleSetting" class="form-label">Example Setting</label>
                        <p class="hint">This is an example setting for the theme. Adjust as needed.</p>
                        <input type="text" class="form-control" id="exampleSetting" placeholder="Enter value">
                    </div>
                    <div class="col">
                        <label for="anotherSetting" class="form-label">Another Setting</label>
                        <p class="hint">This is an example setting for the theme. Adjust as needed.</p>
                        <select class="form-select" id="anotherSetting">
                            <option selected>Choose...</option>
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                        </select>
                    </div>
                </div>
            </section>-->

            <?php exec_action('theme-extras'); //@hook theme-extras after theme html output ?>

        </div>

    </main>

    <aside id="sidebar" class="col-lg-3">
        <?php include('template/sidebar-theme.php'); ?>
    </aside>

<?php get_template('footer'); ?>
