<?php

/**
 * Esta clase es la capa de persistencia que carga y guarda los datos de los asistentes.
 * Se crea una instancia de esta clase por cada estudiante que esté en un proyecto.
 * Si el mismo estudiante se encuentra en más de un proyecto al mismo tiempo se crean dos
 * instancias representando cada proyecto.
 *
 */
class Asistente  extends CModel{
    
    public $nombre;
    public $apellido1;
    public $apellido2;
    public $cedula;
    public $numerocuenta;
    public $cuentacliente;
    public $carnet;
    public $carrera;
    public $telefono;
    public $correo;
    public $banco;
    public $codigo;
    public $rol;
    public $horas;

    /**
     * Funciones requeridas por Yii. 
     */
    // <editor-fold defaultstate="collapsed" desc="Yii">
    /**
     * Retorna un array con los nombres de los atributos
     */
    public function attributeNames()     {
        return array(
            'nombre',
            'apellido1',
            'apellido2',
            'cedula',
            'numerocuenta',
            'banco',
            'cuentacliente',
            'codigo',
            'telefono',
            'correo'
        );
    }

    public function __get($name)     {
        if (property_exists($this, $name))         {
            return $this->$name;
        }         else         {
            return parent::__get($name);
        }
    }


        public function __set($name, $value)     {
        if (property_exists($this, $name))         {
            $this->$name = $value;
        }         else         {
            parent::__set($name, $value);
        }
    }

        /**
         * Este método retorna una instancia del modelo para cuando se ocupe
         * acceder a sus propiedades. Por ejemplo, para conseguir el string
         * de los labels de los atributos.
         * @return \Asistente Una instancia del modelo asistente.
         */
        public static function model() {
            return new Asistente;
        }//fin model
        
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'nombre' => 'Nombre',
            'apellido1' => 'Primer Apellido',
            'apellido2' => 'Segundo Apellido',
            'cedula' => 'Cédula',
            'numerocuenta' => 'N° Cuenta',
            'banco' => 'Banco',
            'cuentacliente' => 'Cuenta Cliente',
            'codigo' => 'Código del proyecto',
            'telefono' => 'Teléfono',
            'correo' => 'Correo Electrónico'
        );
    }
