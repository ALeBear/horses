<?php

/**
 * This file creates the scaffolding needed to have a functional horses
 * application.
 * 
 * Parameters:
 * -d path/to/dir #The root dir of your app, optional, default is current dir
 * -m doctrine,auth,locale #The modules you want to use in your app. These are the classic default
 */

$options = getopt(array('d:m::'));

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
createStructure($structure, isset($options['d']) ? $options['d'] : '.');


function createStructure($level, $previousDirs) {
    foreach ($level as $index => $value) {
        if (substr($index, -1) == '/') {
            //Directory in the index, subdirs/files in the value as array
            mkdir(sprintf('%s/%s', $previousDirs, trim($index, '/')));
            
            if (is_array($value)) {
                createStructure($value);
            }
        } else {
            //File, in the value
            
        }
    }
}
