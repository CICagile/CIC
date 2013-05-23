<?php
/**
 * Esta clase es la capa de persistencia que carga y guarda los datos de los investigadores.
 * Se crea una instancia de esta clase por cada investigador que esté en un proyecto.
 * Si el mismo investigador se encuentra en más de un proyecto al mismo tiempo se crean
 * instancias aparte.
 *
 */
class Investigador  extends CModel{
    
    //Propiedades
    public $nombre;
    public $apellido1;
    public $apellido2;
    public $cedula;
    public $correo;
    public $telefono;
    public $experiencia;
    public $grado;
    public $proyecto;
    public $rol;
    
    /**
     * Año en que ingresó el investigador al ITCR. 
     */
    public $ingreso;
    
    public $horas = null;
    
    // <editor-fold defaultstate="collapsed" desc="Validaciones">
    
    /**
     *@return array Reglas de validación para los atributos del modelo. 
     */
    public function rules()
    {
        return array(
            array('cedula,rol', 'required', 'on'=>'agregar-investigador','message'=>'{attribute} no puede dejarse en blanco.'),
            array('nombre,apellido1,correo,experiencia,grado,proyecto,rol','required','on'=>'nuevo','message'=>'{attribute} no puede dejarse en blanco.'),
            array('nombre,apellido1,apellido2,proyecto','length','max'=>20),
            array('cedula','length','min'=>9,'max'=>20),
            array('telefono, correo', 'length', 'max'=>40),
            array('telefono, cedula, experiencia', 'match', 'pattern'=>'/^[\p{N}]+$/u', 'message'=>'{attribute} sólo puede estar compuesto por dígitos.'),
            array('correo', 'email', 'message'=>'Dirección de correo inválida'),
            array('nombre, apellido1, apellido2, ', 'match', 'pattern'=>'/^[\p{L} ]+$/u'),
            array('proyecto','validarCodigoProyecto','on'=>'nuevo'),
            array('horas','validarHorasInvestigador','on'=>'nuevo','min'=>1),
            array('horas','validarHorasInvestigador','on'=>'agregar-investigador','min'=>1),
            array('ingreso','numerical','integerOnly'=>true, 'min'=>1901, 'max'=>2155, 'message' => '{attribute} es inválido.'),
        );
    }//fin rules
    
    /**
     * Valida que las horas del investigador no estén vacías
     * @param type $attribute Atributos del validador
     * @param type $params Parametros del validador. Los parámetros son:
     *  on  - Dice bajo que scenario del modelo hace la validación, si se omite, siempre realiza la validación.
     *  min - Mínimo valor que pueden tener las horas. No puede ser negativo.
     *  max - Máximo valor que pueden tener las horas.
     * Todos los parámetros son opcionales.
     */
    public function validarHorasInvestigador($attribute, $params)
    {
        if(isset($params['on']) && $params['on'] != $this->scenario)
            return;
        if ($this->$attribute === null || empty($this->$attribute))
        {
            $this->addError($attribute,  $this->getAttributeLabel($attribute) . ' no puede estar vacío.');
            return;
        }//fin si el arreglo está vacío o es nulo
        foreach ($this->$attribute as $tipo=>$horas)
        {
            if ($tipo == NULL)
            {
                $this->addError($attribute,  'El tipo de horas no puede estar vacío.');
                break;
            }//fin si el tipo est[a vacio
            if ($horas == NULL)
            {
                $this->addError($attribute,  'La cantidad de horas no puede estar vacía.');
                break;
            }//fin si las horas estan en blanco
            if (!preg_match('/^[0-9]+(.(5?)(0*))?$/', $horas))
            {
                $this->addError($attribute,  'La cantidad de horas no son válidas');
                break;
            }//fin si las horas no coinciden con el patrón.
            if (isset($params['min']) && $horas < $params['min'])
            {
                $this->addError($attribute,  'La cantidad de horas no pueden ser menos de ' . $params['min']);
                break;
            }//fin si el parámetro min se toma en cuenta.
            if (isset($params['max']) && $horas > $params['max'])
            {
                $this->addError($attribute,  'La cantidad de horas no pueden ser más de ' . $params['max']);
                break;
            }//fin si el parámetro max se toma en cuenta.
        }//fin for
    }//fin validar Horas Investigador
    
    /**
     * Valida que el codigo del proyecto se encuentre en la BD y esté activo.
     * @param type $attribute Atributos del validador
     * @param type $params Parametros del validador.
     */
    public function validarCodigoProyecto($attribute,$params) {
        if(isset($params['on']) && $params['on'] != $this->scenario)
            return;
        $criteria = new CDbCriteria;
        $criteria->alias = "pr";
        $criteria->join = 'INNER JOIN tbl_HistorialProyectosPeriodos HPP ON pr.idtbl_Proyectos = HPP.idtbl_Proyectos
                           INNER JOIN tbl_Periodos P ON HPP.idPeriodo = P.idPeriodo';
        $criteria->condition = "pr.codigo = '" . $this->$attribute . "' AND p.inicio <= SYSDATE() AND p.fin > SYSDATE()";
        if (!Proyectos::model()->exists($criteria)) {
            $this->addError($attribute, $this->getAttributeLabel($attribute) . ' no fue encontrado en la base de datos o no se encuentra activo.');
        }//fin si el código existe en la base de datos
    }//fin validarCodigoProyecto
    
