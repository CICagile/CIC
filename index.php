<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';

// si está en la carpeta (...)-CIC/
// establece la configuracion del entorno para desarrollo
if(strncmp(strrev(dirname(__FILE__)), DIRECTORY_SEPARATOR . 'CIC-', 5)){
    $config=dirname(__FILE__).'/protected/config/devmain.php';
    // remove the following lines when in production mode
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    // specify how many levels of call stack should be shown in each log message
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
}
//sino, establece la configuracion para produccion
else{
    $config=dirname(__FILE__).'/protected/config/main.php';
}



require_once($yii);
Yii::createWebApplication($config)->run();