//</editor-fold>
        
    // <editor-fold defaultstate="collapsed" desc="Búsqueda">
    /**
     * Esta funcion llama al SP buscarDatosPersonalesAsistentePorPK y hace una busqueda de todos
     * los datos personales de un asistente a partir del PK de un registro en la tabla tbl_Personas
     * en la base de datos.
     * @param string $pCarnet El carnet de un registro de la tabla Asistentes.
     * @return array Un arreglo con los atributos del asistente. NULL si no encontro algun registro con ese pk.
     */
    public function buscarAsistentePorCarnet($pCarnet) {
        $conexion = Yii::app()->db;
        $call = 'CALL buscarDatosPersonalesAsistentePorCarnet(:pCarnet)';
        $transaccion = Yii::app()->db->beginTransaction();
        $resultado = NULL;
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':pCarnet', $pCarnet, PDO::PARAM_STR);
            $resultado = $comando->query();
            $transaccion->commit();
        } catch (Exception $e) {
            $transaccion->rollback();
            echo $e->getMessage();
            return NULL;
        }


        return $resultado->rowCount === 1 ? $resultado->read() : NULL;
    
    }//fin buscar asistente por pk
    
    //Función que llama a un stored procedure para ver todos los asistentes y 
    // maneja el filtro  
    public function search(){
           $filtersForm=new FiltersForm;
            if (isset($_GET['FiltersForm']))
                $filtersForm->filters=$_GET['FiltersForm'];
           $call = 'CALL verAsistentes()';
           $rawData=Yii::app()->db->createCommand($call)->queryAll();
           $filteredData=$filtersForm->filter($rawData);
           $dataProvider=new CArrayDataProvider($filteredData, array(
                'keyField'=>'carnet',
                'id'=>'user',
                'sort'=>array(
                    'attributes'=>array(
                        'carnet',
                        'nombre',
                        'apellido1',
                        'apellido2',
                        'telefono',
                        'correo',
                    ),
                ),
                'pagination'=>array(
                    'pageSize'=>10,
                ),
            ));
            return $dataProvider;
    }
    /*Metodo que llama a un stored procedure que lista todos los proyectos a los que pertenece un asistente.*/
    public function verProyectos(){
           $call = 'CALL verProyectosporAsistente(:carnetbuscado)';
           $comand=Yii::app()->db->createCommand($call);
           $comand->bindParam(':carnetbuscado', $this->carnet, PDO::PARAM_STR);
           $rawdata=$comand->queryAll();
           $dataProvider=new CArrayDataProvider($rawdata, array(
                'keyField'=>'codigo',
                'id'=>'user',
                'sort'=>array(
                    'attributes'=>array(
                        'idtbl_proyectos',
                        'codigo',
                        'nombre',
                        'horas',
                    ),
                ),
                'pagination'=>array(
                'pageSize'=>10,
                ),
            ));
            return $dataProvider;
    }
    
    /**
     * Busca los datos más actuales de un asistente en un proyecto.
     * Carga los datos en el modelo y guarda los periódos en un array.
     * @param int $pIDProyecto ID del proyecto en la base de datos.
     * @return array Un arreglo de arreglos con la fechas de los periodos correspondientes a los
     * atributos que cargó de la base de datos.
     */
    public function buscarDatosActualesAsistenteEnProyecto($pIDProyecto) {
        $respuesta =  NULL;
        $call = 'CALL buscarDatosActualesAsistenteEnProyecto(:carnet,:idproyecto)';
        $comando = Yii::app()->db->createCommand($call);
        $comando->bindParam(':carnet', $this->carnet);
        $comando->bindParam(':idproyecto', $pIDProyecto);
        $query = $comando->query();
        if ($query->rowCount === 1) {
            $read = $query->read();
            $this->rol = $read['rol_id'];
            $this->horas = $read['horas'];
            $periodo_rol = new Periodos;
            $periodo_rol->inicio = $read['inicio_rol'];
            $periodo_rol->fin = $read['fin'];
            $periodo_horas = new Periodos;
            $periodo_horas->inicio = $read['inicio_horas'];
            $periodo_horas->fin = $read['fin'];
            $periodo_asistencia = new Periodos;
            $periodo_asistencia->inicio = $read['inicio'];
            $periodo_asistencia->fin = $read['fin'];
            $respuesta = array('rol' => $periodo_rol, 'horas' => $periodo_horas, 'asistencia' => $periodo_asistencia);
        }//fin si lo encontró y sólo retorna un resultado
        return $respuesta;
    }//fin buscar Asistente en proyecto
    
    /**
     * Busca el periodo del rol que termina en la fecha dada.
     * Esta función es útil cuando se busca un rol anterior al actual
     * para evitar que se traslapen los tiempos.
     * @param string $pFecha Fecha de fin del periodo que se busca.
     * @return \Periodos Retorna el periodo con las fechas de inicio y fin.
     * Retorna <code>NULL</code> si no encuentra el periodo.
     */
    public function buscarPeriodoRolAnterior($pFecha) {
        $respuesta = NULL;
        $call = 'CALL buscarPeriodoRolAnterior(:fecha,:carnet,:id)';
        $conexion = Yii::app()->db;
        $comando = $conexion->createCommand($call);
        $comando->bindParam(':fecha', $pFecha, PDO::PARAM_STR);
        $comando->bindParam(':carnet', $this->carnet, PDO::PARAM_STR);
        $comando->bindParam(':id', $this->codigo, PDO::PARAM_INT);
        $query = $comando->query();
        if (($read = $query->read())){
            $respuesta = new Periodos;
            $respuesta->inicio = $read['inicio'];
            $respuesta->fin = $read['fin'];
        }//fin si logro leer un resultado
        return $respuesta;
    }//fin buscar periodo rol anterior
    
    /**
     * Busca el periodo de las horas que termina en la fecha dada.
     * Esta función es útil cuando se busca el periodo de horas
     * anterior al actual para evitar que se traslapen los periodos.
     * @param string $pFecha Fecha de fin del periodo que se busca.
     * @return \Periodos Retorna el periodo con las fechas de inicio y fin.
     * Retorna <code>NULL</code> si no encuentra el periodo.
     */
    public function buscarPeriodoHorasAnterior($pFecha) {
        $respuesta = NULL;
        $call = 'CALL buscarPeriodoHorasAnterior(:fecha,:carnet,:id)';
        $conexion = Yii::app()->db;
        $comando = $conexion->createCommand($call);
        $comando->bindParam(':fecha', $pFecha, PDO::PARAM_STR);
        $comando->bindParam(':carnet', $this->carnet, PDO::PARAM_STR);
        $comando->bindParam(':id', $this->codigo, PDO::PARAM_INT);
        $query = $comando->query();
        if (($read = $query->read())){
            $respuesta = new Periodos;
            $respuesta->inicio = $read['inicio'];
            $respuesta->fin = $read['fin'];
        }//fin si logro leer un resultado
        return $respuesta;
    }//fin buscar periodo horas anterior

