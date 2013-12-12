<?php

/**
 * This is the model class for table "tbl_rolusuario".
 *
 * The followings are the available columns in table 'tbl_rolusuario':
 * @property integer $idtbl_rolusuario
 * @property string $rol
 *
 * The followings are the available model relations:
 * @property Usuario[] $usuarios
 */
class RolUsuario extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RolUsuario the static model class
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
		return 'tbl_rolusuario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rol', 'required'),
			array('rol', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_rolusuario, rol', 'safe', 'on'=>'search'),
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
			'usuarios' => array(self::HAS_MANY, 'Usuario', 'idtbl_rolusuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_rolusuario' => 'Idtbl Rolusuario',
			'rol' => 'Rol',
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

		$criteria->compare('idtbl_rolusuario',$this->idtbl_rolusuario);
		$criteria->compare('rol',$this->rol,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}