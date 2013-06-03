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
                'actions' => array('ver', 'ActualizarInfoAsistentes', 'crear', 'actualizar', 'agregarasistente', 'AsistenteAutoComplete',
                    'ValidarAgregarAsistente', 'adminantiguos', 'verantiguos', 'ampliarproyecto', 'cancelarproyecto'),
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

    /*
     * Muestra el detalle de un proyecto activo
     */

    public function actionVer($id) {
        $model = Proyectos::model()->obtenerProyectoconPeriodoActual($id);
        //Proyectos::model()->obtenerSectoresBeneficiadosConFormato();
        if ($model === null)
            throw new CHttpException(404, 'La página solicitado no se ha encontrado.');
        else
            $this->render('ver', array(
                'model' => $model,
                'dataProvider' => $model->buscarAsistentesActivosDeProyecto(),
            ));
    }

    /**
     * Muestra el detalle de un proyecto antiguo
     */

    public function actionVerAntiguos($id) {
        $model = Proyectos::model()->obtenerProyectoconPeriodoActual($id);
        if ($model === null)
            throw new CHttpException(404, 'La página solicitado no se ha encontrado.');
        else
            $this->render('verantiguos', array(
                'model' => $model,
                'asistente' => new Asistente,
                'dataProvider' => $model->buscarAsistentesActivosDeProyecto(),
            ));
    }
    
    /**
     * Verifica que los nuevos datos de los asistentes sean válidos y guarda los cambios.
     * @param string $pRol El rol del asistente en el proyecto
     * @param double $pHoras Horas que el asistente debe dedicar semanalmente al proyecto.
     * @param fecha $pFin La fecha de finalización de la asistencia.
     */
    public function actionActualizarInfoAsistentes($id) {
        $model = Proyectos::model()->obtenerProyectoconPeriodoActual($id);
        $dataProvider = $model->buscarAsistentesActivosDeProyecto();
        $errores = array();

        if (isset($_POST['horas']) && isset($_POST['rol']) && isset($_POST['fin'])) {
            $datos_asistentes = $dataProvider->data;
            $horas = $_POST['horas'];
            $roles = $_POST['rol'];
            $fechas_fin = $_POST['fin'];
            $errores = $this->actualizarCadaAsistente($model, $datos_asistentes, $horas, $roles, $fechas_fin);
            if (empty($errores))
                $this->redirect(array('ver', 'id' => $id)); //Sólo llega a esta instrucción si no hay errores en los datos.
            else
                $dataProvider = $model->buscarAsistentesActivosDeProyecto(); //vuelve a cargar los datos desde la base en caso de que algunos datos sí se hayan actualizado.
        }//fin si asistente se modificó.

        $this->render('viewEditable', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
            'errores' => $errores,
        ));
    }

//fin actualizar informacion de los assitentes activos del proyecto.

    /**
     * Actualiza el periódo del fin de la asistencia de un asistente en un proyecto.
     * @param Periodos $pAsistencia El periodo de asistencia actual del asistente.
     * @param string $pFecha Fecha por la que se quiere cambiar el fin del periodo
     * @param Proyectos $pProyecto Proyecto al que pertenece el asistente.
     * @param Asistente $pAsistente Asistente al que se quiere cambiar los datos.
     * @throws CHttpException Redirige al error 500 si algo sucede mal con la conexión a la base de datos.
     */
    private function actualizarFinAsistencia($pAsistencia, $pFecha, $pProyecto, $pAsistente) {
        if ($pAsistencia->validarActualizacionFechaFinAsistencia($pFecha, $pProyecto)) {
            if (!$pAsistente->cambiarFinAsistencia($pAsistencia->fin))
                throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
        }//fin si la nueva fecha es válida
    }

//fin actualizar fin asistencia

    /**
     * Actualiza, si es posible, las horas que un asistente dedica a un proyecto
     * @param Asistente $pAsistente Asistente al que se le actualizan las horas.
     * @param double $pHoras Cantidad de horas nuevas.
     * @throws CHttpException Redirige al error 500 si algo sucede mal con la conexión a la base de datos.
     */
    private function actualizarHorasAsistencia($pAsistente, $pHoras) {
        if ($pAsistente->validarActualizacionDeHoras($pHoras)) {
            if (!$pAsistente->cambiarHorasAsistencia())
                throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
        }//fin si las horas nuevas son válidas
    }

