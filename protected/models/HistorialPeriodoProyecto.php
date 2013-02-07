<?php

/**
 * This is the model class for table "tbl_historialperiodoproyecto".
 *
 * The followings are the available columns in table 'tbl_historialperiodoproyecto':
 * @property integer $idtbl_historialperiodoproyecto
 * @property integer $idtbl_Proyectos
 * @property integer $idPeriodo
 *
 * The followings are the available model relations:
 * @property Proyectos $idtblProyectos
 * @property Periodos $idPeriodo0
 */
class HistorialPeriodoProyecto extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return HistorialPeriodoProyecto the static model class
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
		return 'tbl_historialperiodoproyecto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idtbl_Proyectos, idPeriodo', 'required'),
			array('idtbl_Proyectos, idPeriodo', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_historialperiodoproyecto, idtbl_Proyectos, idPeriodo', 'safe', 'on'=>'search'),
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
			'_proyectos' => array(self::BELONGS_TO, 'Proyectos', 'idtbl_Proyectos'),
			'_periodos' => array(self::BELONGS_TO, 'Periodos', 'idPeriodo'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_historialperiodoproyecto' => 'Idtbl Historialperiodoproyecto',
			'idtbl_Proyectos' => 'Idtbl Proyectos',
			'idPeriodo' => 'Id Periodo',
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

		$criteria->compare('idtbl_historialperiodoproyecto',$this->idtbl_historialperiodoproyecto);
		$criteria->compare('idtbl_Proyectos',$this->idtbl_Proyectos);
		$criteria->compare('idPeriodo',$this->idPeriodo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}