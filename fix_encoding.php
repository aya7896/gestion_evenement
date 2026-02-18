<?php

// Correcteur d'encodage UTF-8
$replacements = array(
    hex2bin('c383c2a9') => 'é',
    hex2bin('c383c2a8') => 'è',
    hex2bin('c383c2a0') => 'à',
    hex2bin('c383c2a7') => 'ç',
    hex2bin('c383c2ae') => 'î',
    hex2bin('c383c2aa') => 'ê',
    hex2bin('c383c2bb') => 'û',
    hex2bin('c383c2b4') => 'ô',
    hex2bin('c383c2b9') => 'ù',
    hex2bin('c383892087') => 'É',
    hex2bin('c38389') => 'È',
    hex2bin('c383a9') => 'é',
);

$dirs = array('resources/views', 'app/Http/Controllers', 'app/Models');
$fixed = 0;

foreach ($dirs as $dir) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($files as $file) {
        if ($file->isFile() && in_array($file->getExtension(), array('php', 'blade'))) {
            $path = $file->getRealPath();
            $content = file_get_contents($path);
            $newContent = $content;
            
            foreach ($replacements as $bad => $good) {
                $newContent = str_replace($bad, $good, $newContent);
            }
            
            if ($newContent !== $content) {
                file_put_contents($path, $newContent);
                $fixed++;
                echo "OK: " . basename($path) . "\n";
            }
        }
    }
}

echo "\nFixed: " . $fixed . " files\n";
