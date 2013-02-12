<?php

class ProyectosController extends Controller {

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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('view','ActualizarInfoAsistentes','index','create', 'update', 'agregarasistente', 'AsistenteAutoComplete', 'ValidarAgregarAsistente'),
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

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $this->render('view', array(
            'model' => $model,
            'asistente' => new Asistente,
            'dataProvider' => $model->buscarAsistentesActivosPorProyecto(),
        ));
    }
    
    /**
     * Verifica que los nuevos datos de los asistentes sean válidos y guarda los cambios.
     * @param string $pRol El rol del asistente en el proyecto
     * @param double $pHoras Horas que el asistente debe dedicar semanalmente al proyecto.
     * @param fecha $pFin La fecha de finalización de la asistencia.
     */
    public function actionActualizarInfoAsistentes($id) {
        $model = $this->loadModel($id);
        $asistente = new Asistente;
        $dataProvider = $model->buscarAsistentesActivosPorProyecto();
        $datos_validos = true;
        
        if (isset($_POST['horas'])) {
            $datos_asistentes = $dataProvider->data;
            $horas = $_POST['horas'];
            foreach ($horas as $index=>$hora){
                if ($hora != $datos_asistentes[$index]["horas"]){
                    $asistente->carnet = $datos_asistentes[$index]['carnet'];
                    $horas_totales = $asistente->contarHorasAsistenciaActuales();
                    $horas_totales -= $datos_asistentes[$index]["horas"];
                    $horas_totales += $hora;
                    $asistente->horas = $horas_totales;
                    if ($asistente->validate('horas')) {
                        if(!$model->CambiarHorasAsistencia($hora,$asistente->carnet))
                                throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                    }//fin si los datos del asistente son validos
                    else
                        $datos_validos = false;
                }//fin si las horas son diferentes
            }//fin for
            if ($datos_validos)
                $this->redirect(array('view','id'=>$id)); //Sólo llega a esta instrucción si no hay errores.
            /* Hacer las validaciones y cambios en este orden
             * -Fecha finalización.
             * -Horas
             * -Roles
             */
            if(/*Hacer validaciones*/false) {
                //Cambiar fecha finalización
                if(/*!$model->CambiarHorasAsistencia($horas,$carnet)*/false)
                    throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                //Cambiar roles
                //si todo fue exitoso:
                //$this->redirect(array('view','id'=>$id));
            }//fin si los cambios son posibles
        }//fin si asistente se modificó.
        
        $this->render('viewEditable', array(
            'model' => $this->loadModel($id),
            'asistente' => $asistente,
            'dataProvider' => $dataProvider,
        ));
    }//fin actualizar informacion de los assitentes activos del proyecto.

    protected function FechaPhptoMysql($pfechaphp) {
        try {
            list($d, $m, $y) = explode('-', $pfechaphp);
            $nuevafecha = mktime(0, 0, 0, $m, $d, $y);
            $fechamysql = strftime('%Y-%m-%d', $nuevafecha);
        } catch (Exception $e) { //El catch no ejecuta ninguna funcion porque las excepciones son manejas por el CErrorHandler de Yii.                
        }
        return $fechamysql;
    }
    
    public function FechaMysqltoPhp($pfechamysql){
            try{
                $fecha = substr($pfechamysql, 0, 10);
                list($y, $m, $d) = explode('-', $fecha);               
                $fecha = $d.'-'.$m.'-'.$y;                 
            }
            catch (Exception $e){  
            } 
            return $fecha;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        
        $modelproyectos = new Proyectos;
        $modelperiodos = new Periodos;   
        
        $this->performAjaxValidation(array($modelproyectos,$modelperiodos));

        if (isset($_POST['Periodos']) && isset($_POST['Proyectos'])) {           
            
            $modelperiodos->attributes = $_POST['Periodos'];
            $modelproyectos->attributes = $_POST['Proyectos']; 
            
            if ($modelperiodos->validate() && $modelproyectos->validate()) {

                $modelperiodos->inicio = $this->FechaPhptoMysql($modelperiodos->inicio);
                $modelperiodos->fin = $this->FechaPhptoMysql($modelperiodos->fin);

                $transaction = Yii::app()->db->beginTransaction();

                $resultado = $modelperiodos->save(false); //Guardo el periodo sin validar, ya que lo valide con anterioridad                                                           

                
                $modelproyectos->tbl_Periodos_idPeriodo = $modelperiodos->idPeriodo;

                $resultado = $resultado ? $modelproyectos->save() : $resultado;
                if ($resultado) {
                    $transaction->commit();
                    Yii::log("Creación exitosa del proyecto con el código: " . $modelproyectos->codigo, "info", "application.
controllers.ProyectosController");
                    $this->redirect(array('view', 'id' => $modelproyectos->idtbl_Proyectos));
                } else {
                    $transaction->rollBack();
                    Yii::log("Rollback al intentar crear el proyecto con el código: " . $modelproyectos->codigo, "warning", "application.
controllers.ProyectosController");
                    throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                }
            }
        }

        $this->render('create', array(
            'modelproyectos' => $modelproyectos,
            'modelperiodos' => $modelperiodos,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $modelproyectos = $this->loadModel($id);
        $modelperiodos = $proyecto->periodos;

        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation(array($modelproyectos, $modelperiodos));

        if (isset($_POST['Proyectos'])) {
            $model->attributes = $_POST['Proyectos'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->idtbl_Proyectos));
        }

        $this->render('update', array(
            'modelproyectos' => $modelproyectos,
            'modelperiodos' => $modelperiodos,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Proyectos');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Proyectos('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Proyectos']))
            $model->attributes = $_GET['Proyectos'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Proyectos::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'La página solicitado no se ha encontrado.');
        return $model;
    }

    public function actionAgregarAsistente($id) {
        $model = $this->loadModel($id);
      
        if (isset($_POST['Proyectos'])) {
            
            $carnet = $_POST['asistente'];
            $idrol = $this->obtenerIdRol($_POST['rol']);
            $fechainicio = $this->FechaPhptoMysql($_POST['inicio']);
            $fechafin = $this->FechaPhptoMysql($_POST['fin']);
            $horas = $_POST['horas'];

            if ($model->agregarAsistenteProyecto($model->idtbl_Proyectos, $carnet, $idrol, $fechainicio, $fechafin, $horas)) {
                Yii::log("Asociacion exitosa del asistente: " . $carnet . " al proyecto: " . $model->idtbl_Proyectos, "info", "application.
    controllers.ProyectosController");                
                $response = array(
                    'ok' => true, 
                    'msg' => "Asociacion exitosa del asistente: " . $carnet . " al proyecto: " . $model->codigo
                );
                echo CJSON::encode($response);               
                Yii::app()->end();        
            } else {
                Yii::log("Error al asociar asistente: " . $carnet . " al proyecto: " . $model->idtbl_Proyectos, "warning", "application.
    controllers.ProyectosController");
                $response = array(
                    'ok' => false, 
                    'msg' => "Ha ocurrido un error al intentar asociar el asistente " . $carnet . " al proyecto: " . $model->codigo
                );
                echo CJSON::encode($response);               
                Yii::app()->end(); 
            }
        }

        $this->render('agregarasistente', array(
            'model' => $model,
        ));
    }

    public function actionAsistenteAutoComplete() {
        if (isset($_GET['term'])) {

            $keyword = $_GET['term'];
            // escape % and _ characters
            $keyword = strtr($keyword, array('%' => '\%', '_' => '\_'));

            $dataReader = Yii::app()->db->createCommand()
                    ->select(array('carnet', 'nombre', 'apellido1', 'apellido2'))
                    ->from('tbl_asistentes a')
                    ->join('tbl_personas p', 'a.idtbl_Personas=p.idtbl_Personas')
                    ->where(array('like', 'carnet', $keyword . '%'))
                    ->query();

            $return_array = array();
            if ($dataReader->count() == 0) {
                $return_array[] = array(
                    'label' => 'No se ha encontrado ese carnet.',
                    'value' => '',
                );
            } else {
                foreach ($dataReader as $row) {
                    $return_array[] = array(
                        'label' => $row['nombre'] . " " . $row['apellido1'] . " " . $row['apellido2'],
                        'value' => $row['carnet'],
                    );
                }
            }
            echo CJSON::encode($return_array);
        }
    }

    public function actionValidarAgregarAsistente() {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            $response = array();
            if ($action == 'validate_form_agregar') {//validate form on submit
                    $responseform = array();
                    $datacod = $_POST['codigo'];
                    $carne = $_POST['carne'];
                    $responseform[] = $this->validarAsistente($carne, $datacod);
                    $rol = $_POST['rol'];
                    $responseform[] = $this->validarRol($rol);
                    $datafechainicio = $_POST['fecha_inicio'];                    
                    $responseform[] = $this->validarFechaInicio($datafechainicio, $datacod);
                    $datafechafin = $_POST['fecha_fin'];                    
                    $responseform[] = $this->validarFechaFin($datafechafin, $datacod, $datafechainicio);
                    $horas = $_POST['horas'];
                    $responseform[] = $this->validarHorasAsistencia($horas, $carne);
                    
                    $response = array('ok' => true,'msg' => '');                    
                    foreach ($responseform as $res) {               
                         if(!$res['ok'])
                         {
                              $response['ok'] = false;
                              $response['msg'] = $response['msg']."</br>-".$res['msg'];
                         }
                    } 
            }
            else if ($action == 'validate_asistente') {
                if (isset($_POST['carne'])) {
                    $data = $_POST['carne'];
                    $datacod = $_POST['codigo'];
                    $response = $this->validarAsistente($data, $datacod);
                }
            }//validate-asistente                
            else if ($action == 'validate_rol') {
                if (isset($_POST['rol'])) {
                    $data = $_POST['rol'];
                    $response = $this->validarRol($data);
                }
            }//validate-rol
            else if ($action == 'validate_fecha_inicio') {
                if (isset($_POST['fecha_inicio']) && isset($_POST['codigo'])) {
                    $datafechainicio = $_POST['fecha_inicio'];
                    $datacod = $_POST['codigo'];
                    $response = $this->validarFechaInicio($datafechainicio, $datacod);
                }
            }//validate-fecha-inicio
            else if ($action == 'validate_fecha_fin') {
                if (isset($_POST['fecha_fin']) && isset($_POST['fecha_inicio'])&& isset($_POST['codigo'])) {
                    $datafechainicio = $_POST['fecha_inicio'];
                    $datafechafin = $_POST['fecha_fin'];
                    $datacod = $_POST['codigo'];
                    $response = $this->validarFechaFin($datafechafin, $datacod, $datafechainicio);
                }
            }//validate-fecha-fin 
            else if ($action == 'validate_horas') {
                if (isset($_POST['horas'])) {
                    $data = $_POST['horas'];
                    $response = $this->validarHorasAsistencia($data);
                }
            }//validate-horas
            echo CJSON::encode($response);
        }
    }

    protected function validarHorasAsistencia($phoras, $pcarne = null) {
        $response = array();
        define('MIN_VALUE', 1);   //Cantidad de horas minimas de asistencia por semana
        define('MAX_VALUE', 20);  //Cantidad de horas maximas de asistencia por semana
        $horas = trim($phoras);
        if ($horas == '') {//Si no ingresa el numero de horas
            $response = array(
                'ok' => false,
                'msg' => "Ingrese la cantidad de horas");
        } else if (!is_numeric($horas)) {
            $response = array(
                'ok' => false,
                'msg' => "Debe ingresar un numero.");
        } else if ($this->convertirStringNumerico($horas) < MIN_VALUE || $this->convertirStringNumerico($horas) > MAX_VALUE) {
            $response = array(
                'ok' => false,
                'msg' => "La cantidad de horas semanales debe estar en un rango de 1-20 horas.");           
        }else if(!is_null($pcarne)){//Esta validacion solo se ejecuta en submit action del form
            if ($this->validarCantidadHorasAcumuladas($horas, $pcarne)> MAX_VALUE) {
            $response = array(
                'ok' => false,
                'msg' => "La cantidad de horas que desea agregar a este Asistente excede la cantidad horas maximas permitidas (".MAX_VALUE.") en distintos proyectos del CIC por semana.");           
            }
            else{
                $response = array(
                'ok' => true,
                'msg' => "Valido.");
            }
        }else {
            $response = array(
                'ok' => true,
                'msg' => "Valido.");
        }
        return $response;
    }

    protected function validarFechaInicio($pfecha, $pidproyecto) {
        $response = array();
        $fecha = trim($pfecha);
        $codigo = trim($pidproyecto);
        if (!$this->validarFechaRangoAsistenciaProyecto($fecha, $codigo)) {
            $response = array(
                'ok' => false,
                'msg' => "La fecha de inicio asistencia seleccionada no cumple con el periodo del proyecto.");
        } else {
            $response = array(
                'ok' => true,
                'msg' => "Valido.");
        }
        return $response;
    }
    
    protected function validarFechaFin($pfechafin, $pidproyecto, $pfechainicio){
        $response = array();        
        $codigo = trim($pidproyecto);
        if($pfechafin == '') {
               $response = array(
                'ok' => false,
                'msg' => "La fecha de finalización no puede estar en blanco.");
        }  
        else if(strtotime($pfechafin) <= strtotime($pfechainicio)) {
               $response = array(
                'ok' => false,
                'msg' => "La fecha de finalización de la asistencia no puede ser menor o igual que la fecha de inicio.");
        }  
        else if (!$this->validarFechaRangoAsistenciaProyecto($pfechafin, $codigo)) {
            $response = array(
                'ok' => false,
                'msg' => "La fecha de finalizacion de asistencia seleccionada no cumple con el periodo del proyecto.");
        } 
        else {
            $response = array(
                'ok' => true,
                'msg' => "Valido.");
        }
        return $response;
    }

    protected function validarAsistente($pcarne, $pidproyecto) {
        $response = array();
        $carne = trim($pcarne);
        $idproyecto = trim($pidproyecto);
        if ($carne == '') {
            $response = array(
                'ok' => false,
                'msg' => "Indique el carnet del asistente.");
        } else if (!$this->existeCarnet($carne)) {
            $response = array(
                'ok' => false,
                'msg' => "El carnet #" . $carne . " no corresponde a ningun asistente.");
        } else if ($this->existeAsistenteProyecto($carne, $idproyecto)) {
            $response = array(
                'ok' => false,
                'msg' => "El asistente con carnet #" . $carne . " ya esta vinculado a este proyecto");
        }else {
            $response = array(
                'ok' => true,
                'msg' => "Valido.");
        }
        return $response;
    }

    protected function validarRol($prol) {
        $response = array();
        $rol = trim($prol);
        if ($rol == '') {//Si no selecciona un rol
            $response = array(
                'ok' => false,
                'msg' => "Seleccione un rol.");
        } else if (!$this->existeRol($rol)) {
            $response = array(
                'ok' => false,
                'msg' => "El rol que ha seleccionado es invalido.");
        } else {
            $response = array(
                'ok' => true,
                'msg' => "Valido.");
        }
        return $response;
    }

    protected function existeRol($prol) { //Permite determinar si un Rol existe a partir del nombre del rol.
        $criteria = new CDbCriteria();
        $criteria->compare('nombre', $prol);
        $roles = RolAsistente::model()->findAll($criteria);
        if (count($roles) == 0)
            return false;
        else
            return true;
    }
    
    protected function obtenerIdRol($prol){
        $criteria = new CDbCriteria();
        $criteria->compare('nombre', $prol);
        $roles = RolAsistente::model()->findAll($criteria);
        $idrol = $prol;
        foreach($roles as $rol)
        {
            $idrol = $rol->idtbl_RolesAsistentes;
        }      
        return $idrol;
    }

    protected function existeCarnet($carne) {//Permite determinar si un Carnet existe.
        $criteria = new CDbCriteria();
        $criteria->compare('carnet', $carne);
        $result = Asistentes::model()->findAll($criteria);
        if (count($result) == 0)
            return false;
        else
            return true;
    }
    
    protected function existeAsistenteProyecto($pcarne, $idproyecto) {        
        $asistente = Asistentes::model()->findByAttributes(array('carnet' => $pcarne));        
        $proyectosasistente = $asistente->proyectos;
        $res = false;
        foreach ($proyectosasistente as $proy)
        {
            if($proy->idtbl_Proyectos == $idproyecto)
            {
                $res = true;
            }
        }
        return $res;            
    }

    protected function validarFechaRangoAsistenciaProyecto($pfecha, $pidproyecto) {
        $proyecto = $this->loadModel($pidproyecto);
        $fecha = $this->FechaPhptoMysql($pfecha);

        $proyectoini = $proyecto->periodos->inicio;
        $proyectofin = $proyecto->periodos->fin;

        if (strtotime($proyectoini) > strtotime($fecha)) {
            return false;
        } else if (strtotime($proyectofin) < strtotime($fecha)) {
            return false;
        }
        else
            return true;
    }

    protected function convertirStringNumerico($pstring) {//Para utilizar esta funcion se debe comprobar con anticipacion que el var es numerico
        if ((float) $pstring != (int) $pstring)
            return (float) $pstring;
        else
            return (int) $pstring;
    }
    
    protected function validarCantidadHorasAcumuladas($phoras, $pcarne){
         define('ZERO_VALUE', 0);
         $asistente = Asistentes::model()->findByAttributes(array('carnet' => $pcarne));
         if(!is_null($asistente)){
            $horasacumuladas = $asistente->verificarHorasAcumuladasProyectos($asistente->idtbl_Asistentes);
            if(is_null($horasacumuladas)){  
                $phoras = ZERO_VALUE;
                return $phoras;
            }
            else{    
                $phoras = $phoras + current($horasacumuladas);
                return $phoras;
            }
         }
         else{
             $phoras = ZERO_VALUE;
             return $phoras;
         }
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($models) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'proyectos-form') {
            echo CActiveForm::validate($models);
            Yii::app()->end();
        }
    }

}
