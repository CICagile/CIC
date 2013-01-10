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
				'actions'=>array('create','update','codigoautocomplete'),
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

		// Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation($model);

		if(isset($_POST['Asistente']))
		{
			$model->attributes=$_POST['Asistente'];
                        $model->validarCodigoProyecto();
			if($model->validate(NULL, false)){  //se agrega el false para que no borre el error de validar codigo proyecto si este es generado.
                            if($model->crear())
				$this->redirect(Yii::app()->homeUrl);
                            else
                                $this->redirect ('error');
                        }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Asistente']))
		{
			$model->attributes=$_POST['Asistente'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->idtbl_Personas));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
		$dataProvider=new CActiveDataProvider('Asistente');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Asistente('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Asistente']))
			$model->attributes=$_GET['Asistente'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Asistente::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
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
