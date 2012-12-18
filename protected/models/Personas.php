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
 * @property string $banco
 * @property string $cuentacliente
 *
 * The followings are the available model relations:
 * @property Proyectos[] $tblProyectoses
 * @property Contactos[] $contactoses
 */
class Personas extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Personas the static model class
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
			array('nombre, apellido1, cedula, numerocuenta, banco, cuentacliente', 'required'),
			array('nombre, apellido1, apellido2, cedula', 'length', 'max'=>20),
			array('numerocuenta', 'length', 'max'=>30),
			array('banco', 'length', 'max'=>50),
			array('cuentacliente', 'length', 'max'=>17),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_Personas, nombre, apellido1, apellido2, cedula, numerocuenta, banco, cuentacliente', 'safe', 'on'=>'search'),
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
			'tblProyectoses' => array(self::MANY_MANY, 'Proyectos', 'tbl_profesoresproyectos(tbl_Personas_idtbl_Personas, tbl_Proyectos_idtbl_Proyectos)'),
			'contactoses' => array(self::HAS_MANY, 'Contactos', 'tbl_Personas_idtbl_Personas'),
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
			'banco' => 'Banco',
			'cuentacliente' => 'Cuentacliente',
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
		$criteria->compare('banco',$this->banco,true);
		$criteria->compare('cuentacliente',$this->cuentacliente,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}