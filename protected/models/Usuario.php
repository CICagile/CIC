<?php

/**
 * This is the model class for table "tbl_usuario".
 *
 * The followings are the available columns in table 'tbl_usuario':
 * @property integer $idtbl_usuario
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $idtbl_rolusuario
 *
 * The followings are the available model relations:
 * @property Rolusuario $idtblRolusuario
 */
class Usuario extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Usuario the static model class
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
		return 'tbl_usuario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, idtbl_rolusuario', 'required'),
			array('idtbl_rolusuario', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>100),
			array('password', 'length', 'max'=>64),
			array('email', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_usuario, username, password, email, idtbl_rolusuario', 'safe', 'on'=>'search'),
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
			'idtblRolusuario' => array(self::BELONGS_TO, 'Rolusuario', 'idtbl_rolusuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_usuario' => 'Idtbl Usuario',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'idtbl_rolusuario' => 'Idtbl Rolusuario',
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

		$criteria->compare('idtbl_usuario',$this->idtbl_usuario);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('idtbl_rolusuario',$this->idtbl_rolusuario);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}