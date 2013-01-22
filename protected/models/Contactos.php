<?php

/**
 * This is the model class for table "tbl_contactos".
 *
 * The followings are the available columns in table 'tbl_contactos':
 * @property integer $idtbl_Contactos
 * @property string $valor
 * @property integer $tbl_Personas_idtbl_Personas
 * @property integer $tbl_TiposContacto_idtbl_TiposContacto
 *
 * The followings are the available model relations:
 * @property Personas $tblPersonasIdtblPersonas
 * @property Tiposcontacto $tblTiposContactoIdtblTiposContacto
 */
class Contactos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Contactos the static model class
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
		return 'tbl_contactos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('valor, tbl_Personas_idtbl_Personas, tbl_TiposContacto_idtbl_TiposContacto', 'required'),
			array('tbl_Personas_idtbl_Personas, tbl_TiposContacto_idtbl_TiposContacto', 'numerical', 'integerOnly'=>true),
			array('valor', 'length', 'max'=>25),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_Contactos, valor, tbl_Personas_idtbl_Personas, tbl_TiposContacto_idtbl_TiposContacto', 'safe', 'on'=>'search'),
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
			'tblPersonasIdtblPersonas' => array(self::BELONGS_TO, 'Personas', 'tbl_Personas_idtbl_Personas'),
			'tblTiposContactoIdtblTiposContacto' => array(self::BELONGS_TO, 'Tiposcontacto', 'tbl_TiposContacto_idtbl_TiposContacto'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_Contactos' => 'Idtbl Contactos',
			'valor' => 'Valor',
			'tbl_Personas_idtbl_Personas' => 'Tbl Personas Idtbl Personas',
			'tbl_TiposContacto_idtbl_TiposContacto' => 'Tbl Tipos Contacto Idtbl Tipos Contacto',
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

		$criteria->compare('idtbl_Contactos',$this->idtbl_Contactos);
		$criteria->compare('valor',$this->valor,true);
		$criteria->compare('tbl_Personas_idtbl_Personas',$this->tbl_Personas_idtbl_Personas);
		$criteria->compare('tbl_TiposContacto_idtbl_TiposContacto',$this->tbl_TiposContacto_idtbl_TiposContacto);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}