    /**
     * Valida que no haya otro investigador con esa cédula. Si hay otra persona que tiene la misma
     * cédula pero no está en la tabla investigadores entonces no lo cuenta.
     * @param type $pCedula cédula que tenía antes de una actualización.
     */
    public function validarCedulaUnica($pCedula = NULL) {
        if ($this->cedula != $pCedula) {
            $conexion = Yii::app()->db;
            $sql = "SELECT COUNT(*) cuenta FROM tbl_Personas P INNER JOIN tbl_Investigadores I ON P.idtbl_Personas = I.idtbl_Personas WHERE P.cedula = :pCedula;";
            $comando = $conexion->createCommand($sql);
            $comando->bindParam(':pCedula',$this->cedula, PDO::PARAM_STR);
            $resultado = $comando->query();
            $cuenta = $resultado->read();
            if ($cuenta['cuenta'] != 0) {
                $this->addError('cedula', 'Ya existe un investigador con la cédula ' . $this->cedula . '.');
            }//fin si el carnet es único
        }//fin si el carnet es diferente
    }//fin validar cedula unica
    
// </editor-fold>
    
    /**
     * @return array Etiquetas personalizadas de los atributos del modelo.
     */
    public function attributeLabels()
    {
        return array(
            'nombre' => 'Nombre',
            'apellido1' => 'Primer Apellido',
            'apellido2' => 'Segundo Apellido',
            'cedula' => 'Cédula',
            'correo' => 'Correo Electrónico',
            'telefono' => 'Teléfono',
            'experiencia' => 'Años de Experiencia',
            'grado' => 'Grado Académico',
            'proyecto' => 'Código del Proyecto',
            'rol' => 'Rol del Investigador',
            'horas' => 'Horas',
            'ingreso' => 'Año de ingreso al ITCR',
        );
    }//fin attribute labels
    
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
            'correo',
            'telefono',
            'experiencia',
            'grado',
            'proyecto',
            'rol',
            'horas',
            'ingreso',
        );
    }//fin attribute names


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
     * Este método retorna una instancia del modelo para cuando se ocupe
     * acceder a sus propiedades. Por ejemplo, para conseguir el string
     * de los labels de los atributos.
     * @return Investigador Una instancia del modelo investigador.
     */
    public static function model(){
        return new Investigador;
    }//fin model
        
    /**
    * Funcion de guardado.
    * Registra un nuevo investigador en la base de datos. 
     * Por ahora supongo que las horas son un array de la siguiente forma: array('VIE'=>3, 'FUNDATEC'=>1.5, 'Docencia'=>3, 'Reconocimiento'=>1)
    * @param Periodos $pPeriodo El periodo inicial del investigador.
    */
    public function crear($pPeriodo)
    {
        $conexion = Yii::app()->db;
        $call = "CALL registrarInvestigador(:nombre,:ape1,:ape2,:ced,:correo,:tel,:exp,:grado,:cod,:rol,'" . $pPeriodo->inicio . "','" . $pPeriodo->fin. "', :ingreso)";
        $transaccion = Yii::app()->db->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $comando->bindParam(':ape1', $this->apellido1, PDO::PARAM_STR);
            $comando->bindParam(':ape2', $this->apellido2, PDO::PARAM_STR);
            $comando->bindParam(':ced', $this->cedula, PDO::PARAM_STR);
            $comando->bindParam(':correo', $this->correo, PDO::PARAM_STR);
            $comando->bindParam(':tel', $this->telefono, PDO::PARAM_STR);
            $comando->bindParam(':exp', $this->experiencia, PDO::PARAM_INT);
            $comando->bindParam(':grado', $this->grado, PDO::PARAM_STR);
            $comando->bindParam(':cod', $this->proyecto, PDO::PARAM_STR);
            $comando->bindParam(':rol', $this->rol, PDO::PARAM_STR);
            $comando->bindParam(':ingreso', $this->ingreso);
            $comando->execute();
            foreach ($this->horas as $tipo => $horas) {
                $call = "CALL asignarHorasInvestigador(:ced,:horas,:tipo,'" . $pPeriodo->inicio . "','" . $pPeriodo->fin . "',:cod)";
                $comando = $conexion->createCommand($call);
                $comando->bindParam(':ced', $this->cedula, PDO::PARAM_STR);
                $comando->bindParam(':horas', $horas);
                $comando->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $comando->bindParam(':cod', $this->proyecto, PDO::PARAM_STR);
                $comando->execute();
            }//fin for
            $transaccion->commit();
        } catch (Exception $e) {
            Yii::log("Error en la transacción: " . $e->getMessage(), "error", "application.models.Investigador");
            $transaccion->rollback();
            return false;
        }
        return true;
    }//fin crear

    
}//fin clase Investigador

?>