// </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Validaciones">
    /**
        * @return array validation rules for model attributes.
        */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('carnet,rol,horas','required','on'=>'agregar'),
            array('nombre, apellido1, cedula, numerocuenta, banco, cuentacliente, carnet, carrera, telefono, correo, codigo, rol, horas', 'required', 'message'=>'{attribute} no puede dejarse en blanco.', 'on'=>'nuevo'),
            array('nombre, apellido1, cedula, numerocuenta, banco, cuentacliente, carnet, carrera, telefono, correo', 'required', 'message'=>'{attribute} no puede dejarse en blanco.', 'on'=>'actDP'),
            array('horas, rol','required','message'=>'{attribute} no puede dejarse en blanco.','on'=>'actInfoProy'),
            array('nombre, apellido1, apellido2, codigo', 'length', 'max'=>20),
            array('cedula','length','min'=>9,'max'=>20,'safe'=>true),
            array('carnet','length', 'min'=>7,'max'=>15,'safe'=>true),
            array('carrera, rol','length', 'max'=>60,'safe'=>true),
            array('telefono, correo', 'length', 'max'=>40,'safe'=>true),
            array('numerocuenta', 'length', 'max'=>30,'safe'=>true),
            array('banco', 'length', 'max'=>70,'safe'=>true),
            array('cuentacliente', 'length', 'min'=>17, 'max'=>17,'safe'=>true),
            array('telefono, cedula, cuentacliente, carnet', 'match', 'pattern'=>'/^[\p{N}]+$/u', 'message'=>'{attribute} sólo puede estar compuesto por dígitos.'),
            array('horas', 'match', 'pattern'=>'/^[0-9]+(.(5?)(0*))?$/', 'message'=>'{attribute} no son válidas.'),
            array('horas', 'numerical', 'max'=>20, 'min'=>1, 'tooBig'=>'Se permite un máximo de {max} horas de asistencia', 'tooSmall'=>'Se permite un mínimo de {min} horas.'),
            array('correo', 'email', 'message'=>'Dirección de correo inválida'),
            array('nombre, apellido1, apellido2, ', 'match', 'pattern'=>'/^[\p{L} ]+$/u'),
            array('numerocuenta', 'match', 'pattern'=>'/^[\p{N}-]+$/u'), //,codigo
            array('codigo','validarCodigoProyecto','on'=>'nuevo'),
            array('carnet','validarAsistenteNoRepetido','on'=>'agregar'),
        );
    }
   
    /**
     * Valida que el asistente no esté activo en el proyecto.
     * @param array $attribute Atributos que valida
     * @param array $params Parametros de la validacion
     */
    public function validarAsistenteNoRepetido($attribute, $params){
        if(isset($params['on']) && $params['on'] != $this->scenario)
            return;
        $call = "CALL buscarDatosActualesAsistenteEnProyecto(:carnet,:codigo)";
        $comando = Yii::app()->db->createCommand($call);
        $comando->bindParam(':carnet',$this->carnet,PDO::PARAM_STR);
        $comando->bindParam(':codigo', $this->codigo,  PDO::PARAM_INT);
        $query = $comando->query();
        if ($query->rowCount != 0)
            $this->addError($attribute, 'El asistente ya está en el proyecto.');
    }//fin validar asistente no repetido
        
    /**
     * Valida que las horas nuevas cumplan con que sean numéricas y que el asistente no sobrepase
     * las horas acumuladas establecidas. Si las horas son válidas el modelo queda con las horas nuevas
     * y si no son válidas el modelo queda con las horas anteriores.
     * @param string $pHorasNuevas Las horas que el usuario quiere asignarle al asistente.
     * @param Asistente $this El modelo del asistente que representa al asistente al que se le realiza el cambio.
     * @return boolean Retorna true si los datos son válidos y false de lo contrario.
        */
    public function validarActualizacionDeHoras($pHorasNuevas) {
        $horas_anteriores = $this->horas;
        $horas_totales = $this->contarHorasAsistenciaActuales();
        $horas_totales -= $horas_anteriores;
        $horas_totales += $pHorasNuevas;
        $this->horas = $horas_totales;
        if ($this->validate(array('horas'), false)) {
            $this->horas = $pHorasNuevas;
            if ($this->validate(array('horas'), false)) {
                return true;
            }//fin si las horas totales y las horas nuevas son válidas.
            else {
                $this->horas = $horas_anteriores;
                return false;
            }//fin si no son válidas
        }//fin si las horas totales son válidas
        else {
            $this->horas = $horas_anteriores;
            return false;
        }//fin si las horas totales no son válidas
    }// fin validación de las nuevas horas ingresadas
    
    /**
     * Valida que no haya otro asistente con esa cédula. Si hay otra persona que tiene la misma
     * cédula pero no está en la tabla asistentes entonces no lo cuenta.
     * @param type $pCedula cédula que tenía antes de una actualización.
     */
    public function validarCedulaUnica($pCedula = NULL) {
        if ($this->cedula != $pCedula) {
            $conexion = Yii::app()->db;
            $sql = "SELECT COUNT(*) cuenta FROM tbl_Personas P INNER JOIN tbl_Asistentes A ON P.idtbl_Personas = A.idtbl_Personas WHERE P.cedula = :pCedula;";
            $comando = $conexion->createCommand($sql);
            $comando->bindParam(':pCedula',$this->cedula, PDO::PARAM_STR);
            $resultado = $comando->query();
            $cuenta = $resultado->read();
            if ($cuenta['cuenta'] != 0) {
                $this->addError('cedula', 'Ya existe un asistente con la cédula ' . $this->cedula . '.');
            }//fin si el carnet es único
        }//fin si el carnet es diferente
    }//fin validar cedula unica
    
    /**
     * Valida que no exista otro asistente con ese carnet en la base de datos.
     * @param type $pCarnet Carnet del estudiante antes de la actualización. Sirve para que no de
     * un error falso indicando que ya existe otro asistente con ese carnet.
     */
    public function validarCarnetUnico($pCarnet = NULL) {
        if ($this->carnet != $pCarnet) {
            $conexion = Yii::app()->db;
            $sql = "SELECT * FROM tbl_Asistentes WHERE carnet = :pCarnet";
            $comando = $conexion->createCommand($sql);
            $comando->bindParam(':pCarnet',$this->carnet, PDO::PARAM_STR);
            $resultado = $comando->query();
            if ($resultado->rowCount != 0) {
                $this->addError('carnet', 'Ya existe un asistente con el carnet ' . $this->carnet . '.');
            }//fin si el carnet es único
        }//fin si el carnet es diferente
    }//fin validar que el carnet sea único
    
    /**
     * Verifica que el código digitado se encuentre en la base de datos.
     * De esta forma se previene que elija un proyecto de la lista y luego
     * digite un número más, ingresando de esta forma un código incorrecto. 
     */
    public function validarCodigoProyecto($attribute,$params) {
        if(isset($params['on']) && $params['on'] != $this->scenario)
            return;
        $criteria = new CDbCriteria;
        $criteria->alias = "pr";
        $criteria->join = 'INNER JOIN tbl_HistorialProyectosPeriodos HPP ON pr.idtbl_Proyectos = HPP.idtbl_Proyectos
                           INNER JOIN tbl_Periodos P ON HPP.idPeriodo = P.idPeriodo';
        $criteria->condition = "pr.codigo = '" . $this->codigo . "' AND p.inicio <= SYSDATE() AND p.fin > SYSDATE()";
        if (!Proyectos::model()->exists($criteria)) {
            $this->addError('codigo', $this->getAttributeLabel('codigo') . ' no fue encontrado en la base de datos o no se encuentra activo.');
        }//fin si el código existe en la base de datos
    }//fin validarCodigoProyecto
