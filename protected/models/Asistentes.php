<?php

/**
 * This is the model class for table "tbl_asistentes".
 *
 * The followings are the available columns in table 'tbl_asistentes':
 * @property integer $idtbl_Asistentes
 * @property string $carnet
 * @property integer $idtbl_Carreras
 * @property integer $idtbl_Personas
 *
 * The followings are the available model relations:
 * @property Carreras $idtblCarreras
 * @property Personas $idtblPersonas
 * @property Proyectos[] $tblProyectoses
 */
class Asistentes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Asistentes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_asistentes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('carnet, idtbl_Carreras, idtbl_Personas', 'required'),
			array('idtbl_Carreras, idtbl_Personas', 'numerical', 'integerOnly'=>true),
			array('carnet', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_Asistentes, carnet, idtbl_Carreras, idtbl_Personas', 'safe', 'on'=>'search'),
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
			'idtblCarreras' => array(self::BELONGS_TO, 'Carreras', 'idtbl_Carreras'),
			'idtblPersonas' => array(self::BELONGS_TO, 'Personas', 'idtbl_Personas'),
			'proyectos' => array(self::MANY_MANY, 'Proyectos', 'tbl_asistentes_has_tbl_proyectos(idtbl_Asistentes, idtbl_Proyectos)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_Asistentes' => 'Idtbl Asistentes',
			'carnet' => 'Carnet',
			'idtbl_Carreras' => 'Idtbl Carreras',
			'idtbl_Personas' => 'Idtbl Personas',
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

		$criteria->compare('idtbl_Asistentes',$this->idtbl_Asistentes);
		$criteria->compare('carnet',$this->carnet,true);
		$criteria->compare('idtbl_Carreras',$this->idtbl_Carreras);
		$criteria->compare('idtbl_Personas',$this->idtbl_Personas);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function verificarHorasAcumuladasProyectos($pidAsistente) {
        $conexion = Yii::app()->db;
        $call = 'CALL verificarHorasAcumuladasProyectos(:pidAsistente)';
            $transaccion = Yii::app()->db->beginTransaction();
            $resultado = NULL;
            try {
                $comando = $conexion->createCommand($call);
                $comando->bindParam(':pidAsistente', $pidAsistente);
                $resultado = $comando->query();
                $transaccion->commit();
            } catch (Exception $e) {
                $transaccion->rollback();
                echo $e->getMessage();
                return NULL;
            }
            
            return $resultado->rowCount === 1 ? $resultado->read() : NULL;
            
    }
}