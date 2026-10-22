<?php

// Run with: php tests/security_paths.php
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);
});

$fixture = sys_get_temp_dir() . '/gs-paths-' . bin2hex(random_bytes(12));
if (!mkdir($fixture, 0700)) {
    throw new RuntimeException('Cannot create test directory.');
}

try {
    define('IN_GS', true);
    define('GSROOTPATH', $fixture . '/');
    define('GSDATAPATH', $fixture . '/data/');
    define('GSBACKUPSPATH', $fixture . '/backups/');
    define('GSDATAUPLOADPATH', GSDATAPATH . 'uploads/');
    define('GSTHUMBNAILPATH', GSDATAPATH . 'thumbs/');
    define('GSPLUGINPATH', $fixture . '/plugins/');
    define('GSLANGPATH', $fixture . '/lang/');
    define('GSDEBUGFILEIO', false);
    define('GSDOCHMOD', false);
    define('GSBAKFILEPREFIX', '.bak');
    define('GSBAKFILESUFFIX', '');

    foreach (array(
        'data/pages/sub', 'data/uploads', 'data/thumbs', 'data-other',
        'backups', 'backups-other', 'plugins/demo/lang', 'lang', 'outside/lang'
    ) as $directory) {
        mkdir($fixture . '/' . $directory, 0700, true);
    }

    $repo = dirname(__DIR__);
    require $repo . '/admin/inc/basic.php';
    require $repo . '/admin/inc/security_functions.php';
    require $repo . '/admin/inc/template_functions.php';

    $checks = 0;
    $check = function ($name, $expected, $operation) use (&$checks) {
        clearstatcache(true);
        ++$checks;
        $actual = $operation();

        if ($actual !== $expected) {
            throw new RuntimeException($name . ': got ' . var_export($actual, true));
        }
    };

    $page = GSDATAPATH . 'pages/example.xml';
    file_put_contents($page, 'current');
    file_put_contents($fixture . '/data-other/secret.xml', 'KEEP');

    $check('allowed root', true, fn() => path_is_safe(GSDATAPATH, GSDATAPATH));
    $check('exact mode', false, fn() => path_is_safe(dirname($page), GSDATAPATH, false));
    $check('existing file', true, fn() => filepath_is_safe($page, GSDATAPATH));
    $check('direct-file mode', false, fn() => filepath_is_safe($page, GSDATAPATH, false));
    $check('sibling prefix', false, fn() => path_is_safe($fixture . '/data-other', GSDATAPATH));
    $check('sibling file', false, fn() => filepath_is_safe($fixture . '/data-other/secret.xml', GSDATAPATH));
    $check('missing root', false, fn() => path_is_safe($page, $fixture . '/missing'));
    $check('both missing', false, fn() => path_is_safe($fixture . '/missing', $fixture . '/missing'));
    $check('missing target', false, fn() => filepath_is_safe($page . '.missing', GSDATAPATH));
    $check('new file', true, fn() => filepath_is_safe($page . '.new', GSDATAPATH, true, true));
    $check('missing parent', false, fn() => filepath_is_safe(GSDATAPATH . 'missing/file', GSDATAPATH, true, true));
    $check('file cannot be parent', false, fn() => filepath_is_safe($page . '/file', GSDATAPATH, true, true));
    $check('traversal escape', false, fn() => filepath_is_safe(GSDATAPATH . '../data-other/secret.xml', GSDATAPATH));
    $check('contained traversal', true, fn() => filepath_is_safe(GSDATAPATH . 'pages/sub/../example.xml', GSDATAPATH));
    $check('empty input', false, fn() => path_is_safe('', GSDATAPATH));
    $check('NUL input', false, fn() => path_is_safe($page . "\0", GSDATAPATH));
    $check('array input', false, fn() => path_is_safe(array(), GSDATAPATH));
    $check('new filename stream separator', false, fn() => filepath_is_safe($page . ':stream', GSDATAPATH, true, true));
    $check('new filename trailing separator', false, fn() => filepath_is_safe($page . '.new/', GSDATAPATH, true, true));

    if (DIRECTORY_SEPARATOR === '/') {
        $check('backslash in new filename', false, fn() => filepath_is_safe($page . '\\new', GSDATAPATH, true, true));
    }

    $filesystemRoot = realpath($fixture);
    while (dirname($filesystemRoot) !== $filesystemRoot) {
        $filesystemRoot = dirname($filesystemRoot);
    }

    $check('filesystem root', true, fn() => path_is_safe($page, $filesystemRoot));
    $check('safe recursive creation', true, fn() => create_safe_directory(GSDATAPATH . 'new/sub', GSDATAPATH));
    $check('escaped creation', false, fn() => create_safe_directory($fixture . '/data-other/new', GSDATAPATH));
    $check('upload root deletion', false, fn() => delete_upload_dir(''));

    mkdir(GSDATAUPLOADPATH . 'empty');
    mkdir(GSTHUMBNAILPATH . 'empty');
    file_put_contents(GSTHUMBNAILPATH . 'empty/thumbnail.old.png', 'cache');
    $check('cached folder deletion', true, fn() => delete_upload_dir('empty'));
    $check('cached folder removed', false, fn() => file_exists(GSTHUMBNAILPATH . 'empty'));

    mkdir(GSDATAUPLOADPATH . 'full');
    file_put_contents(GSDATAUPLOADPATH . 'full/keep.txt', 'KEEP');
    $check('nonempty upload retained', false, fn() => delete_upload_dir('full'));
    $check('root alias retained', false, fn() => delete_upload_dir('full/..'));

    $backup = GSBACKUPSPATH . 'pages/example.bak.xml';
    $check('backup mapping', $backup, fn() => getBackupFilePath($page));
    $check('backup creation', true, fn() => backup_datafile($page));
    $check('backup content', 'current', fn() => file_get_contents($backup));
    $check('invalid backup mapping', false, fn() => getBackupFilePath($fixture . '/data-other/secret.xml'));
    $check('invalid backup lookup', false, fn() => fileHasBackup($fixture . '/data-other/secret.xml'));

    file_put_contents($page, 'changed');
    $check('restore existing file', true, fn() => restore_datafile($page));
    $check('restored content', 'current', fn() => file_get_contents($page));
    $check('previous content preserved', 'changed', fn() => file_get_contents($backup));
    $check('outside restore destination', false, fn() => restore_backup($backup, $fixture . '/data-other/secret.xml'));

    file_put_contents($backup . '.tmp', 'BUSY');
    $check('occupied restore temporary file', false, fn() => restore_datafile($page));
    $check('occupied temporary preserved', 'BUSY', fn() => file_get_contents($backup . '.tmp'));
    unlink($backup . '.tmp');

    unlink($page);
    $check('restore deleted file', true, fn() => restore_datafile($page));
    $check('restored deleted content', 'changed', fn() => file_get_contents($page));

    foreach (array(
        GSLANGPATH . 'en_US.php',
        GSPLUGINPATH . 'demo/lang/en_US.php',
        $fixture . '/outside/lang/en_US.php'
    ) as $language) {
        file_put_contents($language, '<?php $i18n = array("LABEL" => "test");');
    }

    $merged = array();
    $check('core language', true, function () use (&$merged) {
        return i18n_merge_impl(null, 'en_US', $merged);
    });
    $check('core language content', 'test', fn() => $merged['LABEL']);
    $check('plugin language', true, function () use (&$merged) {
        return i18n_merge_impl('demo', 'en_US', $merged);
    });
    $check('plugin language content', 'test', fn() => $merged['demo/LABEL']);
    $check('plugin traversal', false, function () use (&$merged) {
        return i18n_merge_impl('../outside', 'en_US', $merged);
    });
    $check('language traversal', false, function () use (&$merged) {
        return i18n_merge_impl(null, '../outside/lang/en_US', $merged);
    });

    if (@symlink($fixture . '/data-other', GSDATAPATH . 'escape')) {
        symlink($fixture . '/missing', GSDATAPATH . 'dangling');
        $check('symlink directory escape', false, fn() => path_is_safe(GSDATAPATH . 'escape', GSDATAPATH));
        $check('symlink creation escape', false, fn() => create_safe_directory(GSDATAPATH . 'escape/new', GSDATAPATH));
        $check('dangling link', false, fn() => filepath_is_safe(GSDATAPATH . 'dangling', GSDATAPATH, true, true));

        symlink($fixture . '/outside', GSPLUGINPATH . 'escape');
        $check('plugin directory symlink escape', false, function () use (&$merged) {
            return i18n_merge_impl('escape', 'en_US', $merged);
        });

        file_put_contents(GSBACKUPSPATH . 'pages/stored.xml', 'stored');
        symlink(GSBACKUPSPATH . 'pages/stored.xml', $backup);
        $check('backup symlink not moved into data', false, fn() => restore_datafile($page));
        unlink($backup);

        symlink($fixture . '/data-other/secret.xml', $backup);
        $check('backup destination symlink escape', false, fn() => backup_datafile($page));
        $check('backup source symlink escape', false, fn() => restore_datafile($page));

        if (DIRECTORY_SEPARATOR === '/') {
            symlink($fixture . '/data-other/secret.xml', GSDATAPATH . 'pages/trailing.xml');
            file_put_contents(GSDATAPATH . 'pages/trailing.xml ', 'old');
            $check('untrimmed file contained', true, fn() => filepath_is_safe(GSDATAPATH . 'pages/trailing.xml ', GSDATAPATH));
            $check('exact write destination', true, fn() => save_file(GSDATAPATH . 'pages/trailing.xml ', 'new'));
            $check('untrimmed filename written', 'new', fn() => file_get_contents(GSDATAPATH . 'pages/trailing.xml '));
        }

        $check('outside content preserved', 'KEEP', fn() => file_get_contents($fixture . '/data-other/secret.xml'));
    } else {
        echo "SKIP: symlink checks unavailable on this system.\n";
    }

    echo "$checks checks passed.\n";
} finally {
    $entries = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($fixture, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($entries as $entry) {
        if ($entry->isLink() || !$entry->isDir()) {
            unlink($entry->getPathname());
        } else {
            rmdir($entry->getPathname());
        }
    }

    rmdir($fixture);
    restore_error_handler();
}
