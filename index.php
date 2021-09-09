<?php

include __DIR__ . '/vendor/autoload.php';

$path = 'en.001.txt';
$content = file_get_contents($path);

$process = new \Giahao9899\PdfLazyMerge\LazyMerge();
$new_content = $process->merge($content);

file_put_contents('new.en.001.txt', $new_content);