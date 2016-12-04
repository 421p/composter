<?php

$phar = new Phar('composter.phar');

function copy_ex($first, $second) {
    exec('cp -rf '.$first.' '.$second);
}

if (is_dir('phpmake')) {
    exec('rm -rf phpmake');
}

mkdir('phpmake', 0777, true);

copy_ex('resources', 'phpmake/resources');
copy_ex('vendor', 'phpmake/vendor');
copy_ex('src', 'phpmake/src');
copy_ex('index.php', 'phpmake/index.php');

$phar->buildFromDirectory('phpmake');

$phar->compressFiles(Phar::GZ);