//fin actualizar horas asistencia

    /**
     * Actualiza, si es posible, el rol del asistente.
     * @param string $pRolNuevo El rol que se le quiere asignar al asistente.
     * @param Asistente $pAsistente El asistente al que se le cmabian los datos.
     * @throws CHttpException Redirige al error 500 si algo sucede mal con la conexión a la base de datos.
     */
    private function actualizarRolAsistente($pRolNuevo, $pAsistente) {
        $pAsistente->rol = $pRolNuevo;
        if ($pAsistente->validate('rol', false)) {
            if (!$pAsistente->cambiarRolProyecto())
                throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
        }//fin si el rol es válido
    }

//fin actualizar rol asistente

    /**
     * Valida y actualiza a todos los asistentes del proyecto.
     * @param Proyecto $pProyecto Proyecto al que se le actualizan los asistentes.
     * @param array $pDatos Datos cargados de la base de datos sobre los asistentes.
     * @param array $pHoras Un array de las horas de cada asistente provenientes de un POST.
     * @param array $pRoles Un array de los roles de cada asistente provenientes de un POST.
     * @param array $pFechas Un array de las fechas de finalización de asistencia de cada asistente provenientes de un POST.
     * @return array Retorna un array con los modelos que contienen errores.
     */
    private function actualizarCadaAsistente($pProyecto, $pDatos, $pHoras, $pRoles, $pFechas) {
        $respuesta = array();
        foreach ($pDatos as $index => $datos_asistente) {
            $asistente = new Asistente;
            $asistente->scenario = 'actInfoProy';
            $asistente->attributes = $pDatos[$index];
            $asistente->codigo = $pProyecto->idtbl_Proyectos;
            $asistencia = new Periodos('cambiarAsistencia');
            $asistencia->inicio = $datos_asistente['inicio_asistencia'];
            $asistencia->fin = $datos_asistente['fin'];
            if ($asistencia->fin != $pFechas[$index]) {
                $this->actualizarFinAsistencia($asistencia, $pFechas[$index], $pProyecto, $asistente);
            }//fin si cambio la fecha del final de la asistencia
            if ($pHoras[$index] != $asistente->horas) {
                $this->actualizarHorasAsistencia($asistente, $pHoras[$index]);
            }//fin si las horas son diferentes
            if ($asistente->rol != $pRoles[$index]) {
                $this->actualizarRolAsistente($pRoles[$index], $asistente);
            }//fin si el rol del asistente cambió.
            if ($asistente->hasErrors() || $asistencia->hasErrors()) {
                $error = array('Asistente' => $asistente, 'Asistencia' => $asistencia);
                array_push($respuesta, $error);
            }//fin si algún modelo tiene errores.
        }//fin for
        return $respuesta;
    }

