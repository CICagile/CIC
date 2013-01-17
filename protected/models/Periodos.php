<?php

/**
 * This is the model class for table "tbl_periodos".
 *
 * The followings are the available columns in table 'tbl_periodos':
 * @property integer $idPeriodo
 * @property string $inicio
 * @property string $fin
 *
 * The followings are the available model relations:
 * @property Historialeshorasasistente[] $historialeshorasasistentes
 * @property Historialperiodosasistentesxproyecto[] $historialperiodosasistentesxproyectos
 * @property Historialroles[] $historialroles
 * @property Proyectos[] $proyectoses
 */
class Periodos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Periodos the static model class
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
		return 'tbl_periodos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('inicio, fin', 'required', 'message' => 'La {attribute} es requerido.'),
                        array('inicio', 'date', 'format'=> 'dd-MM-yyyy'),
                        array('fin', 'date', 'format'=> 'dd-MM-yyyy'),
                        array('fin','compare', 'compareAttribute' => 'inicio', 'operator' => '!=', 'message' => 'La {attribute} debe ser distinto a la fecha de inicio.'),
                        //array('fin','compare', 'compareAttribute' => 'inicio', 'operator' => '>', 'message' => 'La {attribute} debe ser mayor a la fecha de inicio.'),                        
                        array('fin', 'ValidadorFechaMayor'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idPeriodo, inicio, fin', 'safe', 'on'=>'search'),
		);
	}       
        
        public function ValidadorFechaMayor($attribute, $params)
        {
            if(strtotime($this->fin) < strtotime($this->inicio)) {
                $this->addError($attribute,'La fecha de finalización del proyecto no puede ser menor que la fecha de inicio.');
            }            
        }

        	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'historialeshorasasistentes' => array(self::HAS_MANY, 'Historialeshorasasistente', 'tbl_Periodos_idPeriodo'),
			'historialperiodosasistentesxproyectos' => array(self::HAS_MANY, 'Historialperiodosasistentesxproyecto', 'tbl_Periodos_idPeriodo'),
			'historialroles' => array(self::HAS_MANY, 'Historialroles', 'tbl_Periodos_idPeriodo'),
			'proyectos' => array(self::HAS_MANY, 'Proyectos', 'tbl_Periodos_idPeriodo'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idPeriodo' => 'Id periodo',
			'inicio' => 'Fecha inicio',
			'fin' => 'Fecha fin',
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

		$criteria->compare('idPeriodo',$this->idPeriodo);
		$criteria->compare('inicio',$this->inicio,true);
		$criteria->compare('fin',$this->fin,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}