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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','updateDP','codigoautocomplete','update'),
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
                $criteria->alias = "proyecto";
                $criteria->condition = "proyecto.codigo like '" . $_GET['term'] . "%'";
                
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

		// Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);

		if(isset($_POST['Asistente']))
		{
			$model->attributes=$_POST['Asistente'];
                        $model->ajustarCarnetBuscado();
			if($model->validate()){
                            if($model->crear())
				$this->actionAdmin ();
                            else
                                $this->redirect ('error');
                        }
		}

		$this->render('create',array(
			'model'=>$model,
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

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Asistente']))
		{
			$model->attributes=$_POST['Asistente'];
                        $model->ajustarCarnetBuscado($id);
			if($model->validate()){
				if ($model->actualizarDatosPersonales($id))
                                    $this->redirect(Yii::app()->homeUrl);
                                //else
                                    //$this->redirect('error');
                        }//fin si los datos son válidos
		}

		$this->render('updateDP',array(
			'model'=>$model,
		));
            }
	}
        

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		//$dataProvider=new CActiveDataProvider('Asistente');
		$this->render('index'
		);
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
