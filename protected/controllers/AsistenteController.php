<?php

class AsistenteController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','updateDP','codigoautocomplete','update','index'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        public function actionCodigoAutoComplete()
        {
            if (isset($_GET['term'])) {
                $criteria = new CDbCriteria;
                $criteria->alias = "pr";
                $criteria->select = 'pr.nombre nombre, pr.codigo codigo, pr.idtbl_proyectos idtbl_Proyectos';
                $criteria->join = 'INNER JOIN tbl_HistorialProyectosPeriodos HPP ON pr.idtbl_Proyectos = HPP.idtbl_Proyectos
                                   INNER JOIN tbl_Periodos P ON HPP.idPeriodo = P.idPeriodo';
                $criteria->condition = "pr.codigo LIKE '" . $_GET['term'] . "%' AND p.inicio <= SYSDATE() AND p.fin > SYSDATE()";
                
                $dataProvider = new CActiveDataProvider(get_class(Proyectos::model()), array(
                    'criteria'=>$criteria,
                ));
                $proyectos = $dataProvider->getData();
                $return_array = array();
                foreach($proyectos as $proyecto) {
                    $return_array[] = array(
                        'label'=>$proyecto->nombre,
                        'value'=>$proyecto->codigo,
                        'id'=>$proyecto->idtbl_Proyectos,
                    );
                }
                echo CJSON::encode($return_array);
            }
        }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Asistente;
                $model->scenario = 'nuevo';
                $periodo = new Periodos;

		// Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation(array($model,$periodo));

		if(isset($_POST['Asistente']) && isset($_POST['Periodos']))
		{
			$model->attributes=$_POST['Asistente'];
                        $periodo->attributes = $_POST['Periodos'];
                        $model->validarCarnetUnico();
                        $model->validarCedulaUnica();
			if($model->validate(NULL,false)){
                            $periodo->validarFechaInicioAsistencia($model->codigo);
                            $periodo->validarFechaFinAsistencia($model->codigo);
                            if ($periodo->validate(NULL,false)) {
                                if($model->crear($periodo))
                                    $this->redirect(array('index'));
                                else
                                    throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                            }//fin si el periodo es válido
                        }//fin si los datos del asistente són válidos
		}

		$this->render('create',array(
			'model'=>$model,
                        'periodo'=>$periodo,
		));
	}
        

	/**
         * Actualiza los Datos Personales de un asistente.
         * @param type $id Carnet del asistente.
         */
	public function actionUpdateDP($id = NULL)
	{
            if ($id === NULL) {
                /* Cuando busca un asistente, en la página en la que ve los detalles del asistente,
                 * le debería salir las opciones de modificar que son modificar datos personales,
                 * agregar asistente a un nuevo proyecto y modificar datos del asistente
                 */
                $this->actionIndex();
            }
            else {
		$model=$this->loadModel($id);
                $model->scenario = 'actDP';
                $nombre = $model->nombre;
                $apellido1 = $model->apellido1;
                $apellido2 = $model->apellido2;
                $carnet = $id;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Asistente']))
		{
                        $cedula = $model->cedula;
			$model->attributes=$_POST['Asistente'];
                        $model->validarCarnetUnico($id);
                        $model->validarCedulaUnica($cedula);
			if($model->validate(NULL, false)){
                                $model->attributes = $_POST['Asistente'];
				if ($model->actualizarDatosPersonales($id))
                                    $this->redirect(array('view','id'=>$model->carnet));
                                else
                                    throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                        }//fin si los datos son válidos
		}

		$this->render('updateDP',array(
			'model'=>$model,
                        'nombre'=>$nombre,
                        'apellido1'=>$apellido1,
                        'apellido2'=>$apellido2,
                        'carnet'=>$carnet,
		));
            }
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		//$dataProvider=new CActiveDataProvider('Asistente');
		/*$this->render('index'
		);*/
                $this->actionAdmin();
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
                $filtersForm=new FiltersForm;
                $this->render('admin',array(
                    'filtersForm' => $filtersForm,
                ));
	}
        
       	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = new Asistente;
                $atributos = $model->buscarAsistentePorCarnet($id);
		if($atributos===null)
			throw new CHttpException(404,'No se encontro el carnet ' . $id);
                $model->attributes = $atributos;
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='asistente-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
