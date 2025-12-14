<?php

//phpcs:disable Generic.Files.LineLength.TooLong

/**
 * Components
 *
 * Displays and creates static components
 *
 * @package GetSimple
 * @subpackage Components
 * @link http://get-simple.info/docs/what-are-components
 */

# setup inclusions
$load['plugin'] = true;
include('inc/common.php');
login_cookie_check();

exec_action('load-components');

# variable settings
$update = $table = $list = '';
$asset  = GSDATAOTHERPATH . GSCOMPONENTSFILE;
$id     = "component";

# check to see if form was submitted
if (isset($_POST['submitted'])) {
    check_for_csrf("modify_" . $id);
    $error  = saveCollection($id, $asset);
    $update = empty($error) ? $id . '-success' : 'error';
    if (!$error) {
        get_components_xml(true);
    }
}

# if undo was invoked
if (isset($_GET['undo'])) {
    # perform the undo
    restore_datafile($asset);
    check_for_csrf("undo");
    if (!requestIsAjax()) {
        // redirect to prevent refresh undos
        redirect('components.php?upd=comp-restored');
    }
    // undos are not ajax, ??
    // $update = 'comp-restored';
    // get_components_xml(true);
}

# create components form html
$collectionData = get_components_xml();
$numitems       = $collectionData ? count((object)$collectionData) : 0;
$pagetitle      = i18n_r('COMPONENTS');
get_template('header');

include('template/include-nav.php'); ?>

    <main id="maincontent" class="col-lg-9">
        <div class="main">
            <h3 class="floated"><?php echo i18n_r('EDIT_COMPONENTS');?></h3>
            <div class="edit-nav clearfix" >
                <a href="javascript:void(0)" id="addcomponent" accesskey="<?php echo find_accesskey(i18n_r('ADD_COMPONENT')); ?>" ><?php i18n('ADD_COMPONENT'); ?></a>
                <?php exec_action(get_filename_id() . '-edit-nav'); ?>
            </div>
            <?php exec_action(get_filename_id() . '-body'); ?>
            <form id="compEditForm" class="manyinputs watch" action="<?php myself(); ?>" method="post" accept-charset="utf-8" >
                <input type="hidden" id="id" value="<?php echo $numitems; ?>" />
                <input type="hidden" id="nonce" name="nonce" value="<?php echo get_nonce("modify_component"); ?>" />

                <div id="divTxt"></div>
                <?php outputCollection('components', $collectionData, '', 'get_component'); ?>
                <p id="submit_line" class="<?php echo $numitems > 0 ? '' : ' hidden'; ?>" >
                    <span><input type="submit" class="btn btn-default-outline submit" name="submitted" id="button" value="<?php i18n('SAVE_COMPONENTS'); ?>" /></span>
                    &nbsp;&nbsp;<?php i18n('OR'); ?>&nbsp;&nbsp; <a class="cancel" href="components.php?cancel"><?php i18n('CANCEL'); ?></a>
                </p>
            </form>
            <div id="comptemplate" class="hidden"><?php echo getItemTemplate('components', ' noeditor', 'get_component'); ?></div>
        </div>
    </main>

    <aside id="sidebar" class="col-lg-3">
        <?php include('template/sidebar-theme.php'); ?>
        <?php outputCollectionTags('components', $collectionData); ?>
    </aside>

<?php get_template('footer'); ?>
