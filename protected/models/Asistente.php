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
                array('nombre, apellido1, apellido2, cedula, codigo', 'length', 'max'=>20),
                array('carnet','length', 'max'=>15),
                array('carrera, rol','length', 'max'=>45),
                array('telefono, correo', 'length', 'max'=>25),
                array('numerocuenta', 'length', 'max'=>30),
                array('banco', 'length', 'max'=>70),
                array('cuentacliente', 'length', 'min'=>17, 'max'=>17),
                array('telefono, cedula, cuentacliente, carnet', 'match', 'pattern'=>'/^[\p{N}]+$/u', 'message'=>'{attribute} sólo puede estar compuesto por dígitos.'),
                array('horas', 'numerical', 'max'=>20, 'min'=>0, 'tooBig'=>'Se permite un máximo de {max} horas', 'tooSmall'=>'Se permite un mínimo de {min} horas.'),
                array('correo', 'email', 'message'=>'Dirección de correo inválida'),
                array('nombre, apellido1, apellido2, ', 'match', 'pattern'=>'/^[\p{L} ]+$/u'),
                array('numerocuenta,codigo', 'match', 'pattern'=>'/^[\p{N}-]+$/u')
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
         */
        public function crear() {
            $conexion = Yii::app()->db;
            $call = 'CALL registrarNuevoAsistente(:nombre,:ape1,:ape2,:ced,:numc,:ccliente,:carnet,:carrera,:cod,:rol,:horas,:tel,:correo,:banco)';
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
                $transaccion->rollback();
                echo $e->getMessage();
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
    public function validarCodigoProyecto() {
        if ($this->validate(array('codigo'))) {
            $criteria = new CDbCriteria;
            $criteria->alias = "proyecto";
            $criteria->condition = "proyecto.codigo = " . $this->codigo;
            if (!Proyectos::model()->exists($criteria)) {
                $this->addError('codigo', $this->getAttributeLabel('codigo') . ' no fue encontrado en la base de datos.');
            }//fin si el código existe en la base de datos
        }//fin si el codigo es valido
        else
            $this->clearErrors ();  //Se quitan los errores para que no se muestren dos veces
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
            echo $e->getMessage();
            $transaction->rollback();
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     *Valida que el carnet del estudiante que se quiere crear o actualizar sea único.
     * @return boolean TRUE si el carnet es único en la tabla asistentes, FALSE de lo contrario.
     */
    public function validarCarnetUnico() {
        $sql = "SELECT COUNT(*) FROM tbl_Asistentes WHERE carnet = '" . $this->carnet . "'";
        return true;
    }//fin validar carnet unico
}

?>
