<?php

/**
 * This is the model class for table "tbl_historialproyectosperiodos".
 *
 * The followings are the available columns in table 'tbl_historialproyectosperiodos':
 * @property integer $idtbl_Proyectos
 * @property integer $idPeriodo
 */
class HistorialProyectosPeriodo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return HistorialProyectosPeriodo the static model class
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
		return 'tbl_historialproyectosperiodos';
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
			array('idtbl_Proyectos, idPeriodo', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
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

		$criteria->compare('idtbl_Proyectos',$this->idtbl_Proyectos);
		$criteria->compare('idPeriodo',$this->idPeriodo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}