//fin actualizar cada asistente


    protected function FechaPhptoMysql($pfechaphp) {
        try {
            list($d, $m, $y) = explode('-', $pfechaphp);
            $nuevafecha = mktime(0, 0, 0, $m, $d, $y);
            $fechamysql = strftime('%Y-%m-%d', $nuevafecha);
        } catch (Exception $e) { //El catch no ejecuta ninguna funcion porque las excepciones son manejas por el CErrorHandler de Yii.                
        }
        return $fechamysql;
    }

    public function FechaMysqltoPhp($pfechamysql) {
        try {
            $fecha = substr($pfechamysql, 0, 10);
            list($y, $m, $d) = explode('-', $fecha);
            $fecha = $d . '-' . $m . '-' . $y;
        } catch (Exception $e) {
            
        }
        return $fecha;
    }

    /*
     * Crea un proyecto
     */

    public function actionCrear() {
        $modelproyectos = new Proyectos;
        $modelperiodos = new Periodos;
        $modelProyectosXSector = new ProyectosSectorbeneficiado;

        $modelproyectos->scenario = 'crearproyecto'; //Activo el escenario para las reglas de validacion especificas

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'proyectos-formcrear') {
            echo CActiveForm::validate(array($modelproyectos, $modelperiodos));
            Yii::app()->end();
        }

        if (isset($_POST['Periodos']) && isset($_POST['Proyectos'])) {

            $modelperiodos->attributes = $_POST['Periodos'];
            $modelproyectos->attributes = $_POST['Proyectos'];

            if ($modelperiodos->validate() && $modelproyectos->validate()) {
                $modelperiodos->inicio = $this->FechaPhptoMysql($modelperiodos->inicio);
                $modelperiodos->fin = $this->FechaPhptoMysql($modelperiodos->fin);

                $transaction = Yii::app()->db->beginTransaction();

                $resultado = $modelperiodos->save(false); //Guardo el periodo sin validar, ya que lo valide con anterioridad                                                           
                //El proyecto cuando se crea por Default es aprobado -> 0           
                $resultado = $resultado ? $modelproyectos->save() : $resultado;


                if ($resultado) {//Si se guarda bien el proyecto
                    //almacena los sectores beneficiados
                    $resultado_sector = $modelProyectosXSector->saveAllBenefitedSectors(
                            $modelproyectos->idtbl_Proyectos, $modelproyectos->idtbl_sectorbeneficiado);
                            
                    if ($resultado_sector) {
                        //Guardo el historial de periodos del proyecto
                        $historialproyectoperiodo = new HistorialProyectosPeriodo();
                        $historialproyectoperiodo->idPeriodo = $modelperiodos->idPeriodo;
                        $historialproyectoperiodo->idtbl_Proyectos = $modelproyectos->idtbl_Proyectos;

                        $resultado = $resultado ? $historialproyectoperiodo->save() : $resultado;

                        if ($resultado) {//Si se guarda bien el historial de periodos del proyecto
                            $transaction->commit();
                            Yii::log("Creación exitosa del proyecto con el código: " . $modelproyectos->codigo, "info", "application.
    controllers.ProyectosController");
                            $this->redirect(array('admin'));
                        } else {
                            $transaction->rollBack();
                            Yii::log("Rollback al intentar crear el proyecto con el código: " . $modelproyectos->codigo, "warning", "application.
    controllers.ProyectosController");
                            throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                        }
                    } else { //-
                        $transaction->rollBack();
                        Yii::log("Rollback. Error al almacenar el sector: " . $modelproyectos->codigo . "-" . $modelProyectosXSector->idtbl_sectorbeneficiado . "+" . $modelProyectosXSector->idtbl_Proyectos, "warning", "application.
    controllers.ProyectosController");
                        throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                    }//-
                } else {
                    $transaction->rollBack();
                    Yii::log("Rollback al intentar crear el proyecto con el código: " . $modelproyectos->codigo . "-" . $modelProyectosXSector->idtbl_sectorbeneficiado . "+" . $modelProyectosXSector->idtbl_Proyectos, "warning", "application.
    controllers.ProyectosController");
                    throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                }
            }
        }

        $this->render('crear', array(
            'modelproyectos' => $modelproyectos,
            'modelperiodos' => $modelperiodos,
        ));
    }

    /*
     * Actualizar un proyecto
     */

    public function actionActualizar($id) {
        $modelproyectos = Proyectos::model()->obtenerProyectoconPeriodoActual($id);
        $antiguos_sectores = $modelproyectos->idtbl_sectorbeneficiado;
        
        if ($modelproyectos === null)
            throw new CHttpException(404, 'La página solicitado no se ha encontrado.');

        $modelproyectos->scenario = 'actualizarproyecto';

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'proyectos-formactualizar' 
                && isset($_POST['Proyectos']['idtbl_sectorbeneficiado'])) {

            //$modelproyectos->idtbl_sectorbeneficiado = $_POST['Proyectos']['idtbl_sectorbeneficiado'];
            /*if(!isset($_POST['Proyectos']['idtbl_sectorbeneficiado']))
                unset($modelproyectos->idtbl_sectorbeneficiado);*/
                
            if ($modelproyectos->codigo == $_POST['Proyectos']['codigo']) {//Para este caso no procedo a validar el codigo del proyecto
                echo CActiveForm::validate($modelproyectos, array('nombre', 'idtbl_objetivoproyecto', 'tipoproyecto', 'idtbl_adscrito', 'estado', 'idtbl_sectorbeneficiado'));
            }
            else
                echo CActiveForm::validate($modelproyectos);
            
            Yii::app()->end();
        }

        if (isset($_POST['Proyectos'])) {
            $modelproyectos->attributes = $_POST['Proyectos'];
            $result = $modelproyectos->save(false);
            if ($result) {
                $result_sectores = ProyectosSectorbeneficiado::updateBenefitedSectors(
                        $modelproyectos->idtbl_Proyectos, $antiguos_sectores, $modelproyectos->idtbl_sectorbeneficiado);
                
                $result_periodos = $modelproyectos->actualizarFechasProyecto($modelproyectos->idtbl_Proyectos,
                        $_POST['fecha_inicio'],
                        $_POST['fecha_fin']);
                
             if($result_sectores && $result_periodos){   
                Yii::log("Cambio exitoso de la información del proyecto: " . $modelproyectos->codigo, "info", "application.
    controllers.ProyectosController");
                $this->redirect(array('ver', 'id' => $modelproyectos->idtbl_Proyectos));
             }else {
                throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
            }
            }else{
                throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
            }
        }

        $this->render('actualizar', array(
            'modelproyectos' => $modelproyectos
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
     * Manages all models.
     */
    public function actionAdmin() {
        // Create filter model and set properties
        $filtersForm = new FiltersForm;
        $dataProvider = new CArrayDataProvider(array());

        if (isset($_GET['FiltersForm']))
            $filtersForm->filters = $_GET['FiltersForm'];

        $modelos = Proyectos::model()->obtenerProyectosActivos();

        if (!$modelos == null) {
            $filteredData = $filtersForm->filter($modelos);
            $dataProvider = new CArrayDataProvider($filteredData, array(
                        'keyField' => 'idtbl_Proyectos',
                        'id' => 'idtbl_Proyectos',
                        'sort' => array(
                            'attributes' => array(
                                'idtbl_Proyectos',
                                'codigo',
                                'nombre',
                                'inicio',
                                'fin',
                            ),
                        ),
                        'pagination' => array(
                            'pageSize' => 10,
                        ),
                    ));
        }
        // Render
        $this->render('admin', array(
            'filtersForm' => $filtersForm,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionAdminAntiguos() {
        // Create filter model and set properties
        $filtersForm = new FiltersForm;
        $dataProvider = new CArrayDataProvider(array());

        if (isset($_GET['FiltersForm']))
            $filtersForm->filters = $_GET['FiltersForm'];

        $modelos = Proyectos::model()->obtenerProyectosAntiguos();

        if (!$modelos == null) {
            $filteredData = $filtersForm->filter($modelos);
            $dataProvider = new CArrayDataProvider($filteredData, array(
                        'keyField' => 'idtbl_Proyectos',
                        'id' => 'idtbl_Proyectos',
                        'sort' => array(
                            'attributes' => array(
                                'idtbl_Proyectos',
                                'codigo',
                                'nombre',
                                'inicio',
                                'fin',
                                'estado',
                            ),
                        ),
                        'pagination' => array(
                            'pageSize' => 10,
                        ),
                    ));
        }
        // Render
        $this->render('adminantiguos', array(
            'filtersForm' => $filtersForm,
            'dataProvider' => $dataProvider,
        ));
    }
/**
 *
 * @param type $id
 * @throws CHttpException 
 */
    public function actionAmpliarProyecto($id) {
        $modelproyectos = Proyectos::model()->obtenerProyectoconPeriodoActual($id);
        if ($modelproyectos === null)
            throw new CHttpException(404, 'La página solicitado no se ha encontrado.');

        if (Yii::app()->request->isAjaxRequest && isset($_POST["ampliacion"])) {

            if (trim($_POST["ampliacion"]) == NULL) {
                $response = array(
                    'ok' => false,
                    'msg' => 'Debe seleccionar la fecha de ampliación.',
                );
            } else {
                $fecha_ampliacion = $_POST["ampliacion"];

                $transaction = Yii::app()->db->beginTransaction();
                $periodoantiguo = Periodos::model()->findByPk($modelproyectos->idperiodo);
                $periodoantiguo->fin = date("Y-m-d");
                $result = $periodoantiguo->save(false);

                if ($result) {

                    $periodoampliado = new Periodos();
                    $periodoampliado->inicio = $periodoantiguo->inicio;
                    $periodoampliado->fin = $this->FechaPhptoMysql($fecha_ampliacion);

                    $result = $periodoampliado->save(false);

                    if ($result) {

                        $historialproyectosperiodo = new HistorialProyectosPeriodo();
                        $historialproyectosperiodo->idtbl_Proyectos = $modelproyectos->idtbl_Proyectos;
                        $historialproyectosperiodo->idPeriodo = $periodoampliado->idPeriodo;

                        $result = $historialproyectosperiodo->save(false);

                        if ($result) {

                            //Cambiamos el estado del proyecto
                            $modelproyectos->actualizarEstadoProyecto(
                                    $modelproyectos->idtbl_Proyectos, $modelproyectos->CODIGO_AMPLIADO);
                            $modelproyectos->estado = $modelproyectos->CODIGO_AMPLIADO;

                            $result = $modelproyectos->save(false);

                            if ($result) {
                                $transaction->commit();
                                Yii::log("Exito ampliando el proyecto con el codigo: " . $modelproyectos->codigo . " con el periodo " . $periodoampliado->idPeriodo, "info", "application.
                                controllers.ProyectosController");
                                $response = array(
                                    'ok' => true,
                                    'msg' => 'El proyecto se ha ampliado con éxito.',
                                );
                                echo CJSON::encode($response);
                                Yii::app()->end();
                            } else {
                                $transaction->rollBack();
                                Yii::log("Rollback al intentar ampliar al proyecto con el codigo: " . $modelproyectos->codigo, "warning", "application.
                                controllers.ProyectosController");
                                $response = array(
                                    'ok' => false,
                                    'msg' => 'Ha ocurrido un inconveniente al intentar ampliar el proyecto.',
                                );
                                echo CJSON::encode($response);
                                Yii::app()->end();
                            }
                        } else {
                            $transaction->rollBack();
                            Yii::log("Rollback al intentar ampliar al proyecto con el codigo: " . $modelproyectos->codigo, "warning", "application.
                            controllers.ProyectosController");
                            $response = array(
                                'ok' => false,
                                'msg' => 'Ha ocurrido un inconveniente al intentar ampliar el proyecto.',
                            );
                            echo CJSON::encode($response);
                            Yii::app()->end();
                        }
                    } else {
                        $transaction->rollBack();
                        Yii::log("Rollback al intentar ampliar al proyecto con el codigo: " . $modelproyectos->codigo, "warning", "application.
                        controllers.ProyectosController");
                        $response = array(
                            'ok' => false,
                            'msg' => 'Ha ocurrido un inconveniente al intentar ampliar el proyecto.',
                        );
                        echo CJSON::encode($response);
                        Yii::app()->end();
                    }
                } else {
                    $transaction->rollBack();
                    Yii::log("Rollback al intentar ampliar al proyecto con el codigo: " . $modelproyectos->codigo, "warning", "application.
                    controllers.ProyectosController");
                    $response = array(
                        'ok' => false,
                        'msg' => 'Ha ocurrido un inconveniente al intentar ampliar el proyecto.',
                    );
                    echo CJSON::encode($response);
                    Yii::app()->end();
                }
            }

            echo CJSON::encode($response);
            Yii::app()->end();
        }

        $this->render('ampliar', array(
            'modelproyectos' => $modelproyectos
        ));
    }

    
    /**
     * Permite cancelar un proyecto, con las acciones correspondientes para los asistentes
     * e investigadores asociados
     * @param type $id corresponde al id del proyecto a cancelar
     * @throws CHttpException 
     */
     public function actionCancelarProyecto($id) {
        $modelproyectos = Proyectos::model()->obtenerProyectoconPeriodoActual($id);
        if ($modelproyectos === null)
            throw new CHttpException(404, 'La página solicitado no se ha encontrado.');

        if (Yii::app()->request->isAjaxRequest && isset($_POST["cancelacion"])
                && isset($_POST["detalle_motivo"])) {

            if (trim($_POST["cancelacion"]) == NULL) {
                $response = array(
                    'ok' => false,
                    'msg' => 'Debe seleccionar la fecha de cancelación.',
                );
            }else if(trim($_POST["detalle_motivo"]) == NULL){ 
                $response = array(
                    'ok' => false,
                    'msg' => 'Debe indicar un motivo para la cancelación.',
                );
            }else {
                $fecha_cancelacion = $_POST["cancelacion"] . '';
                $motivo_cancelacion = $_POST["detalle_motivo"];
                
                if($modelproyectos->cancelarProyecto($id, $fecha_cancelacion, $motivo_cancelacion)){
                        
                
                $response = array(
                                    'ok' => true,
                                    'msg' => "Proyecto cancelado exitosamente", //$fecha_cancelacion . ' ' . $motivo_cancelacion,
                                );
                }else{
                    $response = array(
                                    'ok' => false,
                                    'msg' => "Error al cancelar el proyecto",
                                );
                }
            }

            echo CJSON::encode($response);
            Yii::app()->end();
        }

        $this->render('cancelar', array(
            'modelproyectos' => $modelproyectos
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
        $model = Proyectos::model()->obtenerProyectoconPeriodoActual($id);

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

                $response = array('ok' => true, 'msg' => '');
                foreach ($responseform as $res) {
                    if (!$res['ok']) {
                        $response['ok'] = false;
                        $response['msg'] = $response['msg'] . "</br>-" . $res['msg'];
                    }
                }
            } else if ($action == 'validate_asistente') {
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
                if (isset($_POST['fecha_fin']) && isset($_POST['fecha_inicio']) && isset($_POST['codigo'])) {
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
        } else if (!$this->validarMediaHora($horas)) {
            $response = array(
                'ok' => false,
                'msg' => "Solo puede ingresar horas completas o con media hora.");
        } else if (!is_null($pcarne)) {//Esta validacion solo se ejecuta en submit action del form
            if ($this->validarCantidadHorasAcumuladas($horas, $pcarne) > MAX_VALUE) {
                $response = array(
                    'ok' => false,
                    'msg' => "La cantidad de horas que desea agregar a este Asistente excede la cantidad horas maximas permitidas (" . MAX_VALUE . ") en distintos proyectos del CIC por semana.");
            } else {
                $response = array(
                    'ok' => true,
                    'msg' => "Valido.");
            }
        } else {
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
        if ($fecha == '') {
            $response = array(
                'ok' => false,
                'msg' => "La fecha de inicio no puede estar en blanco.");
        } else if (!$this->validarFechaRangoAsistenciaProyecto($fecha, $codigo)) {
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

    protected function validarFechaFin($pfechafin, $pidproyecto, $pfechainicio) {
        $response = array();
        $codigo = trim($pidproyecto);
        if ($pfechafin == '') {
            $response = array(
                'ok' => false,
                'msg' => "La fecha de finalización no puede estar en blanco.");
        } else if (strtotime($pfechafin) <= strtotime($pfechainicio)) {
            $response = array(
                'ok' => false,
                'msg' => "La fecha de finalización de la asistencia no puede ser menor o igual que la fecha de inicio.");
        } else if (!$this->validarFechaRangoAsistenciaProyecto($pfechafin, $codigo)) {
            $response = array(
                'ok' => false,
                'msg' => "La fecha de finalizacion de asistencia seleccionada no cumple con el periodo del proyecto.");
        } else {
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

    protected function obtenerIdRol($prol) {
        $criteria = new CDbCriteria();
        $criteria->compare('nombre', $prol);
        $roles = RolAsistente::model()->findAll($criteria);
        $idrol = $prol;
        foreach ($roles as $rol) {
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

        $res = false;
        $model = new Proyectos();
        $model->idtbl_Proyectos = $idproyecto;
        $arraydataprovider = $model->buscarAsistentesActivosDeProyecto();
        $asistentes = $arraydataprovider->rawData;

        foreach ($asistentes as $asistente) {
            if ($asistente['carnet'] == $pcarne) {
                $res = true;
            }
        }

        return $res;

        /* Esta versión no valida si el asistente está activo por lo que no deja asociar a un asistente
         * que alguna vez estuvo activo, luego inactivo y luego se quiere reintegrar al proyecto. */
//        $asistente = Asistentes::model()->findByAttributes(array('carnet' => $pcarne));        
//        $proyectosasistente = $asistente->proyectos;
//        $res = false;
//        foreach ($proyectosasistente as $proy)
//        {
//            if($proy->idtbl_Proyectos == $idproyecto)
//            {
//                $res = true;
//            }
//        }             
    }

    protected function validarFechaRangoAsistenciaProyecto($pfecha, $pidproyecto) {
        $proyecto = Proyectos::model()->obtenerProyectoconPeriodoActual($pidproyecto);
        $fecha = $this->FechaPhptoMysql($pfecha);

        $proyectoini = $proyecto->inicio;
        $proyectofin = $proyecto->fin;

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

    protected function validarMediaHora($pnumber) {
        if (preg_match("/^[0-9]+(\.5)?$/", $pnumber))
            return true;
        else
            return false;
    }

    protected function validarCantidadHorasAcumuladas($phoras, $pcarne) {
        define('ZERO_VALUE', 0);
        $asistente = Asistentes::model()->findByAttributes(array('carnet' => $pcarne));
        if (!is_null($asistente)) {
            $horasacumuladas = $asistente->verificarHorasAcumuladasProyectos($asistente->idtbl_Asistentes);
            if (is_null($horasacumuladas)) {
                $phoras = ZERO_VALUE;
                return $phoras;
            } else {
                $phoras = $phoras + current($horasacumuladas);
                return $phoras;
            }
        } else {
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
