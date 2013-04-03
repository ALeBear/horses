<?php

/**
 * This file creates or updates the scaffolding needed to have a functional
 * horses application.
 * 
 * Parameters:
 * -d path/to/dir #The root dir of your app, optional, default is current dir
 * -m doctrine,auth,locale #The modules you want to use in your app. These are the classic default
 */

$options = getopt('d:m::');

if (!isset($options['m'])) {
    throw new InvalidArgumentException('"m" parameter mandatory for modules you want included, no value if none wanted');
}

$modules = $options['m'] ? explode(',', $options['m']) : array();

$structure = array(
    'application/' => array(
        'config/' => array(
            'kernel.yml',
            'view.yml'
        ),
        'controller/' => array('horses_partials/' => null),
        'layout.php'
    ),
    'lib/' => null,
    'htdocs/' => null
);

if (in_array('locale', $modules)) {
    $structure['application/']['config/'][] = 'locale.yml';
}
if (in_array('auth', $modules)) {
    $structure['application/']['config/'][] = 'auth.yml';
}
if (in_array('doctrine', $modules)) {
    $structure['application/']['config/'][] = 'db.yml';
    $structure['application/'][] = 'doctrineProxies/';
    $structure[] = 'cli-config.php';
}

//Create dirs and files
$path = isset($options['d']) ? $options['d'] : '.';
createStructure($structure, $path, $path);


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
            $fileToCopy = sprintf('%s/vendor/alebear/horses/bin/templates/%s-%s',
                $originalPath,
                substr(str_replace('/', '-', $previousDirs), strlen($originalPath) + 1),
                $value);
            if (file_exists($fileToCopy)) {
                copy($fileToCopy, $file);
            } else {
                touch($file);
            }
        }
    }
}
