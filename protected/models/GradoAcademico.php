<?php

/**
 * This is the model class for table "tbl_gradosacademicos".
 *
 * The followings are the available columns in table 'tbl_gradosacademicos':
 * @property integer $idtbl_GradosAcademicos
 * @property string $nombre
 * @property string $abreviacion
 */
class GradoAcademico extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GradoAcademico the static model class
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
		return 'tbl_gradosacademicos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre, abreviacion', 'required'),
			array('nombre', 'length', 'max'=>15),
			array('abreviacion', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_GradosAcademicos, nombre, abreviacion', 'safe', 'on'=>'search'),
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
			'idtbl_GradosAcademicos' => 'Idtbl Grados Academicos',
			'nombre' => 'Nombre',
			'abreviacion' => 'Abreviacion',
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

		$criteria->compare('idtbl_GradosAcademicos',$this->idtbl_GradosAcademicos);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('abreviacion',$this->abreviacion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function __get($name) {
            if ($name == 'nombre-abreviacion')
                return $this->nombre . ' (' . $this->abreviacion . ')';
            else
                return parent::__get($name);
        }
}