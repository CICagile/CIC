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
                    'ValidarAgregarAsistente', 'adminantiguos', 'verantiguos', 'ampliarproyecto', 'agregarInvestigador', 'investigadorAutoComplete', 'cancelarproyecto'),
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
        $model->scenario = 'ver';
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
        $model->scenario = 'editar-asistentes';

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

        $this->render('ver', array(
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
        $modelperiodos = new Periodos;
        
        if ($modelproyectos === null)
            throw new CHttpException(404, 'La página solicitado no se ha encontrado.');

        $modelproyectos->scenario = 'actualizarproyecto';

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'proyectos-formactualizar' 
                && isset($_POST['Proyectos']['idtbl_sectorbeneficiado'])) {

            //$modelproyectos->idtbl_sectorbeneficiado = $_POST['Proyectos']['idtbl_sectorbeneficiado'];
            /*if(!isset($_POST['Proyectos']['idtbl_sectorbeneficiado']))
                unset($modelproyectos->idtbl_sectorbeneficiado);*/
                
            if ($modelproyectos->codigo == $_POST['Proyectos']['codigo']) {//Para este caso no procedo a validar el codigo del proyecto
                echo CActiveForm::validate(array($modelproyectos, $modelperiodos), array('nombre', 'idtbl_objetivoproyecto', 'tipoproyecto', 'idtbl_adscrito', 'estado', 'idtbl_sectorbeneficiado', 'inicio', 'fin'));
            }
            else
                echo CActiveForm::validate(array($modelproyectos, $modelperiodos));
            
            Yii::app()->end();
        }

        if (isset($_POST['Proyectos'])) {
            $modelproyectos->attributes = $_POST['Proyectos'];
            $result = $modelproyectos->save(false);
            if ($result) {
                $result_sectores = ProyectosSectorbeneficiado::updateBenefitedSectors(
                        $modelproyectos->idtbl_Proyectos, $antiguos_sectores, $modelproyectos->idtbl_sectorbeneficiado);
                
                $result_periodos = $modelproyectos->actualizarFechasProyecto($modelproyectos->idtbl_Proyectos,
                        $_POST['Periodos']['inicio'],
                        $_POST['Periodos']['fin']);
                
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
     * Manages all models.
     */
    public function actionAdmin() {
        // Create filter model and set properties
        $filtersForm = new FiltersForm;
        $atributos=array(5);
        $dataProvider = new CArrayDataProvider(array());
        
        if (isset($_GET['FiltersForm']))
            $filtersForm->filters = $_GET['FiltersForm'];
        $atributos = $filtersForm->getAttributes();
        $long = sizeof($atributos['filters']);
        if ($long == 0)
            $modelos = Proyectos::model()->obtenerProyectosActivos(NULL);
        else if ($atributos['filters']['sectorbeneficiado'] == "")
            $modelos = Proyectos::model()->obtenerProyectosActivos(NULL); 
        else
            $modelos = Proyectos::model()->obtenerProyectosActivos($atributos['filters']['sectorbeneficiado']);
        if (!$modelos == null) {
            $filteredData = $filtersForm->filter($modelos);
            $dataProvider = new CArrayDataProvider($filteredData, array(
                        'keyField' => 'idtbl_Proyectos',
                        'id' => 'idtbl_Proyectos',
                        'sort' => array(
                            'attributes' => array(
                                'idtbl_Proyectos',
                               // 'codigo',
                                'nombre',
                                'inicio',
                                'fin',
                                'sectorbeneficiado',
                                
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
            throw new CHttpException(404, 'La página solicitaao no se ha encontrado.');

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
        $asistente = new Asistente;
        $periodo = new Periodos;
        
        $asistente->scenario = 'agregar';

        if (isset($_POST['Proyectos']) && isset($_POST['Asistente']) && isset($_POST['Periodos'])) {
            $asistente->attributes = $_POST['Asistente'];
            $asistente->codigo = $model->codigo;
            $periodo->attributes = $_POST['Periodos'];
            $horas_nuevas = $asistente->horas;
            $asistente->horas = 0;
            if (!$asistente->validarActualizacionDeHoras($horas_nuevas))
                $asistente->horas = $horas_nuevas;
            $periodo->validarFechaInicioAsistencia($model->codigo);
            $periodo->validarFechaFinAsistencia($model->codigo);
            if ($asistente->validate(NULL,false) && $periodo->validate(NULL, false)) {
                if ($model->agregarAsistenteProyecto($model->idtbl_Proyectos, $asistente->carnet, $asistente->rol, $periodo->inicio, $periodo->fin, $asistente->horas))
                        $this->redirect(array('ver','id'=>$model->idtbl_Proyectos));
                else
                        throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
            }//fin si los modelos son validos
        }//fin si hizo el POST

        $this->render('agregarasistente', array(
            'model' => $model,
            'asistente' => $asistente,
            'periodo' => $periodo,
        ));
    }
    
    /**
     * Agrega un investigador a un proyecto.
     * @param int $id PK del proyecto en la BD.
     */
    public function actionAgregarInvestigador($id){
        $model = Proyectos::model()->obtenerProyectoconPeriodoActual($id);
        $investigador = new Investigador;
        $periodo = new Periodos;
        $investigador->scenario = 'agregar-investigador';
        $datos_horas = array();
        
        if (isset($_POST['Proyectos']) && isset($_POST['Investigador']) && isset($_POST['Periodos']) && isset($_POST['formhoras'])){
            $horas = array();
            $datos_horas = $_POST['formhoras']['formhoras'];
            $investigador->attributes = $_POST['Investigador'];
            $periodo->attributes = $_POST['Periodos'];
            foreach ($datos_horas as $dato)
            {
                if (array_key_exists($dato['tipo_horas'],$horas))
                {
                    $investigador->addError('horas',  'El tipo de horas no se puede repetir.');//TODO: Esto se debería validar dentro del modelo.
                    break;
                }//fin si el tipo de horas se repite.
                else
                {
                    $horas[$dato['tipo_horas']] = $dato['cantidad_horas'];
                }//fin si el tipo de horas no se repite
            }//fin for
            $investigador->horas = $horas;
            $periodo->validarFechaInicioAsistencia($model->codigo);
            $periodo->validarFechaFinAsistencia($model->codigo);
            if($investigador->validate(NULL,false) && $periodo->validate(NULL,false)){
                if ($model->agregarInvestigadorProyecto($model->codigo, $investigador->cedula, $investigador->rol, $periodo->inicio, $periodo->fin, $investigador->horas))
                        $this->redirect(array('ver','id'=>$model->idtbl_Proyectos));
                else
                            throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
            }
        }//fin si hizo click en el boton Agregar
        
        $this->render('agregarinvestigador', array(
            'model' => $model,
            'investigador' => $investigador,
            'periodo' => $periodo,
            'horas' => array_values($datos_horas),
        ));
    }//fin agregar investigador

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
    
    /**
         * Busca el codigo de la variable GET en la BD para autocompletar un campo de texto. 
         */
        public function actionInvestigadorAutoComplete()
        {
            if (isset($_GET['term'])) {
                $conexion = Yii::app()->db;
                $call = 'CALL buscarinvestigadorPorCedula2(:ced)';
                $comando = $conexion->createCommand($call);
                $comando->bindParam(':ced',$_GET['term'],PDO::PARAM_STR);
                $result_set = $comando->query();
                $investigadores = $result_set->readAll();
                $return_array = array();
                foreach($investigadores as $investigador) {
                    $return_array[] = array(
                        'label'=>$investigador['nombre'],
                        'value'=>$investigador['cedula'],
                    );
                }
                echo CJSON::encode($return_array);
            }
        }//fin investigador autocomplete

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
