<?php

/**
 * Toast Messages
 *
 * Display toast messages throughout the admin interface
 *
 * @package GetSimple
 * @subpackage ToastMessages
 * @author John Stray <john.stray@getsimple.info>
 * @since 3.5.0
 *
 */

/**
 * Display a toast message
 *
 * @param  string  $message - The message content
 * @param  string  $title - The message title
 * @param  string  $type - The message type: 'info', 'success', 'warning', 'danger'
 * @param  boolean $persistent - Whether the toast should persist until dismissed
 * @param  array   $buttons - An array of buttons to include in the toast
 * @return void
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function doToastMessage($message = '', $title = '', $type = 'info', $persistent = false, $buttons = [])
{
    if (empty($message) || empty($title)) {
        return;
    }
    $toast_id = 'toast' . uniqid();

    // Put the toast message into the debug log
    debugLog(
        'Toast ['
        . htmlspecialchars($type, ENT_QUOTES) . ']: '
        . htmlspecialchars($title, ENT_QUOTES) . ' - '
        . htmlspecialchars($message, ENT_QUOTES)
    );
    ?>
    <div id="<?php echo $toast_id; ?>"
        class="toast border-0" style="margin-right:var(--bs-toast-spacing);"
        role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-autohide="<?php echo $persistent ? 'false' : 'true'; ?>">

        <div class="toast-header text-bg-<?php echo htmlspecialchars($type, ENT_QUOTES); ?>">
            <?php if (!empty($title)) { ?>
                <strong class="me-auto"><?php echo htmlspecialchars($title, ENT_QUOTES); ?></strong>
            <?php } ?>
            <?php if ($persistent) { ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            <?php } ?>
        </div>
        <div class="toast-body text-dark text-bg-<?php echo htmlspecialchars($type, ENT_QUOTES); ?>"
            style="--bs-bg-opacity:0.15;">
            <div><?php echo $message; ?></div>
            <?php if (!empty($buttons) && is_array($buttons)) { ?>
                <div class="border-top border-<?php echo htmlspecialchars($type, ENT_QUOTES); ?>-subtle"
                    style="padding-top:var(--bs-toast-padding-x);margin-top:calc(var(--bs-toast-padding-x) - 0.25rem);">
                    <?php foreach ($buttons as $button) {
                        $btn_text = isset($button['text']) ? $button['text'] : 'Button';
                        $btn_class = isset($button['class']) ? $button['class'] : 'btn-outline-'
                            . htmlspecialchars($type, ENT_QUOTES);
                        $btn_href = isset($button['href']) ? $button['href'] : '#';
                        ?>
                        <a href="<?php echo htmlspecialchars($btn_href, ENT_QUOTES); ?>"
                            class="btn btn-sm w-25 me-2 <?php echo htmlspecialchars($btn_class, ENT_QUOTES); ?>"
                            style="--bs-btn-padding-y:0;">
                            <?php echo htmlspecialchars($btn_text, ENT_QUOTES); ?>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastEl = document.getElementById('<?php echo $toast_id; ?>');
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        });
    </script>
<?php }
