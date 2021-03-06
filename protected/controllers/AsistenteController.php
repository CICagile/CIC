<?php

class AsistenteController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
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
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'updateDP', 'reportarProyectos', 'reportarHoras', 'ActualizarReporteHorasMes', 'codigoautocomplete', 'update', 'index', 'desvincular'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionCodigoAutoComplete() {
        if (isset($_GET['term'])) {
            $criteria = new CDbCriteria;
            $criteria->alias = "pr";
            $criteria->select = 'pr.nombre nombre, pr.codigo codigo, pr.idtbl_proyectos idtbl_Proyectos';
            $criteria->join = 'INNER JOIN tbl_HistorialProyectosPeriodos HPP ON pr.idtbl_Proyectos = HPP.idtbl_Proyectos
                                   INNER JOIN tbl_Periodos P ON HPP.idPeriodo = P.idPeriodo';
            $criteria->condition = "pr.codigo LIKE '" . $_GET['term'] . "%' AND p.inicio <= SYSDATE() AND p.fin > SYSDATE()";

            $dataProvider = new CActiveDataProvider(get_class(Proyectos::model()), array(
                        'criteria' => $criteria,
                    ));
            $proyectos = $dataProvider->getData();
            $return_array = array();
            foreach ($proyectos as $proyecto) {
                $return_array[] = array(
                    'label' => $proyecto->nombre,
                    'value' => $proyecto->codigo,
                    'id' => $proyecto->idtbl_Proyectos,
                );
            }
            echo CJSON::encode($return_array);
        }
    }

    public function actionDesvincular($idtbl_Proyectos, $carnet) {
        $model = $this->loadModel($carnet);
        if ($model->desvincular($idtbl_Proyectos, $carnet))
            $this->redirect(array('Proyectos/ver', 'id' => $idtbl_Proyectos));
        else
            throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
//        $historial_proyectos_asistente = $model->obtenerHistorialProyectosAsistente();
//
//        $data_provider = new CArrayDataProvider(
//                        $historial_proyectos_asistente,
//                        array(
//                            'keyField'=>'codigo',
//                            'id' => 'asistente-historial-proyectos',
//                            'sort' => array(
//                                'attributes' => array(
//                                    'idtbl_proyectos','codigo', 'inicio', 'fin','rol'
//                                ),
//                            ),
//                            'pagination' => array(
//                                'pageSize' => 50,
//                            ),
//                ));
        $this->render('view', array(
            'model' => $model,
            //'data_provider' => $data_provider,
        ));
    }

    /**
     * hace un reporte de el rol que ha tenido un estudiante por proyecto y asociado a un periodo de tiempo
     * presentando totales por cada proyecto
     * @param type $id 
     */
    public function actionReportarProyectos($id){
        //$model = $this->loadModel($id);
        $model = $this->loadModel($id);
        $historial_proyectos_asistente = $model->obtenerHistorialProyectosAsistente($id);

        $data_provider = new CArrayDataProvider(
                        $historial_proyectos_asistente,
                        array(
                            'keyField'=>'codigo',
                            'id' => 'asistente-historial-proyectos',
                            'sort' => array(
                                'attributes' => array(
                                    'idtbl_proyectos','codigo', 'inicio', 'fin','rol'
                                ),
                            ),
                            'pagination' => array(
                                'pageSize' => 50,
                            ),
                ));
        $this->render('reportarProyectos', array(
            'model' => $model,
            'data_provider' => $data_provider,
        ));
    }
    
    
    /**
     * Reporta un listado de todas las horas que ha realizado un asistente por proyecto
     * Hace un render a vista que permite ver las horas que se realizan en un mes dado
     * @param type $id carnet del estudiante
     */
    public function actionReportarHoras($id){
        $model = $this->loadModel($id);
        $data_provider = Asistente::model()->obtenerHorasAsistente($id,null); //se pasa un null para mostrar el historial completo
        $this->render('reportarHoras', array(
            'model' => $model,
            'data_provider' => $data_provider
        ));
    }
    
    /**
     * Permite visualizar las horas que realiza un asistente, para un mes dado
     *  implementa un render partial de _reporteHorasMes, y recibe el valor a partir de una peticion
     *  AJAX, que se realiza en la vista Asistente/reportarHoras.php con un ajaxbutton
     * @param type $pCarnet 
     */
    public function actionActualizarReporteHorasMes($pCarnet){
        if(isset($_POST['mes_reporte'])){
            //es necesario concatenar primero el mes para que la base de datos haga una comparacion adecuada
            $fecha_mes = '01-' . $_POST['mes_reporte'];

            $data_provider = Asistente::model()->obtenerHorasAsistente($pCarnet, $fecha_mes);
            $this->renderPartial('_reporteHorasMes', array('data_provider'=> $data_provider, 'fecha_mes'=>$fecha_mes));
        }
    }
    
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Asistente;
        $model->scenario = 'nuevo';
        $periodo = new Periodos;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation(array($model, $periodo));

        if (isset($_POST['Asistente']) && isset($_POST['Periodos'])) {
            $model->attributes = $_POST['Asistente'];
            $periodo->attributes = $_POST['Periodos'];
            $model->validarCarnetUnico();
            $model->validarCedulaUnica();
            if ($model->validate(NULL, false)) {
                $periodo->validarFechaInicioAsistencia($model->codigo);
                $periodo->validarFechaFinAsistencia($model->codigo);
                if ($periodo->validate(NULL, false)) {
                    if ($model->crear($periodo))
                        $this->redirect(array('index'));
                    else
                        throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                }//fin si el periodo es válido
            }//fin si los datos del asistente són válidos
        }

        $this->render('create', array(
            'model' => $model,
            'periodo' => $periodo,
        ));
    }

    /**
     * Actualiza los Datos Personales de un asistente.
     * @param type $id Carnet del asistente.
     */
    public function actionUpdateDP($id = NULL) {
        if ($id === NULL) {
            /* Cuando busca un asistente, en la página en la que ve los detalles del asistente,
             * le debería salir las opciones de modificar que son modificar datos personales,
             * agregar asistente a un nuevo proyecto y modificar datos del asistente
             */
            $this->actionIndex();
        } else {
            $model = $this->loadModel($id);
            $model->scenario = 'actDP';
            $nombre = $model->nombre;
            $apellido1 = $model->apellido1;
            $apellido2 = $model->apellido2;
            $carnet = $id;

            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);

            if (isset($_POST['Asistente'])) {
                $cedula = $model->cedula;
                $model->attributes = $_POST['Asistente'];
                $model->validarCarnetUnico($id);
                $model->validarCedulaUnica($cedula);
                if ($model->validate(NULL, false)) {
                    $model->attributes = $_POST['Asistente'];
                    if ($model->actualizarDatosPersonales($id))
                        $this->redirect(array('view', 'id' => $model->carnet));
                    else
                        throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                }//fin si los datos son válidos
            }

            $this->render('updateDP', array(
                'model' => $model,
                'nombre' => $nombre,
                'apellido1' => $apellido1,
                'apellido2' => $apellido2,
                'carnet' => $carnet,
            ));
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        //$dataProvider=new CActiveDataProvider('Asistente');
        /* $this->render('index'
          ); */
        $this->actionAdmin();
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $filtersForm = new FiltersForm;
        $this->render('admin', array(
            'filtersForm' => $filtersForm,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = new Asistente;
        $atributos = $model->buscarAsistentePorCarnet($id);
        if ($atributos === null)
            throw new CHttpException(404, 'No se encontro el carnet ' . $id);
        $model->attributes = $atributos;
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'asistente-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
