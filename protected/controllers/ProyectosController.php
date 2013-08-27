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
                    'ValidarAgregarAsistente', 'adminantiguos', 'verantiguos', 'ampliarproyecto', 'agregarInvestigador', 'investigadorAutoComplete',
                    'cancelarproyecto', 'editarasistencia'),
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


    /*protected function FechaPhptoMysql($pfechaphp) {
        try {
            list($d, $m, $y) = explode('-', $pfechaphp);
            $nuevafecha = mktime(0, 0, 0, $m, $d, $y);
            $fechamysql = strftime('%Y-%m-%d', $nuevafecha);
        } catch (Exception $e) { //El catch no ejecuta ninguna funcion porque las excepciones son manejas por el CErrorHandler de Yii.                
        }
        return $fechamysql;
    }*/

    /* public function FechaMysqltoPhp($pfechamysql) {
      try {
      $fecha = substr($pfechamysql, 0, 10);
      list($y, $m, $d) = explode('-', $fecha);
      $fecha = $d . '-' . $m . '-' . $y;
      } catch (Exception $e) {

      }
      return $fecha;
      } */

    /*
     * Crea un proyecto
     */

    public function actionCrear() {
        $modelproyectos = new Proyectos;
        $modelperiodos = new Periodos;
        $modelProyectosXSector = new ProyectosSectorbeneficiado;
        $historialproyectoperiodo = new HistorialProyectosPeriodo();

        $modelproyectos->scenario = 'crearproyecto'; //Activo el escenario para las reglas de validacion especificas

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'proyectos-formcrear') {
            echo CActiveForm::validate(array($modelproyectos, $modelperiodos));
            Yii::app()->end();
        }

        if (isset($_POST['Periodos']) && isset($_POST['Proyectos'])) {

            $modelperiodos->attributes = $_POST['Periodos'];
            $modelproyectos->attributes = $_POST['Proyectos'];

            if ($modelperiodos->validate() && $modelproyectos->validate()) {
                $transaction = Yii::app()->db->beginTransaction();

                //originalmente, un estado de 0 significaba aprobado         
                $resultado = $modelproyectos->save();
                $resultado_sector = $modelProyectosXSector->saveAllBenefitedSectors(
                        $modelproyectos->idtbl_Proyectos, $modelproyectos->idtbl_sectorbeneficiado);
                $resultado_historial = $historialproyectoperiodo->agregarHistorialAProyecto(
                        $modelproyectos->idtbl_Proyectos, $modelperiodos->inicio, $modelperiodos->fin, $modelproyectos->CODIGO_APROBADO);
                if ($resultado && $resultado_sector && $resultado_historial) {
                    $transaction->commit();
                    Yii::log("Creación exitosa del proyecto con el código: "
                            . $modelproyectos->codigo, "info", "application.controllers.ProyectosController");
                    $this->redirect(array('admin'));
                } else {
                    $transaction->rollBack();
                    Yii::log("Rollback al intentar crear el proyecto con el código: "
                            . $modelproyectos->codigo . " IdProyecto:"
                            . $historialproyectoperiodo->idtbl_Proyectos . "-"
                            . $modelproyectos->idtbl_Proyectos, "warning", "application.controllers.ProyectosController");
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
            /* if(!isset($_POST['Proyectos']['idtbl_sectorbeneficiado']))
              unset($modelproyectos->idtbl_sectorbeneficiado); */

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

                $result_periodos = $modelproyectos->actualizarFechasProyecto($modelproyectos->idtbl_Proyectos, $_POST['Periodos']['inicio'], $_POST['Periodos']['fin']);

                if ($result_sectores && $result_periodos) {
                    Yii::log("Cambio exitoso de la información del proyecto: " . $modelproyectos->codigo, "info", "application.
    controllers.ProyectosController");
                    $this->redirect(array('ver', 'id' => $modelproyectos->idtbl_Proyectos));
                } else {
                    throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                }
            } else {
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
        $atributos = array(5);
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
            throw new CHttpException(404, 'La página solicitada no se ha encontrado.');

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

                $historialproyectosperiodo = new HistorialProyectosPeriodo;
                $result_periodo = $historialproyectosperiodo->
                        agregarHistorialAProyecto(
                                $modelproyectos->idtbl_Proyectos, 
                                date("d-m-Y"), //debe realmente ser la fecha en la que se amplio el proyecto?
                                $fecha_ampliacion, 
                                Proyectos::$CODIGO_AMPLIADO);

                $modelproyectos->actualizarEstadoProyecto(
                        $modelproyectos->idtbl_Proyectos, Proyectos::$CODIGO_AMPLIADO);
                $modelproyectos->estado = Proyectos::$CODIGO_AMPLIADO;

                $result_estado = $modelproyectos->save(false);



                if ($result && $result_periodo && $result_estado) {
                    $transaction->commit();
                    Yii::log("Exito ampliando el proyecto con el codigo: " . $modelproyectos->codigo, "info", "application.
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
        if($modelproyectos->estado == Proyectos::$CODIGO_CANCELADO){
            throw new CHttpException(403, 'Acción no permitida: el proyecto ya ha sido cancelado');
        }

        if (Yii::app()->request->isAjaxRequest && isset($_POST["cancelacion"])
                && isset($_POST["detalle_motivo"])) {

            if (trim($_POST["cancelacion"]) == NULL) {
                $response = array(
                    'ok' => false,
                    'msg' => 'Debe seleccionar la fecha de cancelación.',
                );
            } else if (trim($_POST["detalle_motivo"]) == NULL) {
                $response = array(
                    'ok' => false,
                    'msg' => 'Debe indicar un motivo para la cancelación.',
                );
            } else {
                $fecha_cancelacion = $_POST["cancelacion"] . '';
                $motivo_cancelacion = $_POST["detalle_motivo"];

                if ($modelproyectos->cancelarProyecto($id, $fecha_cancelacion, $motivo_cancelacion)) {


                    $response = array(
                        'ok' => true,
                        'msg' => "Proyecto cancelado exitosamente", //$fecha_cancelacion . ' ' . $motivo_cancelacion,
                    );
                } else {
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
    
// <editor-fold defaultstate="collapsed" desc="Editar Asistencia">
    /**
     * Agrega un nuevo periodo en el que el asistente puede llevar un nuevo rol.
     * @param Periodos $pPeriodo Periodo en que inicia el nuevo rol. La fecha de fin no cambia.
     * @param Asistente $pAsistente Asistente al que se le hace el cambio.
     * @param Proyectos $pProyecto Proyecto en el que está el asistente.
     */
    private function cambiarRolAsistente($pPeriodo, $pAsistente, $pProyecto) {
        $pPeriodo->validarFechaInicioAsistencia($pProyecto->codigo);
        if ($pAsistente->rol === '')
            $pAsistente->addError ('rol', 'Tiene que elegir un rol.');
        if ($pPeriodo->validate(NULL, FALSE) && $pAsistente->validate('rol',FALSE))
            if (!$pAsistente->actualizarRolProyecto($pPeriodo->inicio,$pProyecto->idtbl_Proyectos))
                    throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
    }//fin cambiar rol asistente
    
    /**
     * Agrega un nuevo periodo para las nuevas horas que hace el asistente.
     * Se realizan las validaciones necesarias.
     * @param Periodos $pPeriodo Periodo en que cumple con las horas. La fecha de fin no cmabia.
     * @param Asistente $pAsistente Asistente al que se le hace el cambio.
     * @param Proyectos $pProyecto Proyecto en el que está el asistente.
     * @param double $pHoras Horas nuevas que cumplirá el asistente
     */
    private function cambiarHorasAsistente($pPeriodo, $pAsistente, $pProyecto, $pHoras){
        $pPeriodo->validarFechaInicioAsistencia($pProyecto->codigo);
        if ($pHoras == null)
            $pAsistente->addError ('horas', 'Las horas no pueden ser nulas');
        if($pPeriodo->validate(NULL,FALSE) && $pAsistente->validarActualizacionDeHoras($pHoras))
            if ($pAsistente->actualizarHorasProyecto($pProyecto->idtbl_Proyectos, $pPeriodo->inicio))
                $this->redirect (array('ver','id'=>$pProyecto->idtbl_Proyectos));
            else
                throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
    }//fin cambiar horas asistente
    
    /**
     * Cambia el periodo de asistencia de cierto asistente. Tambien corrije los otros periodos
     * involucrados. Realiza las validaciones necesarias.
     * @param Periodos $pPeriodo Periodo de la asistencia nueva.
     * @param Asistente $pAsistente Asistente al que se le hace el cambio.
     * @param Proyectos $pProyecto Proyecto en el que está el asistente.
     */
    private function cambiarPeriodoAsistencia($pPeriodo,$pAsistente,$pProyecto){
        $pPeriodo->validarFechaInicioAsistencia($pProyecto->codigo);
        $pPeriodo->validarFechaFinAsistencia($pProyecto->codigo);
        if ($pPeriodo->validate(NULL, false))
            if ($pAsistente->cambiarPeriodoAsistencia($pProyecto->idtbl_Proyectos, $pPeriodo->inicio, $pPeriodo->fin))
                $this->redirect(array('editarasistencia','id'=>$pProyecto->idtbl_Proyectos,'carnet'=>$pAsistente->carnet));
            else
                throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.'); 
    }//fin cambiar horas asistente
    
    /**
     * Realiza las siguientes validaciones:
     * Verificar que no se cambie la fecha de inicio del primer período.
     * Verificar contra la fecha de fin del periodo
     * Verificar contra la fecha de fin del proyecto
     * Verificar traslapaciones
     *  -inicio nuevo no puede ser menor o  igual a inicio de periodo anterior (periodo con fecha fin igual a este inico).
     * @param Periodos $pPeriodo Periodo que se está verificando.
     * @param Proyectos $pProyecto Proyecto en que se encuentra el asistente.
     * @param Periodos $pAsistencia El periodo en que el asistente cumple la asistencia.
     * @param Periodos $pAnterior Fechas del periodo antes de que se cambiaran.
     */
    private function validarCambioInicioPeriodo($pPeriodo, $pProyecto, $pAsistencia, $pAnterior){
        //Valida que no se cambie la fecha del primer periodo
        if ($pAsistencia->inicio == $pPeriodo->inicio) {
            $pPeriodo->addError('inicio', 'No se puede cambiar esta fecha porque el inicio coincide con el inicio de la asistencia.');
        }//fin si es el primer periodo
        //Valida que los periodos no se traslapen.
        $periodo = new Periodos;
        $periodo->inicio = $pAnterior->inicio;
        $periodo->fin = $pPeriodo->inicio;
        $periodo->validate();
        if ($periodo->hasErrors())
            $pPeriodo->addError('inicio','Hay conflicto con otros periodos.');
        //Valida contra la fecha del proyecto.
        $pPeriodo->validarFechaInicioAsistencia($pProyecto->codigo);
        //Valida contra las fechas del periodo.
        $pPeriodo->validate(NULL,FALSE);
    }//fin validar cambio de inicio del periodo.

    public function actionEditarAsistencia($id, $carnet) {
        new Periodos; //Elimina un error en la funcion buscar datos actuales... Sin esto, esa funcion no puede instanciar periodos.
        $model = Proyectos::model()->obtenerProyectoconPeriodoActual($id);
        $asistente = new Asistente();
        $asistente->carnet = $carnet;
        $periodos = $asistente->buscarDatosActualesAsistenteEnProyecto($id);
        if ($periodos === NULL)
            throw new CHttpException(404, 'No se encontró al asistente en ese proyecto.');
        if (isset($_POST['Rol']) && isset($_POST['Asistente'])) {
            if (isset($_POST['correccion'])){
                $anterior = new Periodos;
                $anterior->inicio = $periodos['rol']->inicio;
                $periodos['rol']->inicio = $_POST['Rol']['inicio'];
                $this->validarCambioInicioPeriodo($periodos['rol'], $model, $periodos['asistencia'], $anterior);
                if(!$periodos['rol']->hasErrors()){
                    if ($asistente->corregirFechaInicioRolAsistente($periodos['rol']->inicio)){
                        ;
                    }
                    else {
                        throw new CHttpException(500, 'Ha ocurrido un error interno, vuelva a intentarlo.');
                    }//fin si fallo la transaccion
                }//fin si no hay errores
            }//fin si es sólo corregir fecha de inicio
            else {
                if ($asistente->rol == $_POST['Asistente']['rol'])
                    $asistente->addError ('rol', 'Rol no cambió.');
                else if ($periodos['rol']->inicio == $_POST['Rol']['inicio'])
                    $periodos['rol']->addError ('inicio', 'La fecha de inicio no cambió.');
                else {
                    $asistente->rol = $_POST['Asistente']['rol'];
                    $periodos['rol']->inicio = $_POST['Rol']['inicio'];
                    $this->cambiarRolAsistente($periodos['rol'],$asistente,$model);
                }//fin si el usuario eligió un nuevo rol.
            }//fin si es agregar nuevo periodo
        }//fin si cambia el periodo del rol
        else if (isset($_POST['Asistencia'])) {
            $periodos['asistencia']->attributes = $_POST['Asistencia'];
            $this->cambiarPeriodoAsistencia($periodos['asistencia'],$asistente,$model);
        }//fin si cambia el periodo de asistencia
        else if (isset($_POST['Horas'])) {
            if (isset($_POST['correccion']))
                $periodos['horas']->addError('inicio', '¡Sólo corregir período no implementado!');
            else {
                if ($asistente->horas == $_POST['Asistente']['horas'])
                    $asistente->addError ('horas', 'No cambiaron las horas.');
                else if ($periodos['horas']->inicio == $_POST['Horas']['inicio'])
                    $periodos['horas']->addError ('inicio', 'La fecha de inicio no cambió.');
                else {
                    $periodos['horas']->inicio = $_POST['Horas']['inicio'];
                    $this->cambiarHorasAsistente($periodos['horas'], $asistente, $model, $_POST['Asistente']['horas']);
                }//fin si es agregar nuevo periodo
            }//fin si las cambia el periodo de las horas.
        }//fin si cambia el periodo de las horas

        $this->render('editarasistencia', array(
            'model' => $model,
            'asistente' => $asistente,
            'periodos' => $periodos,
        ));
    }//fin accion editar asistencia
// </editor-fold>

    
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
            if ($asistente->validate(NULL, false) && $periodo->validate(NULL, false)) {
                if ($model->agregarAsistenteProyecto($model->idtbl_Proyectos, $asistente->carnet, $asistente->rol, $periodo->inicio, $periodo->fin, $asistente->horas))
                    $this->redirect(array('ver', 'id' => $model->idtbl_Proyectos));
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
    public function actionAgregarInvestigador($id) {
        $model = Proyectos::model()->obtenerProyectoconPeriodoActual($id);
        $investigador = new Investigador;
        $periodo = new Periodos;
        $investigador->scenario = 'agregar-investigador';
        $datos_horas = array();

        if (isset($_POST['Proyectos']) && isset($_POST['Investigador']) && isset($_POST['Periodos']) && isset($_POST['formhoras'])) {
            $horas = array();
            $datos_horas = $_POST['formhoras']['formhoras'];
            $investigador->attributes = $_POST['Investigador'];
            $periodo->attributes = $_POST['Periodos'];
            foreach ($datos_horas as $dato) {
                if (array_key_exists($dato['tipo_horas'], $horas)) {
                    $investigador->addError('horas', 'El tipo de horas no se puede repetir.'); //TODO: Esto se debería validar dentro del modelo.
                    break;
                }//fin si el tipo de horas se repite.
                else {
                    $horas[$dato['tipo_horas']] = $dato['cantidad_horas'];
                }//fin si el tipo de horas no se repite
            }//fin for
            $investigador->horas = $horas;
            $periodo->validarFechaInicioAsistencia($model->codigo);
            $periodo->validarFechaFinAsistencia($model->codigo);
            if ($investigador->validate(NULL, false) && $periodo->validate(NULL, false)) {
                if ($model->agregarInvestigadorProyecto($model->codigo, $investigador->cedula, $investigador->rol, $periodo->inicio, $periodo->fin, $investigador->horas))
                    $this->redirect(array('ver', 'id' => $model->idtbl_Proyectos));
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
    }

//fin agregar investigador

// <editor-fold defaultstate="collapsed" desc="Autocomplete">
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
    public function actionInvestigadorAutoComplete() {
        if (isset($_GET['term'])) {
            $conexion = Yii::app()->db;
            $call = 'CALL buscarinvestigadorPorCedula2(:ced)';
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':ced', $_GET['term'], PDO::PARAM_STR);
            $result_set = $comando->query();
            $investigadores = $result_set->readAll();
            $return_array = array();
            foreach ($investigadores as $investigador) {
                $return_array[] = array(
                    'label' => $investigador['nombre'],
                    'value' => $investigador['cedula'],
                );
            }
            echo CJSON::encode($return_array);
        }
    }

//fin investigador autocomplete


    /**
     * Busca el codigo de la variable GET en la BD para autocompletar un campo de texto. 
         */
    public function actionInvestigadorAutoComplete()         {
        if (isset($_GET['term'])) {
            $conexion = Yii::app()->db;
            $call = 'CALL buscarinvestigadorPorCedula2(:ced)';
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':ced', $_GET['term'], PDO::PARAM_STR);
            $result_set = $comando->query();
            $investigadores = $result_set->readAll();
            $return_array = array();
            foreach ($investigadores as $investigador) {
                $return_array[] = array(
                    'label' => $investigador['nombre'],
                    'value' => $investigador['cedula'],
                );
            }
            echo CJSON::encode($return_array);
        }
    }

//fin investigador autocomplete// </editor-fold>


// <editor-fold defaultstate="collapsed" desc="AJAX">
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

// </editor-fold>


}