// </editor-fold>
    
    /**
     * Funciones que sirven para cambiar datos erróneos que se hayan ingresado. Sólo actualizan los campos, sin afectar periodos.
     */
    // <editor-fold defaultstate="collapsed" desc="Correción de datos">
    /**
     * Llama al stored procedure encargado de cambiar las horas que un asistente con cierto carnet cumple semanalmente en este proyecto.
     * El cambio no afecta el periodo de las horas.
     * @return boolean Retorna true si la operación fué exitosa y false en caso contrario.
     */
    public function cambiarHorasAsistencia() {
        $conexion = Yii::app()->db;
        $call = "CALL cambiarHorasAsistencia(:carnet, :pkProyecto, :horas)";
        $transaccion = $conexion->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':carnet', $this->carnet);
            $comando->bindParam(':pkProyecto', $this->codigo);
            $comando->bindParam(':horas', $this->horas);
            $comando->execute();
            $transaccion->commit();
        }//fin try
        catch (Exception $e) {
            Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
            $transaccion->rollback();
            return false;
        }//fin catch
        return true;
    }

//fin cambiar horas asistencia


    /**
     * Cambia el rol que desempeña un asistente en un proyecto.
     * El cambio no afecta el periodo del rol.
     * @return boolean True si la operación tuvo éxito y false de lo contrario.
         */
    public function cambiarRolProyecto() {
        $conexion = Yii::app()->db;
        $call = 'CALL cambiarRolAsistente(:pkProyecto, :carnet, :rol)';
        $transaccion = $conexion->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':pkProyecto', $this->codigo, PDO::PARAM_INT);
            $comando->bindParam(':carnet', $this->carnet, PDO::PARAM_STR);
            $comando->bindParam(':rol', $this->rol, PDO::PARAM_STR);
            $comando->execute();
            $transaccion->commit();
        }//fin try
        catch (Exception $e) {
            Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
            $transaccion->rollback();
            return false;
        }//fin catch
        return true;
    }//cambia el rol del asistente en un proyecto.
    
    /**
     * Corrige el periodo actual del rol del asistente. Cambia la fecha de inicio
     * del periodo actual y la fecha de fin del periodo anterior.
     * @param string $pInicio Nueva fecha de inicio.
     * @return boolean Retorna <code>true</code> si logra hacer el cambio con éxito y <code>false</code> de lo contrario.
     */
    public function corregirFechaInicioRolAsistente($pInicio){
        $conexion = Yii::app()->db;
        $call = 'CALL corregirFechaInicioRolAsistente(:pk,:carnet,:inicio)';
        $transaction = $conexion->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':pk', $this->codigo, PDO::PARAM_STR);
            $comando->bindParam('carnet', $this->carnet, PDO::PARAM_STR);
            $comando->bindParam('inicio', $pInicio, PDO::PARAM_STR);
            $comando->execute();
            $transaction->commit();
        } catch (Exception $e) {
            Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
            $transaction->rollback();
            return false;
        }//fin try-catch
        return true;
    }//fin corregir fecha de inicio de rol del asistente
    
    /**
     * Corrige el periodo actual de las horas del asistente. Cambia la fecha de inicio
     * del periodo actual y la fecha de fin del periodo anterior.
     * @param string $pInicio Nueva fecha de inicio.
     * @return boolean Retorna <code>true</code> si logra hacer el cambio con éxito y <code>false</code> de lo contrario.
     */
    public function corregirFechaInicioHorasAsistente($pInicio) {
        $conexion = Yii::app()->db;
        $call = 'CALL corregirFechaInicioHorasAsistente(:pk,:carnet,:inicio)';
        $transaction = $conexion->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':pk', $this->codigo, PDO::PARAM_STR);
            $comando->bindParam('carnet', $this->carnet, PDO::PARAM_STR);
            $comando->bindParam('inicio', $pInicio, PDO::PARAM_STR);
            $comando->execute();
            $transaction->commit();
        } catch (Exception $e) {
            Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
            $transaction->rollback();
            return false;
        }//fin try-catch
        return true;
    }// fin corregir fecha de inicio de las horas del asistente.
