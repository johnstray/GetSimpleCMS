<?php

// phpcs:disable Generic.Files.LineLength.TooLong

/**
 * Admin Page - Pages Management
 * Displays all pages
 *
 * @package GetSimple
 * @subpackage Page-Edit
 */

// Setup inclusions
$load['plugin'] = true;

// Include common.php
include('inc/common.php');
login_cookie_check();

exec_action('load-pages');

// Variable settings

// inputs for error_checking
$id      = isset($_GET['id']) ? var_in($_GET['id']) : null;
$ptype   = isset($_GET['type']) ? var_in($_GET['type']) : null;

$path    = GSDATAPAGESPATH;
$counter = '0';
$table   = '';

// cloning a page
if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'clone') {
    check_for_csrf("clone", "pages.php");

    $status = clone_page($_GET['id']);
    if ($status !== false) {
        exec_action('page-clone'); // @hook page-clone page was cloned
        redirect('pages.php?upd=clone-success&id=' . $status);
    } else {
        $error = sprintf(i18n_r('CLONE_ERROR'), var_out($_GET['id']));
        redirect('pages.php?error=' . $error);
    }
}

init_pageCache(true, false); // force rebuild of pagecache (refresh,force)
// getPagesXmlValues(true);
// $pagesSorted = sortCustomIndexCallback($pagesArray,'title','prepare_menuOrderParentTitle');
$pagesSorted = sortCustomIndexCallback($pagesArray, 'title');
// debugLog($pagesSorted);
$count       = count($pagesSorted);
$table       = get_pages_menu('', '', 0);
$pagetitle   = i18n_r('PAGE_MANAGEMENT');

get_template('header');

?>

<?php include('template/include-nav.php'); ?>

    <main id="maincontent" class="col-lg-9">
        <?php exec_action('pages-main'); // @hook pages-main before pages main html output ?>
        <div class="main">
            <h3 class="floated"><?php i18n('PAGE_MANAGEMENT'); ?></h3>
            <div class="edit-nav" >
                <a href="#filterSearch" accesskey="<?php echo find_accesskey(i18n_r('FILTER'));?>" role="button"
                    data-bs-toggle="collapse" aria-expanded="false" aria-controls="filterSearch">
                    <i class="fa fa-fw fa-filter"></i>
                    <?php i18n('FILTER'); ?>
                </a>
                <a href="javascript:void(0)" id="show-characters" accesskey="<?php echo find_accesskey(i18n_r('TOGGLE_STATUS'));?>" >
                    <!-- Insert ICON here -->
                    <?php i18n('TOGGLE_STATUS'); ?>
                </a>
                <?php exec_action(get_filename_id() . '-edit-nav'); ?>
            </div>
            <!--<div id="filter-search">
                <form><input type="text" autocomplete="off" class="text" id="q" placeholder="<?php echo strip_tags(lowercase(i18n_r('FILTER'))); ?>..." /> &nbsp; <a href="pages.php" class="cancel"><?php i18n('CANCEL'); ?></a></form>
            </div>-->
            <div class="collapse" id="filterSearch">
                <div class="card card-body mb-3">
                    <form>
                        <div class="row g-3 align-items-center justify-content-center">
                            <label for="filter" class="col-auto col-form-label">Filter Pages</label>
                            <div class="col-auto">
                                <input type="text" class="form-control form-control-sm" id="q" name="q" autocomplete="off" placeholder="<?php echo strip_tags(lowercase(i18n_r('FILTER'))); ?>...">
                            </div>
                            <div class="col-auto">
                                <a href="pages.php" class="cancel"><?php i18n('CANCEL'); ?></a>
                                <!--<button type="submit" class="btn btn-sm btn-primary">Filter Pages</button>-->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php exec_action(get_filename_id() . '-body'); ?>
            <table id="editpages" class="table table-striped table-hover tree">
                <thead>
                    <tr>
                        <th scope="col"><?php i18n('PAGE_TITLE'); ?></th>
                        <th scope="col"><?php i18n('DATE'); ?></th>
                        <th></th><th></th>
                    </tr>
                </thead>
                <?php echo $table; ?>
            </table>
            <p class="small text-center mb-0"><em><b><span id="pg_counter"><?php echo $count; ?></span></b> <?php i18n('TOTAL_PAGES'); ?></em></p>

        </div>
    </main><!-- end maincontent -->


    <aside id="sidebar" class="col-lg-3">
        <?php include('template/sidebar-pages.php'); ?>
    </aside>

<?php get_template('footer'); ?>
