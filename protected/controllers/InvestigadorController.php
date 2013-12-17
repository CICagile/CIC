<?php
/**
 * Controlador del modelo de investigadores, recibe las instrucciones del usuario, muestra la interfaz y llama a las funciones del modelo.
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
			array('allow',  // allow all users 
				'actions'=>array('view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user 
				'actions'=>array('create','index','codigoautocomplete','reportarHoras','actualizarReporteHorasMes'/*,'horasmes_excel'*/),
				'users'=>array('@'),
			),
			array('allow', // allow admin user
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        /**
         * Busca el codigo de la variable GET en la BD para autocompletar un campo de texto. 
         */
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
         * Muestra la ventana con el formulario para registrar un investigador y llama a las funciones
         * del modelo que registran al investigador en la BD. Tambien realiza las validaciones de los datos.
         * @throws CHttpException Manejo de errores en caso de que ocurra un problema con la conexion a la BD.
         */
        public function actionCreate()
	{
		$model=new Investigador;
                $model->scenario = 'nuevo';
                $periodo = new Periodos;
                $datos_horas = array();

		// Uncomment the following line if AJAX validation is needed
                $this->performAjaxValidation(array($model,$periodo));

		if(isset($_POST['Investigador']) && isset($_POST['Periodos']) && isset($_POST['formhoras']))
		{
                    $horas = array();
                    $datos_horas = $_POST['formhoras']['formhoras'];
                    $model->attributes = $_POST['Investigador'];
                    $periodo->attributes = $_POST['Periodos'];
                    foreach ($datos_horas as $dato)
                    {
                        if (array_key_exists($dato['tipo_horas'],$horas))
                        {
                            $model->addError('horas',  'El tipo de horas no se puede repetir.');//TODO: Esto se debería validar dentro del modelo.
                            break;
                        }//fin si el tipo de horas se repite.
                        else
                        {
                            $horas[$dato['tipo_horas']] = $dato['cantidad_horas'];
                        }//fin si el tipo de horas no se repite
                    }
                    $model->horas = $horas;
                    $model->validarCedulaUnica();
                    $periodo->validarFechaInicioAsistencia($model->proyecto);
                    $periodo->validarFechaFinAsistencia($model->proyecto);
                    if($model->validate(NULL,false) && $periodo->validate(NULL,false)){
                        if($model->crear($periodo))
                            $this->redirect(array('index'));
                        else
                            throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                    }//fin si los datos del investigador són válidos
		}

		$this->render('create',array(
			'model'=>$model,
                        'periodo'=>$periodo,
                        'horas'=>  array_values($datos_horas),
		));
	}//fin action create
        
        /**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->actionAdmin();
	}
        
        
	/** Invoca la pantalla donde se muestran todos los investigadores actuales*/
	public function actionAdmin(){
                $filtersForm=new FiltersForm;
                $this->render('admin',array(
                    'filtersForm' => $filtersForm,
                ));
	}
        
        /*Muestra todas las horas de un investigador*/
        public function actionReportarHoras($id){
        $model = $this->loadModel($id);
        $data_provider = Investigador::model()->obtenerHorasinvestigador($id,null); //se pasa un null para mostrar el historial completo
        $this->render('reportarHoras', array(
            'model' => $model,
            'data_provider' => $data_provider
        ));
    }
        /*Reporte de horas de un investigador con el filtro de mes*/
        public function actionActualizarReporteHorasMes($pCedula){
        if(isset($_POST['mes_reporte'])){
            //es necesario concatenar primero el mes para que la base de datos haga una comparacion adecuada
            $fecha_mes = '01-' . $_POST['mes_reporte'];
            $data_provider = Investigador::model()->obtenerHorasInvestigador($pCedula, $fecha_mes);
            $this->renderPartial('_reporteHorasMes', array('data_provider'=> $data_provider, 'fecha_mes'=>$fecha_mes));
        }
    }
        
        public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
        
      /*   public function actionreportehorasmes_excel($id)
	{
                $model = new Investigador;
                $atributos = $model->reportehorasmes_excel($id);
        $model = $this->loadModel($id);
        $resultado = Investigador::model()->obtenerHorasinvestigador($id,null); //se pasa un null para mostrar el historial completo
        $this->render('HorasMes_Excel', array(
            'model' => $model,
            'data_provider' => $data_provider
        ));
      
	}*/
        
        public function actionHorasMes_Excel($id){
        $model = $this->loadModel($id);
        $data_provider = Investigador::model()->obtenerHorasinvestigador($id,null); //se pasa un null para mostrar el historial completo
        $this->render('HorasMes_Excel', array(
            'model' => $model,
            'data_provider' => $data_provider
        ));
        }
        
        public function loadModel($id)
	{
		$model = new Investigador;
                $atributos = $model->buscarInvestigadorporCedula($id);
		if($atributos===null)
			throw new CHttpException(404,'No se encontro la cedula ' . $id);
                $model->attributes = $atributos;
		return $model;
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