// </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Actualización de datos">
    /**
     * Cambia la fecha del fin de la asistencia por la que especifique el usuario. Los periodos de rol, horas y asistencia
     * son afectados en la base de datos.
     * @param string $pFecha Fecha nueva del fin de la asistencia.
     * @return boolean Retorna true si la transacción tuvo éxito o false de lo contrario.
     */
    public function cambiarFinAsistencia($pFecha) {
        $conexion = Yii::app()->db;
        $call = 'CALL actualizarFinAsistencia(:pk, :carnet, :fecha)';
        $transaccion = $conexion->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':pk', $this->codigo, PDO::PARAM_INT);
            $comando->bindParam(':carnet', $this->carnet, PDO::PARAM_STR);
            $comando->bindParam(':fecha', $pFecha, PDO::PARAM_STR);
            $comando->execute();
            $transaccion->commit();
        }//fin try
        catch (Exception $e) {
            Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
            $transaccion->rollback();
            return false;
        }//fin catch
        return true;
    }//fin cambiar fecha del fin de la asistencia
    
    /**
     * Cambia los datos personales de un asistente en la base de datos
     * por los que se proporcionan en el formulario.
     * @param string $pPK El carnet anterior del Asistente.
     * @return boolean True si no ocurrio ningún error y false de lo contrario.
     */
    public function actualizarDatosPersonales($pCarnet) {
        $conexion = Yii::app()->db;
        $call = 'CALL actualizarDatosPersonalesAsistente(:pCarnet,:nombre,:apellido1,:apellido2,:cedula,:numerocuenta,:cuentacliente,:carnet,:carrera,:banco,:telefono,:correo)';
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':pCarnet',$pCarnet, PDO::PARAM_INT);
            $comando->bindParam(':nombre',$this->nombre, PDO::PARAM_STR);
            $comando->bindParam(':apellido1',$this->apellido1, PDO::PARAM_STR);
            $comando->bindParam(':apellido2',$this->apellido2, PDO::PARAM_STR);
            $comando->bindParam(':cedula',$this->cedula, PDO::PARAM_STR);
            $comando->bindParam(':numerocuenta',$this->numerocuenta, PDO::PARAM_STR);
            $comando->bindParam(':cuentacliente',$this->cuentacliente, PDO::PARAM_STR);
            $comando->bindParam(':carnet',$this->carnet, PDO::PARAM_STR);
            $comando->bindParam(':carrera',$this->carrera, PDO::PARAM_STR);
            $comando->bindParam(':banco',$this->banco, PDO::PARAM_STR);
            $comando->bindParam(':telefono',$this->telefono, PDO::PARAM_STR);
            $comando->bindParam(':correo',$this->correo, PDO::PARAM_STR);
            $comando->execute();
            $transaction->commit();
        }
        catch (Exception $e) {
             Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
            $transaction->rollback();
            return FALSE;
        }
        return TRUE;
    }
    
    public function desvincular($idtbl_Proyectos,$carnet){
            $conexion = Yii::app()->db;
            $call = 'CALL desvincularAsistente(:asistente, :proyecto)';
            $transaccion = $conexion->beginTransaction();
            try {
                $comando = $conexion->createCommand($call);
                $comando->bindParam(':proyecto', $idtbl_Proyectos, PDO::PARAM_STR);
                $comando->bindParam(':asistente', $carnet, PDO::PARAM_STR);
                $comando->execute();
                $transaccion->commit();
            }//fin try
            catch (Exception $e) {
                Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
                $transaccion->rollback();
                return false;
            }//fin catch
            return true;
    }
    
    /**
     * Cambia los periodos de asistencia del asistente en el proyecto dados la
     * nueva fecha fin y la nueva fecha de inicio. Afecta a los periodos del
     * rol, de las horas y de la asistencia.
     * @param int $pIdProyecto ID del proyecto. Es el PK que tiene la BD.
     * @param string $pInicio Nueva fecha de inicio de la asistencia.
     * @param string $pFin Nueva Fecha de fin de la asistencia.
     * @return boolean Retorna <code>true</code> si se ejecutó el SP con éxito y <code>false</code> de lo contrario.
     */
    public function cambiarPeriodoAsistencia($pIdProyecto, $pInicio, $pFin) {
        $call = 'CALL cambiarPeriodoAsistencia(:inicio,:fin,:carnet,:id)';
        $conexion = Yii::app()->db;
        $transaction = $conexion->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':inicio', $pInicio, PDO::PARAM_STR);
            $comando->bindParam(':fin', $pFin, PDO::PARAM_STR);
            $comando->bindParam(':carnet', $this->carnet, PDO::PARAM_STR);
            $comando->bindParam('id', $pIdProyecto, PDO::PARAM_INT);
            $comando->execute();
            $transaction->commit();
        }//fin try
        catch (Exception $e) {
            Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
            $transaccion->rollback();
            return false;
        }//fin catch
        return true;
    }//fin cambiar periodo asistencia

    /**
     * Agrega un nuevo periodo para el nuevo rol que desempeña el asistente en el proyecto.
     * @param string $pInicio Fecha en que el asistente inicia con el nuevo rol.
     * @param int $pIDProyecto PK del proyecto en la base de datos.
     * @return boolean Retorna <code>true</code> si logra ejecutar el SP con
     * éxito y <code>false</code> de lo contrario.
     */
    public function actualizarRolProyecto($pInicio,$pIDProyecto) {
        $call = 'CALL actualizarRolAsistente(:id, :carnet, :rol, :inicio)';
        $conexion = Yii::app()->db;
        $transaction = $conexion->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':id', $pIDProyecto, PDO::PARAM_INT);
            $comando->bindParam(':carnet', $this->carnet, PDO::PARAM_STR);
            $comando->bindParam('rol', $this->rol, PDO::PARAM_INT);
            $comando->bindParam(':inicio', $pInicio, PDO::PARAM_STR);
            $comando->execute();
            $transaction->commit();
        }//fin try
        catch (Exception $e) {
            Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
            $transaction->rollback();
            return false;
        }//fin catch
        return true;
    }//fin actualizar rol del asistente en el proyecto.
    
    /**
     * Inserta un nuevo periodo con las nuevas horas que cumple el asistente.
     * @param int $pIDProyecto PK del proyecto en la base de datos.
     * @param string $pInicio Fecha en la que empieza a hacer las nuevas horas.
     * @return boolean Retorna <code>true</code> si logra ejecutar el SP con
     * éxito y <code>false</code> de lo contrario.
     */
    public function actualizarHorasProyecto($pIDProyecto, $pInicio) {
        $call = 'CALL actualizarHorasAsistencia(:carnet,:id,:horas,:inicio)';
        $conexion = Yii::app()->db;
        $transaction = $conexion->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':carnet', $this->carnet, PDO::PARAM_STR);
            $comando->bindParam(':id', $pIDProyecto, PDO::PARAM_INT);
            $comando->bindParam(':horas', $this->horas);
            $comando->bindParam(':inicio', $pInicio, PDO::PARAM_STR);
            $comando->execute();
            $transaction->commit();
        }//fin try
        catch (Exception $e) {
            Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
            $transaction->rollback();
            return false;
        }//fin catch
        return true;
    }//fin actualizar horas del asistente en el proyecto.
