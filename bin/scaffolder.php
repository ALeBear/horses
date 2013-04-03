<?php

/**
 * This file creates or updates the scaffolding needed to have a functional
 * horses application.
 * 
 * Parameters:
 * -d path/to/dir #The root dir of your app, optional, default is current dir
 * -m doctrine,auth,locale #The modules you want to use in your app. These are the classic default
 */

$options = getopt('d:m:');

$modules = isset($options['m']) ? explode(',', $options['m']) : array();

$structure = array(
    'application/' => array(
        'config/' => array(
            'kernel.yml',
            'view.yml'
        ),
        'controller/' => array(
            'horses_partials/' => null,
            'defaulter/' => array(
                'Index.php',
                'Index-view.php'
            )
        ),
        'layout.php'
    ),
    'lib/' => null,
    'htdocs/' => array(
        'index.php',
        '404.php',
        '500.php',
        '.htaccess'
    )
);

if (in_array('locale', $modules)) {
    $structure['application/']['config/'][] = 'locale.yml';
    $structure['application/']['controller/'][] = 'common-dict.en_US.ini';
    $structure['application/']['controller/']['defaulter/'][] = 'Index-dict.en_US.ini';
}
if (in_array('auth', $modules)) {
    $structure['application/']['config/'][] = 'auth.yml';
}
if (in_array('doctrine', $modules)) {
    $structure['application/']['config/'][] = 'db.yml';
    $structure['application/']['doctrineProxies/'] = null;
    $structure[] = 'cli-config.php';
}

//Create dirs and files
$path = isset($options['d']) ? $options['d'] : '.';
createStructure($structure, $path, $path);

//Paste Kernel bootstrap
$frontController = sprintf('%s/htdocs/index.php', $path);
if (!strpos(file_get_contents($frontController), 'Kernel::factory')) {
    file_put_contents(
        $frontController,
        sprintf("Kernel::factory()->run(__DIR__ . '/..', array(%s));", count($modules) ? "'" . implode("', '", $modules) . "'" : ''),
        FILE_APPEND);
}


function createStructure($level, $previousDirs, $originalPath) {
    foreach ($level as $index => $value) {
        if (substr($index, -1) == '/') {
            //Directory in the index, subdirs/files in the value as array
            $currentDir = sprintf('%s/%s', $previousDirs, trim($index, '/'));
            file_exists($currentDir) || mkdir($currentDir);
            
            if (is_array($value)) {
                createStructure($value, $currentDir, $originalPath);
            }
        } else {
            //File, in the value
            $file = sprintf('%s/%s', $previousDirs, $value);
            if (file_exists($file)) {
                return;
            }
            
            //Try to find one in horses as a template, otherwise empty file
            $fileToCopy = sprintf('%s/vendor/alebear/horses/bin/templates/%s+%s',
                $originalPath,
                substr(str_replace('/', '+', $previousDirs), strlen($originalPath) + 1),
                $value);
            if (file_exists($fileToCopy)) {
                copy($fileToCopy, $file);
            } else {
                touch($file);
            }
        }
    }
}
