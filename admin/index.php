<?php

/**
 * Login
 *
 * Allows access to the GetSimple control panel
 *
 * @package GetSimple
 * @subpackage Login
 */

# Setup inclusions
$load['plugin'] = true;
$load['login']  = true;

// wrap all include and header output in output buffering to prevent sending before headers.
ob_start();

include('inc/common.php');
exec_action('load-login');
if (!getDef('GSALLOWLOGIN', true)) {
    redirect($SITEURL);
}
$pagetitle = i18n_r('LOGIN');
get_template('header');

ob_end_flush();

?>

</div>
</div>
<div class="wrapper">

    <main id="maincontent" class="col-4 my-5 mx-auto" role="main">
        <div class="main">
            <h3><?php echo cl($SITENAME); ?></h3>

            <?php
                exec_action('index-login'); //@hook index-login
                exec_action('login-main'); //@hook index-login
            ?>

            <form class="login entersubmit" method="post"
                action="<?php echo '?' . htmlentities($_SERVER['QUERY_STRING'], ENT_QUOTES); ?>">

                <?php include('template/error_checking.php'); ?>

                <div class="mb-3">
                    <?php if (isset($_COOKIE['rememberme'])) {
                        $saved_user = htmlspecialchars($_COOKIE['rememberme'], ENT_QUOTES);
                    } else {
                        $saved_user = isset($_POST['userid']) ? htmlspecialchars($_POST['userid'], ENT_QUOTES) : '';
                    }
                    $usr_validation_class = '';
                    if (
                        (isset($update) && strpos($update, 'login-req') !== false && empty($_POST['userid']))
                        || (isset($update) && strpos($update, 'login-fail') !== false)
                    ) {
                        $usr_validation_class = ' is-invalid';
                    } ?>
                    <label for="userid" class="form-label"><?php i18n('USERNAME'); ?></label>
                    <input type="text" id="userid" name="userid" value="<?php echo $saved_user; ?>"
                        class="form-control<?php echo $usr_validation_class; ?>" />
                    <?php if ($usr_validation_class) { ?>
                        <div class="invalid-feedback">
                            <?php
                            if (
                                isset($update) && strpos($update, 'login-req') !== false
                                && empty($_POST['userid'])
                            ) {
                                i18n('USERNAME_REQUIRED');
                            } elseif (isset($update) && strpos($update, 'login-fail') !== false) {
                                i18n('INVALID_USER');
                            } ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <?php $pwd_validation_class = '';
                    if (
                        (isset($update) && strpos($update, 'login-req') !== false && empty($_POST['pwd']))
                        || (isset($update) && strpos($update, 'login-fail') !== false)
                    ) {
                        $pwd_validation_class = ' is-invalid';
                    } ?>
                    <label for="pwd" class="form-label"><?php i18n('PASSWORD'); ?></label>
                    <input type="password" id="pwd" name="pwd"
                        class="form-control<?php echo $pwd_validation_class; ?>" />
                    <?php if ($pwd_validation_class) { ?>
                        <div class="invalid-feedback">
                            <?php
                            if (
                                isset($update) && strpos($update, 'login-req') !== false
                                && empty($_POST['pwd'])
                            ) {
                                i18n('PASSWORD_REQUIRED');
                            } elseif (isset($update) && strpos($update, 'login-fail') !== false) {
                                i18n('INVALID_PASSWORD');
                            } ?>
                        </div>
                    <?php } ?>
                </div>

                <?php
                    exec_action('login-extras'); // @hook login-extras
                ?>

                <div class="row row-cols-2 align-items-center mb-3">
                    <div class="col">
                        <div class="form-check" style="width:fit-content; margin:auto;">
                            <?php if (isset($_COOKIE['rememberme'])) {
                                $checked = ' checked="checked"';
                            } else {
                                $checked = '';
                            } ?>
                            <input type="checkbox" class="form-check-input" id="rememberme" name="rememberme"
                                value="1"<?php echo $checked; ?> />
                            <label class="form-check-label" for="rememberme"><?php i18n('REMEMBER_ME'); ?></label>
                        </div>
                    </div>
                    <div class="col text-center">
                        <input type="submit" name="submitted" class="btn btn-default-outline w-100 submit"
                            value="<?php i18n('LOGIN'); ?>" />
                    </div>
                </div>
            </form>
            <p class="cta mb-0 text-center">
                <a href="<?php echo $SITEURL; ?>"><?php i18n('BACK_TO_WEBSITE'); ?></a> &nbsp;
                <?php if (getDef('GSALLOWRESETTPASSWORD', true) !== false) { ?>
                    | &nbsp; <a href="resetpassword.php"><?php i18n('FORGOT_PWD'); ?></a>
                <?php } ?>
            </p>

            <div class="reqs"><?php exec_action('login-reqs'); // @hook login-reqs ?></div>
        </div>
    </main>

<?php get_template('footer'); ?>
