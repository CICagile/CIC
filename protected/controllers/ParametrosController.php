<?php

/**
 * Esta clase controla los parámetros del sistema. Desde aquí se muestran las opciones
 * para cambiar algunas variables (e.j. límite de horas que hace un estudiante, etc...).
 * También se usa para cambiar y agregar datos comunes en la base de datos (generalmente
 * aparecen como listas drop-down en las vistas).
 */
class ParametrosController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';
    
    /**
     * @return array action filters
     */
    public function filters()
    {
            return array(
                    'accessControl', // perform access control for CRUD operations
                    'postOnly + delete', // we only allow deletion via POST request
            );
    }
    
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
            return array(		
                    array('allow', // allow authenticated user to perform 'create' and 'update' actions
                            'actions'=>array(),
                            'users'=>array('@'),
                    ),
                    array('allow', // allow admin user to perform 'admin' and 'delete' actions
                            'actions'=>array(),
                            'users'=>array('admin'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
    }
}//fin clase Parametros controller
?>
