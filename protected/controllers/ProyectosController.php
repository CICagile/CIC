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
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'agregarasistente', 'AsistenteAutoComplete', 'ValidarAgregarAsistente'),
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
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    protected function FechaPhptoMysql($pfechaphp) {
        try {
            list($d, $m, $y) = explode('-', $pfechaphp);
            $nuevafecha = mktime(0, 0, 0, $m, $d, $y);
            $fechamysql = strftime('%Y-%m-%d', $nuevafecha);
        } catch (Exception $e) { //El catch no ejecuta ninguna funcion porque las excepciones son manejas por el CErrorHandler de Yii.                
        }
        return $fechamysql;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $modelproyectos = new Proyectos;
        $modelperiodos = new Periodos;

        $this->performAjaxValidation(array($modelproyectos, $modelperiodos));

        if (isset($_POST['Periodos']) && isset($_POST['Proyectos'])) {
            $modelperiodos->attributes = $_POST['Periodos'];
            unset($_POST['Periodos']);

            if ($modelperiodos->validate()) {

                $modelperiodos->inicio = $this->FechaPhptoMysql($modelperiodos->inicio);
                $modelperiodos->fin = $this->FechaPhptoMysql($modelperiodos->fin);

                $transaction = Yii::app()->db->beginTransaction();

                $resultado = $modelperiodos->save(false); //Guardo el periodo sin validar, ya que lo valide con anterioridad                                                           

                $modelproyectos->attributes = $_POST['Proyectos'];
                unset($_POST['Proyectos']);
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
            $idrol = $_POST['rol'];
            $fechainicio = $this->FechaPhptoMysql($_POST['inicio']);
            $horas = $_POST['horas'];

            if ($model->agregarAsistenteProyecto($model->idtbl_Proyectos, $carnet, $idrol, $fechainicio, $horas)) {
                Yii::log("Asociacion exitosa del asistente: " . $carnet . " al proyecto: " . $model->idtbl_Proyectos, "info", "application.
    controllers.ProyectosController");
                $this->render('view', array(
                    'model' => $this->loadModel($model->idtbl_Proyectos),
                ));
            } else {
                Yii::log("Error al asociar asistente: " . $carnet . " al proyecto: " . $model->idtbl_Proyectos, "warning", "application.
    controllers.ProyectosController");
                $error = array('code' => '500', 'message' => 'No se puedo procesar su petición.');
                $this->render('error', $error);
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
                    ->where(array('like', 'carnet', '%' . $keyword . '%'))
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
            if ($action == 'validate_form_agregar') {
                    $responseform = array();
                    $carne = $_POST['carne'];
                    $responseform[] = $this->validarAsistente($carne);
                    $rol = $_POST['rol'];
                    $responseform[] = $this->validarRol($rol);
                    $datafecha = $_POST['fecha'];
                    $datacod = $_POST['codigo'];
                    $responseform[] = $this->validarFechaAsistencia($datafecha, $datacod);
                    $horas = $_POST['horas'];
                    $responseform[] = $this->validarHorasAsistencia($horas);
                    
                    $response = array('ok' => true,'msg' => '');                    
                    foreach ($responseform as $res) {               
                         if(!$res['ok'])
                         {
                              $response['ok'] = false;
                              $response['msg'] = $response['msg']."</br>-".$res['msg'];
                         }
                    } 
            }//validate form on submit
            else if ($action == 'validate_asistente') {
                if (isset($_POST['carne'])) {
                    $data = $_POST['carne'];
                    $response = $this->validarAsistente($data);
                }
            }//validate-asistente                
            else if ($action == 'validate_rol') {
                if (isset($_POST['rol'])) {
                    $data = $_POST['rol'];
                    $response = $this->validarRol($data);
                }
            }//validate-rol
            else if ($action == 'validate_fecha') {
                if (isset($_POST['fecha']) && isset($_POST['codigo'])) {
                    $datafecha = $_POST['fecha'];
                    $datacod = $_POST['codigo'];
                    $response = $this->validarFechaAsistencia($datafecha, $datacod);
                }
            }//validate-fecha
            else if ($action == 'validate_horas') {
                if (isset($_POST['horas'])) {
                    $data = $_POST['horas'];
                    $response = $this->validarHorasAsistencia($data);
                }
            }//validate-horas
            echo CJSON::encode($response);
        }
    }

    protected function validarHorasAsistencia($phoras) {
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
        } else {
            $response = array(
                'ok' => true,
                'msg' => "Valido.");
        }
        return $response;
    }

    protected function validarFechaAsistencia($pfecha, $pidproyecto) {
        $response = array();
        $fecha = trim($pfecha);
        $codigo = trim($pidproyecto);
        if (!$this->validarFechaInicioAsistencia($fecha, $codigo)) {
            $response = array(
                'ok' => false,
                'msg' => "La fecha seleccionada no cumple con el periodo del proyecto.");
        } else {
            $response = array(
                'ok' => true,
                'msg' => "Valido.");
        }
        return $response;
    }

    protected function validarAsistente($pcarne) {
        $response = array();
        $carne = trim($pcarne);
        if ($carne == '') {
            $response = array(
                'ok' => false,
                'msg' => "Indique el carnet del asistente.");
        } else if (!$this->existeCarnet($carne)) {
            $response = array(
                'ok' => false,
                'msg' => "El carnet #" . $carne . " no corresponde a ningun asistente.");
        } else {
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
    
    protected function obtenerIdRol($prol)
    {
        $criteria = new CDbCriteria();
        $criteria->compare('nombre', $prol);
        $roles = RolAsistente::model()->findAll($criteria);
        $idrol = $roles[0][0];
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

    protected function validarFechaInicioAsistencia($pfechainicio, $pidproyecto) {
        $proyecto = $this->loadModel($pidproyecto);
        $fechainicio = $this->FechaPhptoMysql($pfechainicio);

        $proyectoini = $proyecto->periodos->inicio;
        $proyectofin = $proyecto->periodos->fin;

        if (strtotime($proyectoini) > strtotime($fechainicio)) {
            return false;
        } else if (strtotime($proyectofin) < strtotime($fechainicio)) {
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
