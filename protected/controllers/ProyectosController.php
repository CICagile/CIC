<?php

class ProyectosController extends Controller
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
				'actions'=>array('create','update'),
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
        
        protected function FechaPhptoMysql($pfechaphp)
        {
            try{
                list($d, $m, $y) = explode('-', $pfechaphp); 
                $nuevafecha=mktime(0, 0, 0, $m, $d, $y);
                $fechamysql=strftime('%Y-%m-%d',$nuevafecha);
            }
            catch (Exception $e){ //El catch no ejecuta ninguna funcion porque las excepciones son manejas por el CErrorHandler de Yii.                
            }                
                return $fechamysql;
        }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
            $modelproyectos= new Proyectos;
            $modelperiodos = new Periodos;
            
            $this->performAjaxValidation(array($modelproyectos,$modelperiodos));
            
            if(isset($_POST['Periodos']) && isset($_POST['Proyectos']))
            {	                
                 $modelperiodos->attributes=$_POST['Periodos'];
                 unset($_POST['Periodos']);
                 
                if ($modelperiodos->validate()) {
                    
                    $modelperiodos->inicio = $this->FechaPhptoMysql($modelperiodos->inicio);
                    $modelperiodos->fin = $this->FechaPhptoMysql($modelperiodos->fin);  
                    
                    $transaction = Yii::app()->db->beginTransaction();                     
                    
                    $resultado = $modelperiodos->save(false);//Guardo el periodo sin validar, ya que lo valide con anterioridad                                                           

                    $modelproyectos->attributes=$_POST['Proyectos'];
                     unset($_POST['Proyectos']);
                    $modelproyectos->tbl_Periodos_idPeriodo = $modelperiodos->idPeriodo;  

                    $resultado = $resultado ? $modelproyectos->save() : $resultado;
                    if ($resultado){
                        $transaction->commit(); 
                        Yii::log("Creación exitosa del proyecto con el código: ".$modelproyectos->codigo, "info", "application.
controllers.ProyectosController");
                        $this->redirect(array('view','id'=>$modelproyectos->idtbl_Proyectos));
                    }
                    else{                            
                            $transaction->rollBack(); 
                            Yii::log("Rollback al intentar crear el proyecto con el código: ".$modelproyectos->codigo, "warning", "application.
controllers.ProyectosController");
                            throw new CHttpException(500,'Ha ocurrido un error interno, vuelva a intentarlo.'); 
                    }
                }            
            }

            $this->render('create',array(
                    'modelproyectos'=>$modelproyectos,
                    'modelperiodos' => $modelperiodos,
            ));
	}   
        
        

        /**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$modelproyectos =$this->loadModel($id);
                $modelperiodos = $proyecto->periodos;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation(array($modelproyectos,$modelperiodos));

		if(isset($_POST['Proyectos']))
		{
			$model->attributes=$_POST['Proyectos'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->idtbl_Proyectos));
		}

		$this->render('update',array(
			'modelproyectos'=> $modelproyectos,
                        'modelperiodos' => $modelperiodos,
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
		$dataProvider=new CActiveDataProvider('Proyectos');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Proyectos('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Proyectos']))
			$model->attributes=$_GET['Proyectos'];

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
		$model=Proyectos::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'La página solicitado no se ha encontrado.');                       
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($models)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='proyectos-form')
		{
			echo CActiveForm::validate($models);
			Yii::app()->end();
		}
	}
}
