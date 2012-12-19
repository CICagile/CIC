<?php

/**
 * This is the model class for table "tbl_proyectos".
 *
 * The followings are the available columns in table 'tbl_proyectos':
 * @property integer $idtbl_Proyectos
 * @property string $nombre
 * @property string $codigo
 * @property integer $tbl_Periodos_idPeriodo
 *
 * The followings are the available model relations:
 * @property Personas[] $tblPersonases
 * @property Periodos $tblPeriodosIdPeriodo
 */
class Proyectos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Proyectos the static model class
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
                        array('nombre, codigo','required'),			
			array('tbl_Periodos_idPeriodo', 'numerical', 'integerOnly'=>true),
			array('nombre', 'length', 'max'=>250),
			array('codigo', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_Proyectos, nombre, codigo, tbl_Periodos_idPeriodo', 'safe', 'on'=>'search'),
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
			'tblPersonases' => array(self::MANY_MANY, 'Personas', 'tbl_profesoresproyectos(tbl_Proyectos_idtbl_Proyectos, tbl_Personas_idtbl_Personas)'),
			'tblPeriodosIdPeriodo' => array(self::BELONGS_TO, 'Periodos', 'tbl_Periodos_idPeriodo'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_Proyectos' => 'Idtbl Proyectos',
			'nombre' => 'Nombre del Proyecto',
			'codigo' => 'Código',
			'tbl_Periodos_idPeriodo' => 'Tbl Periodos Id Periodo',
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

		$criteria->compare('idtbl_Proyectos',$this->idtbl_Proyectos);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('codigo',$this->codigo,true);
		$criteria->compare('tbl_Periodos_idPeriodo',$this->tbl_Periodos_idPeriodo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}