<?php

/**
 * This is the model class for table "tbl_proyectos".
 *
 * The followings are the available columns in table 'tbl_proyectos':
 * @property integer $idtbl_Proyectos
 * @property string $nombre
 * @property string $codigo
 * @property integer $tbl_Periodos_idPeriodo
 * @property integer $idtbl_objetivoproyecto
 * @property integer $tipoproyecto
 * @property integer $idtbl_adscrito
 * @property integer $idtbl_estadoproyecto
 *
 * The followings are the available model relations:
 * @property Personas[] $tblPersonases
 * @property Asistentes[] $tblAsistentes
 * @property Documentos[] $documentoses
 * @property Financiamientoexterno[] $financiamientoexternos
 * @property Presupuesto[] $presupuestos
 * @property Adscrito $idtblAdscrito
 * @property Estadoproyecto $idtblEstadoproyecto
 * @property Objetivoproyecto $idtblObjetivoproyecto
 * @property Periodos $tblPeriodosIdPeriodo
 * @property Tipoproyecto $tipoproyecto0
 * @property Convenio[] $tblConvenios
 * @property Sectorbeneficiado[] $tblSectorbeneficiados
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
                        array('nombre, codigo, tbl_Periodos_idPeriodo, idtbl_objetivoproyecto, tipoproyecto, idtbl_adscrito, idtbl_estadoproyecto', 'required', 'message' => '{attribute} es requerido.'),
                        //array('nombre', 'unique', 'className' => 'Proyectos', 'message' => 'Ya existe un proyecto con ese nombre.'),
                        array('codigo', 'unique', 'className' => 'Proyectos', 'message' => 'Ya existe un proyecto con ese código.'),
                        
                        
			array('tbl_Periodos_idPeriodo, idtbl_objetivoproyecto, tipoproyecto, idtbl_adscrito, idtbl_estadoproyecto', 'numerical', 'integerOnly'=>true),
                    
			array('nombre', 'length', 'min'=>3, 'max'=>500, 'tooShort'=> 'El {attribute} debe ser mayor a {min} caracteres.', 'tooLong' => 'El {attribute} debe ser menor a {max} caracteres.'),
			array('codigo', 'length', 'min'=>2, 'max'=>20, 'tooShort'=> 'El {attribute} debe ser mayor a {min} caracteres.', 'tooLong' => 'El {attribute} debe ser menor a {max} caracteres.'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('idtbl_Proyectos, nombre, codigo, tbl_Periodos_idPeriodo', 'safe', 'on'=>'search'),
                        array('idtbl_Proyectos, nombre, codigo, $fecha_inicio_search, $fecha_fin_search', 'safe', 'on'=>'search'),
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
			'_estadoproyecto' => array(self::BELONGS_TO, 'Estadoproyecto', 'idtbl_estadoproyecto'),
			'_objetivoproyecto' => array(self::BELONGS_TO, 'Objetivoproyecto', 'idtbl_objetivoproyecto'),
			'periodos' => array(self::BELONGS_TO, 'Periodos', 'tbl_Periodos_idPeriodo'),
			'_tipoproyecto' => array(self::BELONGS_TO, 'Tipoproyecto', 'tipoproyecto'),
                        '_convenios' => array(self::MANY_MANY, 'Convenio', 'tbl_proyectos_convenio(idtbl_Proyectos, idtbl_convenio)'),
			'_sectorbeneficiados' => array(self::MANY_MANY, 'Sectorbeneficiado', 'tbl_proyectos_sectorbeneficiado(idtbl_Proyectos, idtbl_sectorbeneficiado)'),
			
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
			'tbl_Periodos_idPeriodo' => 'Id periodo',
                        'fecha_inicio_search' => 'Fecha Inicio',
                        'fecha_fin_search' => 'Fecha Final',
                        'idtbl_objetivoproyecto' => 'Objetivo del proyecto',
                        'tipoproyecto' => 'Tipo proyecto',
                        'idtbl_adscrito' => 'Adscrito a',
			'idtbl_estadoproyecto' => 'Estado del proyecto',                    
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
		//$criteria->compare('tbl_Periodos_idPeriodo',$this->tbl_Periodos_idPeriodo);
                $criteria->compare( 'periodos.inicio', $this->fecha_inicio_search, true );
                $criteria->compare( 'periodos.fin', $this->fecha_fin_search, true );
                $criteria->compare('idtbl_objetivoproyecto',$this->idtbl_objetivoproyecto);
		$criteria->compare('tipoproyecto',$this->tipoproyecto);
		$criteria->compare('idtbl_adscrito',$this->idtbl_adscrito);
		$criteria->compare('idtbl_estadoproyecto',$this->idtbl_estadoproyecto);

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

}
