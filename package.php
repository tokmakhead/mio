<?php

$projectName = "MIONEX";
$version = "v1.0";
$zipName = "mionex-{$version}-dist.zip";
$tempDir = "export_temp";

function logMsg($msg)
{
    echo "=> $msg\n";
}

// 1. Check for assets
if (!is_dir('public/build')) {
    logMsg("Building assets with npm...");
    shell_exec("npm run build");
}

// 2. Clear temp dir
if (is_dir($tempDir)) {
    shell_exec("rmdir /s /q $tempDir");
}
mkdir($tempDir);

// 3. Export clean source using Git
logMsg("Exporting source using git archive...");
shell_exec("git archive --format=zip -o temp_archive.zip HEAD");

if (!file_exists('temp_archive.zip')) {
    die("Error: Could not create git archive.\n");
}

$zip = new ZipArchive;
if ($zip->open('temp_archive.zip') === TRUE) {
    $zip->extractTo($tempDir);
    $zip->close();
}
unlink('temp_archive.zip');

// 4. Force inject compiled assets (they are usually gitignored)
logMsg("Injecting compiled assets...");
function copyDir($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

if (is_dir('public/build')) {
    copyDir('public/build', "$tempDir/public/build");
}

// 5. Add README and placeholder .env
if (file_exists('README_INSTALL.txt')) {
    copy('README_INSTALL.txt', "$tempDir/README_INSTALL.txt");
}

// 6. Final ZIP creation
logMsg("Creating final distribution ZIP: $zipName");
$finalZip = new ZipArchive;
if ($finalZip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($tempDir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen(realpath($tempDir)) + 1);
            $finalZip->addFile($filePath, $relativePath);
        }
    }
    $finalZip->close();
}

// Cleanup
shell_exec("rmdir /s /q $tempDir");

logMsg("Done! Package created: $zipName");
