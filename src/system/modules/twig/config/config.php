<?php

include(TL_ROOT . '/plugins/twig/lib/Twig/Autoloader.php');
// Twig_Autoloader::register();
ini_set('unserialize_callback_func', 'spl_autoload_call');
spl_autoload_register(array(new Twig_Autoloader, 'autoload'), true, true);
