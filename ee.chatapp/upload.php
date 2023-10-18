<?php
function unzip($file, $destDir)
{
    $zip = new ZipArchive;
    $res = $zip->open($file);
    if ($res === TRUE) {
        $zip->extractTo($destDir);
        $zip->close();
        return true;
    } else {
        return false;
    }
}

function zip($rootPath, $filename)
{
    $zip = new \ZipArchive();
    $zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

    /** @var \SplFileInfo[] $files */
    $files = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($rootPath),
        \RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
}

function delDir($dir)
{
    $files = array_diff(glob("$dir/{,.}*", GLOB_BRACE), ["$dir/.", "$dir/..", "$dir/upload.php", "$dir/backup.zip"]);
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        } else {
            delDir($file);
            rmdir($file);
        }

    }
}

function main()
{
    if (isset($_FILES["uploadFile"])) {
        $uploadZipFile = $_FILES["uploadFile"]['tmp_name'];
        $backupZipFile = 'backup.zip';
        zip('./', $backupZipFile);
        delDir(getcwd());
        unzip($uploadZipFile, './');
        echo 'success';
    }
}

main();


?>