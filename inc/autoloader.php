<?php

// Autoloader
spl_autoload_register(
    function ($classname) {
        /** @inc $classname String */
        $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
        $incPath = CODE_ROOT . '/' . $classname . '.php';
        if (file_exists($incPath)) {
            include_once $incPath;
        }
    }
);
