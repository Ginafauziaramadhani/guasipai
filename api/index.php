<?php

/**
 * Vercel Serverless Entry Point
 * Meneruskan request dari Vercel serverless function ke file public/index.php Laravel
 */

$storage = '/tmp/storage';
if (!is_dir($storage)) {
    mkdir($storage, 0777, true);
    mkdir($storage.'/framework/views', 0777, true);
    mkdir($storage.'/framework/cache', 0777, true);
    mkdir($storage.'/framework/sessions', 0777, true);
    mkdir($storage.'/logs', 0777, true);
}
$_ENV['APP_STORAGE'] = $storage;

require __DIR__ . '/../public/index.php';