// </editor-fold>
    
    /**
     * Funcion de guardado.
     * Crea un nuevo asistente en la base de datos. Usa el stored procedure 'registrarNuevoAsistente'
     * y lo hace de forma transaccional.
     * @param Periodos $pPeriodo El periodo inicial del asistente.
     */
    public function crear($pPeriodo) {
        $conexion = Yii::app()->db;
        $call = "CALL registrarNuevoAsistente(:nombre,:ape1,:ape2,:ced,:numc,:ccliente,:carnet,:carrera,:cod,:rol,:horas,:tel,:correo,:banco,'" . $pPeriodo->inicio . "','" . $pPeriodo->fin. "')";
        $transaccion = Yii::app()->db->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $comando->bindParam(':ape1', $this->apellido1, PDO::PARAM_STR);
            $comando->bindParam(':ape2', $this->apellido2, PDO::PARAM_STR);
            $comando->bindParam(':ced', $this->cedula, PDO::PARAM_STR);
            $comando->bindParam(':numc', $this->numerocuenta, PDO::PARAM_STR);
            $comando->bindParam(':ccliente', $this->cuentacliente, PDO::PARAM_STR);
            $comando->bindParam(':carnet', $this->carnet, PDO::PARAM_STR);
            $comando->bindParam(':carrera', $this->carrera, PDO::PARAM_STR);
            $comando->bindParam(':cod', $this->codigo, PDO::PARAM_STR);
            $comando->bindParam(':rol', $this->rol, PDO::PARAM_STR);
            $comando->bindParam(':horas', $this->horas);
            $comando->bindParam(':tel', $this->telefono, PDO::PARAM_STR);
            $comando->bindParam(':correo', $this->correo, PDO::PARAM_STR);
            $comando->bindParam(':banco', $this->banco, PDO::PARAM_STR);
            $comando->execute();
            $transaccion->commit();
        } catch (Exception $e) {
            Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Asistente");
            $transaccion->rollback();
            return false;
        }
        return true;
    }//fin crear
    
    /**
     * Cuenta las horas de asistencia que actualmente realiza este asistente en todos los proyectos.
     * @return int Retorna el número total de horas de asistencia que hace en todos los proyectos.
     */
    public function contarHorasAsistenciaActuales() {
        $call = 'CALL contarHorasAsistenciaActuales(:carnet)';
        $comando = Yii::app()->db->createCommand($call);
        $comando->bindParam(':carnet',$this->carnet,PDO::PARAM_STR);
        $query = $comando->query();
        if ($query->rowCount === 1) {
            $read = $query->read();
            return $read['horas'];
        }
        else
            return 0;
    }//fin contarHorasActuales
    
