<?php

/**
 * This is the model class for table "tbl_personas".
 *
 * The followings are the available columns in table 'tbl_personas':
 * @property integer $idtbl_Personas
 * @property string $nombre
 * @property string $apellido1
 * @property string $apellido2
 * @property string $cedula
 * @property string $numerocuenta
 * @property string $cuentacliente
 * @property integer $idtbl_Bancos
 */
class Persona extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Persona the static model class
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
		return 'tbl_personas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, apellido1, cedula, numerocuenta, cuentacliente, idtbl_Bancos', 'required'),
			array('idtbl_Bancos', 'numerical', 'integerOnly'=>true),
			array('nombre, apellido1, apellido2, cedula', 'length', 'max'=>20),
			array('numerocuenta', 'length', 'max'=>30),
			array('cuentacliente', 'length', 'max'=>17),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_Personas, nombre, apellido1, apellido2, cedula, numerocuenta, cuentacliente, idtbl_Bancos', 'safe', 'on'=>'search'),
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
			'idtbl_Personas' => 'Idtbl Personas',
			'nombre' => 'Nombre',
			'apellido1' => 'Apellido1',
			'apellido2' => 'Apellido2',
			'cedula' => 'Cedula',
			'numerocuenta' => 'Numerocuenta',
			'cuentacliente' => 'Cuentacliente',
			'idtbl_Bancos' => 'Idtbl Bancos',
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

		$criteria->compare('idtbl_Personas',$this->idtbl_Personas);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('apellido1',$this->apellido1,true);
		$criteria->compare('apellido2',$this->apellido2,true);
		$criteria->compare('cedula',$this->cedula,true);
		$criteria->compare('numerocuenta',$this->numerocuenta,true);
		$criteria->compare('cuentacliente',$this->cuentacliente,true);
		$criteria->compare('idtbl_Bancos',$this->idtbl_Bancos);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
