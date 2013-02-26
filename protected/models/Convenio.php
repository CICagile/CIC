<?php

/**
 * This is the model class for table "tbl_convenio".
 *
 * The followings are the available columns in table 'tbl_convenio':
 * @property integer $idtbl_convenio
 * @property string $nombre
 *
 * The followings are the available model relations:
 * @property Financiamientoexterno[] $financiamientoexternos
 * @property Proyectos[] $tblProyectoses
 */
class Convenio extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Convenio the static model class
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
		return 'tbl_convenio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre', 'required'),
			array('nombre', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_convenio, nombre', 'safe', 'on'=>'search'),
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
			'financiamientoexternos' => array(self::HAS_MANY, 'Financiamientoexterno', 'idtbl_convenio'),
			'tblProyectoses' => array(self::MANY_MANY, 'Proyectos', 'tbl_proyectos_convenio(idtbl_convenio, idtbl_Proyectos)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_convenio' => 'Idtbl Convenio',
			'nombre' => 'Nombre',
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

		$criteria->compare('idtbl_convenio',$this->idtbl_convenio);
		$criteria->compare('nombre',$this->nombre,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}