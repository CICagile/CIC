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
                array('nombre, apellido1, cedula, numerocuenta, banco, cuentacliente, carnet, carrera, telefono, correo, codigo, rol, horas', 'required'),
                array('nombre, apellido1, apellido2, cedula, codigo', 'length', 'max'=>20),
                array('carnet','length', 'max'=>15),
                array('carrera, rol','length', 'max'=>45),
                array('telefono, correo', 'length', 'max'=>25),
                array('numerocuenta', 'length', 'max'=>30),
                array('banco', 'length', 'max'=>70),
                array('cuentacliente', 'length', 'max'=>17),
                array('horas', 'numerical', 'max'=>20, 'min'=>0, 'tooBig'=>'Se permite un máximo de {max} horas'), //maximo pemitido 20 horas?
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
		$className=get_class($this);
		if(!isset(self::$_names[$className]))
		{
			$class=new ReflectionClass(get_class($this));
			$names=array();
			foreach($class->getProperties() as $property)
			{
				$name=$property->getName();
				if($property->isPublic() && !$property->isStatic())
					$names[]=$name;
			}
			return self::$_names[$className]=$names;
		}
		else
			return self::$_names[$className];
	}
}

?>
