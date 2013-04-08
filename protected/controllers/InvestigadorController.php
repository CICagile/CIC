<?php
/**
 * Controlador del modelo de asistentes, recibe las instrucciones del usuario, muestra la interfaz y llama a las funciones del modelo.
 */
class InvestigadorController extends Controller
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
				'actions'=>array(''),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create,index'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(''),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        /**
         * Muestra la ventana con el formulario para registrar un investigador y llama a las funciones
         * del modelo que registran al investigador en la BD. Tambien realiza las validaciones de los datos.
         * @throws CHttpException Manejo de errores en caso de que ocurra un problema con la conexion a la BD.
         */
        public function actionCreate()
	{
		$model=new Investigador;
                $model->scenario = 'nuevo';
                $periodo = new Periodos;

		// Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation(array($model,$periodo));

		if(isset($_POST['Investigador']) && isset($_POST['Periodos']))
		{
			$model->attributes=$_POST['Investigador'];
                        $periodo->attributes = $_POST['Periodos'];
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
	}//fin action create
        
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
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='investigador-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

?>
