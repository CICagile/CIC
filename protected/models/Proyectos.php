<?php

/**
 * This is the model class for table "tbl_proyectos".
 *
 * The followings are the available columns in table 'tbl_proyectos':
 * @property integer $idtbl_Proyectos
 * @property string $nombre
 * @property string $codigo

 * @property integer $idtbl_objetivoproyecto
 * @property integer $tipoproyecto
 * @property integer $idtbl_adscrito
 * @property integer $estado
 *
 * The followings are the available model relations:
 * @property Asistentes[] $tblAsistentes
 * @property Documentos[] $documentoses
 * @property Financiamientoexterno[] $financiamientoexternos
 * @property Periodos[] $_periodos
 * @property Presupuesto[] $presupuestos
 * @property Adscrito $idtblAdscrito
 * @property Objetivoproyecto $idtblObjetivoproyecto
 * @property Tipoproyecto $tipoproyecto0
 * @property Convenio[] $tblConvenios
 * @property Sectorbeneficiado[] $tblSectorbeneficiados
 * 
 * Para estado del proyecto 0 es Aprobado, 1 Ampliado
 * 
 */
class Proyectos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Proyectos the static model class
	 */
        public $fecha_inicio_search;
        public $fecha_fin_search;
        
        //Las variables fecha inicio y fecha fin se utilizan para simular el periodo del proyecto.
        public $inicio;
        public $fin;
        
        public $codaprobado = "0";
        public $codampliado = "1";
        
        public $labelaprobado = 'Aprobado';
        public $labelampliado = 'Ampliado';
    
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_proyectos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('nombre, codigo, idtbl_objetivoproyecto, tipoproyecto, idtbl_adscrito, estado', 'required', 'message' => '{attribute} es requerido.'),                        
                        array('codigo', 'unique', 'className' => 'Proyectos', 'message' => 'Ya existe un proyecto con ese código.'),                      
			array('idtbl_objetivoproyecto, tipoproyecto, idtbl_adscrito, estado', 'numerical', 'integerOnly'=>true),
                    
			array('nombre', 'length', 'min'=>3, 'max'=>500, 'tooShort'=> 'El {attribute} debe ser mayor a {min} caracteres.', 'tooLong' => 'El {attribute} debe ser menor a {max} caracteres.'),
			array('codigo', 'length', 'min'=>2, 'max'=>20, 'tooShort'=> 'El {attribute} debe ser mayor a {min} caracteres.', 'tooLong' => 'El {attribute} debe ser menor a {max} caracteres.'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('idtbl_Proyectos, nombre, codigo, tbl_Periodos_idPeriodo', 'safe', 'on'=>'search'),
                        array('idtbl_Proyectos, nombre, codigo, $fecha_inicio_search, $fecha_fin_search, estado', 'safe', 'on'=>'search'),
		);
	}
        
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'_asistentes' => array(self::MANY_MANY, 'Asistentes', 'tbl_asistentes_has_tbl_proyectos(idtbl_Proyectos, idtbl_Asistentes)'),
			'_documentos' => array(self::HAS_MANY, 'Documentos', 'idtbl_Proyectos'),
			'_financiamientoexterno' => array(self::HAS_MANY, 'Financiamientoexterno', 'idtbl_Proyectos'),
			'_presupuestos' => array(self::HAS_MANY, 'Presupuesto', 'idtbl_Proyectos'),
			'_adscrito' => array(self::BELONGS_TO, 'Adscrito', 'idtbl_adscrito'),			
			'_objetivoproyecto' => array(self::BELONGS_TO, 'Objetivoproyecto', 'idtbl_objetivoproyecto'),			
			'_tipoproyecto' => array(self::BELONGS_TO, 'Tipoproyecto', 'tipoproyecto'),
                        '_convenios' => array(self::MANY_MANY, 'Convenio', 'tbl_proyectos_convenio(idtbl_Proyectos, idtbl_convenio)'),
			'_sectorbeneficiados' => array(self::MANY_MANY, 'Sectorbeneficiado', 'tbl_proyectos_sectorbeneficiado(idtbl_Proyectos, idtbl_sectorbeneficiado)'),
                        '_periodos' => array(self::MANY_MANY, 'Periodos', 'tbl_historialproyectosperiodos(idtbl_Proyectos, idPeriodo)'),
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_Proyectos' => 'Id proyecto',
			'nombre' => 'Nombre del proyecto',
			'codigo' => 'Código del proyecto',			
                        'fecha_inicio_search' => 'Fecha Inicio',
                        'fecha_fin_search' => 'Fecha Final',
                        'idtbl_objetivoproyecto' => 'Objetivo del proyecto',
                        'tipoproyecto' => 'Tipo proyecto',
                        'idtbl_adscrito' => 'Adscrito a',
			'estado' => 'Estado del proyecto',                    
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
                $criteria->with = array( 'periodos' );

		$criteria->compare('idtbl_Proyectos',$this->idtbl_Proyectos);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('codigo',$this->codigo,true);		
                $criteria->compare( 'periodos.inicio', $this->fecha_inicio_search, true );
                $criteria->compare( 'periodos.fin', $this->fecha_fin_search, true );
                $criteria->compare('idtbl_objetivoproyecto',$this->idtbl_objetivoproyecto);
		$criteria->compare('tipoproyecto',$this->tipoproyecto);
		$criteria->compare('idtbl_adscrito',$this->idtbl_adscrito);
		$criteria->compare('estado',$this->estado, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>array(
                        'attributes'=>array(
                            'fecha_inicio_search'=>array(
                                'asc'=>'periodos.inicio',
                                'desc'=>'periodos.inicio DESC',
                            ),
                            'fecha_fin_search'=>array(
                                'asc'=>'periodos.fin',
                                'desc'=>'periodos.fin DESC',
                            ),
                            '*',
                        ),
                    ),
		));
	}
        
        public function agregarAsistenteProyecto($pidproyecto, $pcarnet, $pidrol, $pfechaini, $pfechafin, $phoras) {
            $conexion = Yii::app()->db;
            $call = 'CALL agregarAsistenteProyecto(:idproyecto,:carnet,:idrol,:fechaini,:fechafin, :horas)';
            $transaccion = Yii::app()->db->beginTransaction();
            try {
                $comando = $conexion->createCommand($call);
                $comando->bindParam(':idproyecto', $pidproyecto);
                $comando->bindParam(':carnet', $pcarnet);
                $comando->bindParam(':idrol', $pidrol);
                $comando->bindParam(':fechaini', $pfechaini);
                $comando->bindParam(':fechafin', $pfechafin); 
                $comando->bindParam(':horas', $phoras);               
                $comando->execute();
                $transaccion->commit();
            } catch (Exception $e) {
                $transaccion->rollback();               
                return false;
            }
            return true;
        }
        
    /**
     * Esta funcion busca en la base de datos todos los asistentes que están activos
     * en el proyecto. Un asistente se considera activo si actualmente está asociado al
     * proyecto. En otras palabras si la fecha actual se encuentra entre su fecha de inicio y su fecha de fin de la asistencia.
     * @return CArraryDataProvider Retorna un CArrayDataProvider que se usa para mostrar datos en tablas. 
     */        
    public function buscarAsistentesActivosDeProyecto() {
        $pk = $this->idtbl_Proyectos;
        $call = 'CALL buscarAsistentesActivosDeProyecto(:pk)';
        $conexion = Yii::app()->db;
        $comando = $conexion->createCommand($call);
        $comando->bindParam(':pk',$pk,PDO::PARAM_INT);
        $rawData = $comando->queryAll();
        $dataProvider = new CArrayDataProvider($rawData, array(
            'keyField'=>'carnet',
            'id'=>'Asistentes',
            'sort'=>array(
                'attributes'=>array(
                    'carnet',
                    'nombre',
                    'apellido1',
                    'rol',
                    'horas',
                    //'fin', LA TABLA NO RECONOCE QUE ES UNA FECHA Y NO HACE UN BUEN ORDENAMIENTO
                ),
            ),
        ));
        return $dataProvider;
    }//fin buscar asistentes activos del proyecto
    
    /*Esta funcion retorna la información del proyecto
         * y la información del periodo actual asociado al proyecto
         */
        public function obtenerProyectoconPeriodoActual($idproyecto){
            
            $connection=Yii::app()->db;
            $sql=   "SELECT tbl_proyectos.*, tbl_periodos.inicio, tbl_periodos.fin
                    FROM tbl_proyectos
                    INNER JOIN tbl_historialproyectosperiodos
                    ON (tbl_proyectos.idtbl_Proyectos = tbl_historialproyectosperiodos.idtbl_Proyectos)
                    INNER JOIN tbl_periodos 
                    ON (tbl_historialproyectosperiodos.idPeriodo = tbl_periodos.idPeriodo)
                    WHERE tbl_proyectos.idtbl_Proyectos = :idproyecto
                    ORDER BY tbl_periodos.fin DESC
                    LIMIT 1";
            $command=$connection->createCommand($sql);
            $command->bindParam(":idproyecto",$idproyecto,PDO::PARAM_INT);
            $model = $command->queryRow();
            
            if($model == false)
                return null;            
            else{
                $this->setAttributes($model);//Asociamos los atributos reales de un Proyecto
                $this->idtbl_Proyectos = $model['idtbl_Proyectos'];//Se asocia este atributo de manera manual porque setAttributes no asocia atributos no seguros. 
                $this->inicio = $model['inicio'];//Asociamos el atributos simulado inicio
                $this->fin = $model['fin'];//Asociamos el atributos simulado fin
                return $this; //Retornamos el objeto Proyecto
            }                      
        }
        
        public function obtenerProyectosActivos(){                    
            
            $connection=Yii::app()->db;
            $sql=   "SELECT tbl_proyectos.*, DATE_FORMAT(tbl_periodos.inicio, '%d-%m-%Y') AS inicio, 
                    DATE_FORMAT(tbl_periodos.fin, '%d-%m-%Y') AS fin
                    FROM tbl_proyectos
                    INNER JOIN tbl_historialproyectosperiodos
                    ON (tbl_proyectos.idtbl_Proyectos = tbl_historialproyectosperiodos.idtbl_Proyectos)
                    INNER JOIN tbl_periodos 
                    ON (tbl_historialproyectosperiodos.idPeriodo = tbl_periodos.idPeriodo)
                    WHERE tbl_periodos.fin > SYSDATE()"; 
            $command=$connection->createCommand($sql);
            $models = $command->queryAll();
            
            if(empty($models))
                return null;
            else
                return $models;

        }
        
        public function obtenerProyectosAntiguos(){                    
            
            $connection=Yii::app()->db;
            $sql=   "SELECT tbl_proyectos.*, DATE_FORMAT(P.inicio, '%d-%m-%Y') AS inicio, DATE_FORMAT(P.fin, '%d-%m-%Y') AS fin
                    from tbl_historialproyectosperiodos
                    inner join (SELECT tbl_periodos.* FROM tbl_periodos WHERE fin < SYSDATE() ORDER BY fin DESC) P
                    ON tbl_historialproyectosperiodos.idPeriodo = P.idPeriodo
                    INNER JOIN tbl_proyectos
                    ON tbl_historialproyectosperiodos.idtbl_Proyectos = tbl_proyectos.idtbl_Proyectos
                    GROUP BY tbl_proyectos.idtbl_Proyectos"; 
            $command=$connection->createCommand($sql);
            $models = $command->queryAll();
            
            if(empty($models))
                return null;
            else
                return $models;

        }
}//fin modelo proyectos
