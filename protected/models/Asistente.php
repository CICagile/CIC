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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('nombre, apellido1, cedula, numerocuenta, banco, cuentacliente, carnet, carrera, telefono, correo, codigo, rol, horas', 'required', 'message'=>'{attribute} no puede dejarse en blanco.', 'on'=>'nuevo'),
                array('nombre, apellido1, cedula, numerocuenta, banco, cuentacliente, carnet, carrera, telefono, correo', 'required', 'message'=>'{attribute} no puede dejarse en blanco.', 'on'=>'actDP'),
                array('horas','required','message'=>'{attribute} no puede dejarse en blanco.','on'=>'actInfoProy'),
                array('nombre, apellido1, apellido2, codigo', 'length', 'max'=>20),
                array('cedula','length','min'=>9,'max'=>20),
                array('carnet','length', 'min'=>7,'max'=>15),
                array('carrera, rol','length', 'max'=>60),
                array('telefono, correo', 'length', 'max'=>40),
                array('numerocuenta', 'length', 'max'=>30),
                array('banco', 'length', 'max'=>70),
                array('cuentacliente', 'length', 'min'=>17, 'max'=>17),
                array('telefono, cedula, cuentacliente, carnet', 'match', 'pattern'=>'/^[\p{N}]+$/u', 'message'=>'{attribute} sólo puede estar compuesto por dígitos.'),
                array('horas', 'match', 'pattern'=>'/^[0-9]+(.(5?)(0*))?$/', 'message'=>'{attribute} no son válidas.'),
                array('horas', 'numerical', 'max'=>20, 'min'=>1, 'tooBig'=>'Se permite un máximo de {max} horas de asistencia', 'tooSmall'=>'Se permite un mínimo de {min} horas.'),
                array('correo', 'email', 'message'=>'Dirección de correo inválida'),
                array('nombre, apellido1, apellido2, ', 'match', 'pattern'=>'/^[\p{L} ]+$/u'),
                array('numerocuenta,codigo', 'match', 'pattern'=>'/^[\p{N}-]+$/u'),
                array('codigo','validarCodigoProyecto','on'=>'nuevo')
            );
	}
               
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
        }

    /**
     * Retorna un array con los nombres de los atributos
     */
    public function attributeNames()
    {
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
    //Función que llama a un store procedure para ver todos los asistentes y 
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
    /*Metodo que llama a un store procedure que lista todos los proyectos a los que pertenece un asistente.*/
    public function proyectosasistente(){
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
    
    public function __get($name)
    {
        if (property_exists($this, $name))
        {
            return $this->$name;
        }
        else
        {
            return parent::__get($name);
        }
    }
    
    public function __set($name, $value)
    {
        if (property_exists($this, $name))
        {
            $this->$name = $value;
        }
        else
        {
            parent::__set($name, $value);
        }
    }
    
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
    
    /**
     *Esta funcion llama al SP buscarDatosPersonalesAsistentePorPK y hace una busqueda de todos
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
     * Cuenta las horas de asistencia que actualmente realiza este asistente en todos los proyectos.
     * @return int Retorna el número total de horas de asistencia que hace en todos los proyectos.
     */
    public function contarHorasAsistenciaActuales() {
        $select = "SELECT idtbl_Asistentes id FROM tbl_Asistentes WHERE carnet = '" . $this->carnet . "'";
        $comando = Yii::app()->db->createCommand($select);
        $read = $comando->query()->read();
        $id = $read['id'];
        $call = 'CALL verificarHorasAcumuladasProyectos(:id)';
        $comando = Yii::app()->db->createCommand($call);
        $comando->bindParam(':id',$id,PDO::PARAM_INT);
        $query = $comando->query();
        if ($query->rowCount === 1) {
            $read = $query->read();
            return $read['SUM(horas)'];
        }
        else
            return 0;
    }//fin contarHorasActuales
    
    /**
         * Llama al stored procedure encargado de actualizar las horas que un asistente con cierto carnet cumple semanalmente en este proyecto.
         * @param int pID es el PK del proyecto del cual se cambian las horas
         * @return boolean Retorna true si la operación fué exitosa y false en caso contrario.
         */
        public function cambiarHorasAsistencia() {
            $conexion = Yii::app()->db;
            $call = "CALL actualizarHorasAsistencia(:carnet, :pkProyecto, :horas)";
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
        }//fin cambiar horas asistencia
        
        /**
         * Cambia el rol que desempeña un asistente en un proyecto.
         * @return boolean True si la operación tuvo éxito y false de lo contrario.
         */
        public function cambiarRolProyecto(){
            $conexion = Yii::app()->db;
            $call = 'CALL actualizarRolAsistente(:pkProyecto, :carnet, :rol)';
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
    
}//fin clase Modelo Asistente

?>
