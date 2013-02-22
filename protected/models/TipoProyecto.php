<?php

/**
 * This is the model class for table "tbl_tipoproyecto".
 *
 * The followings are the available columns in table 'tbl_tipoproyecto':
 * @property integer $idtbl_tipoproyecto
 * @property string $nombre
 *
 * The followings are the available model relations:
 * @property Proyectos[] $proyectoses
 */
class TipoProyecto extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TipoProyecto the static model class
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
		return 'tbl_tipoproyecto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idtbl_tipoproyecto, nombre', 'required'),
			array('nombre', 'length', 'max'=>45),
                        array('idtbl_tipoproyecto', 'numerical', 'integerOnly'=>true),
                        array('nombre', 'unique', 'className' => 'TipoProyecto', 'caseSensitive' => true, 'message' => 'Ya existe esa adscripción.'), 
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_tipoproyecto, nombre', 'safe', 'on'=>'search'),
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
			'proyectoses' => array(self::HAS_MANY, 'Proyectos', 'tipoproyecto'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_tipoproyecto' => 'Idtbl Tipoproyecto',
			'nombre' => 'Tipo de proyecto',
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

		$criteria->compare('idtbl_tipoproyecto',$this->idtbl_tipoproyecto);
		$criteria->compare('nombre',$this->nombre,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}