// <editor-fold defaultstate="collapsed" desc="Reportes">
    /**
     * Obtiene lista de proyectos en los que ha trabajado un asistente, su rol y asociado a periodos de tiempo
     * @param type $pCarnet
     * @return 
     */
    public function obtenerHistorialProyectosAsistente($pCarnet){
        $call = 'CALL obtenerHistorialProyectosAsistentes(:carnet)';
        $comando = Yii::app()->db->createCommand($call);
        $comando->bindParam(':carnet',$pCarnet,PDO::PARAM_STR);
        $query = $comando->queryAll();
        if (empty($query))
            return null;
        else
            return $query;
    }
    
    /**
     * Obtiene un CDataProvider con las horas que ha realizado un asistente
     * @param string $pCarnet El carnet del asistente
     * @param string|\null $pFechaMes El mes a buscar, si se pasa un null muestra todo el historial
     * @return null|\CArrayDataProvider 
     */
    public function obtenerHorasAsistente($pCarnet, $pFechaMes){
        if($pFechaMes != null){
            $call = 'CALL obtenerHorasMesAsistente(:pCarnet,:pFechaMes)';
            $comando = Yii::app()->db->createCommand($call);
            $comando->bindParam(':pFechaMes',$pFechaMes,PDO::PARAM_STR);
        }else{
            $call = 'CALL obtenerHistorialHorasAsistente(:pCarnet)';
            $comando = Yii::app()->db->createCommand($call);
        }
        
        $comando->bindParam(':pCarnet',$pCarnet,PDO::PARAM_STR);
        $query = $comando->queryAll();
        
        if (empty($query)){
            return null;
        }
        else{
            $data_provider = new CArrayDataProvider(
                        $query, //obtiene los datos de variable $query
                        array(
                            'keyField'=>'codigo',
                            'id' => 'asistente-historial-proyectos',
                            'sort' => array(
                                'attributes' => array(
                                    'idtbl_proyectos','codigo', 'inicio', 'fin','horas'
                                ),
                            ),
                            'pagination' => array(
                                'pageSize' => 50,
                            ),
                ));
            return $data_provider;
        }
    }
// </editor-fold>


}//fin clase Modelo Asistente

?>
