<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
/**
 * Vercel Serverless Entry Point
 * Meneruskan request dari Vercel serverless function ke file public/index.php Laravel
 */

$storage = '/tmp/storage';
if (!is_dir($storage)) mkdir($storage, 0777, true);
if (!is_dir($storage.'/framework')) mkdir($storage.'/framework', 0777, true);
if (!is_dir($storage.'/framework/views')) mkdir($storage.'/framework/views', 0777, true);
if (!is_dir($storage.'/framework/cache')) mkdir($storage.'/framework/cache', 0777, true);
if (!is_dir($storage.'/framework/sessions')) mkdir($storage.'/framework/sessions', 0777, true);
if (!is_dir($storage.'/logs')) mkdir($storage.'/logs', 0777, true);
$_ENV['APP_STORAGE'] = $storage;
$_SERVER['APP_STORAGE'] = $storage;

require __DIR__ . '/../public/index.php';
