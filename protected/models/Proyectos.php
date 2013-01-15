<?php

/**
 * This is the model class for table "tbl_proyectos".
 *
 * The followings are the available columns in table 'tbl_proyectos':
 * @property integer $idtbl_Proyectos
 * @property string $nombre
 * @property string $codigo
 * @property integer $tbl_Periodos_idPeriodo
 *
 * The followings are the available model relations:
 * @property Personas[] $tblPersonases
 * @property Periodos $tblPeriodosIdPeriodo
 */
class Proyectos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Proyectos the static model class
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
		return 'tbl_proyectos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('nombre, codigo','required', 'message' => '{attribute} es requerido.'),
                        //array('nombre', 'unique', 'className' => 'Proyectos', 'message' => 'Ya existe un proyecto con ese nombre.'),
                        array('codigo', 'unique', 'className' => 'Proyectos', 'message' => 'Ya existe un proyecto con ese código.'),			
			array('nombre', 'length', 'min'=>3, 'max'=>500, 'tooShort'=> 'El {attribute} debe ser mayor a {min} caracteres.', 'tooLong' => 'El {attribute} debe ser menor a {max} caracteres.'),
			array('codigo', 'length', 'min'=>2, 'max'=>20, 'tooShort'=> 'El {attribute} debe ser mayor a {min} caracteres.', 'tooLong' => 'El {attribute} debe ser menor a {max} caracteres.'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('idtbl_Proyectos, nombre, codigo, tbl_Periodos_idPeriodo', 'safe', 'on'=>'search'),
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
			'tblPersonases' => array(self::MANY_MANY, 'Personas', 'tbl_profesoresproyectos(tbl_Proyectos_idtbl_Proyectos, tbl_Personas_idtbl_Personas)'),
			'periodos' => array(self::BELONGS_TO, 'Periodos', 'tbl_Periodos_idPeriodo'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_Proyectos' => 'Id proyecto',
			'nombre' => 'Nombre del proyecto',
			'codigo' => 'Código del proyecto',
			'tbl_Periodos_idPeriodo' => 'Id periodo',
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
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('codigo',$this->codigo,true);
		$criteria->compare('tbl_Periodos_idPeriodo',$this->tbl_Periodos_idPeriodo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function agregarAsistenteProyecto($pidproyecto, $pcarnet, $pidrol, $pfechaini, $phoras) {
            $conexion = Yii::app()->db;
            $call = 'CALL agregarAsistenteProyecto(:idproyecto,:carnet,:idrol,:fechaini,:horas)';
            $transaccion = Yii::app()->db->beginTransaction();
            try {
                $comando = $conexion->createCommand($call);
                $comando->bindParam(':idproyecto', $pidproyecto);
                $comando->bindParam(':carnet', $pcarnet);
                $comando->bindParam(':idrol', $pidrol);
                $comando->bindParam(':fechaini', $pfechaini);                              
                $comando->bindParam(':horas', $phoras);               
                $comando->execute();
                $transaccion->commit();
            } catch (Exception $e) {
                $transaccion->rollback();               
                return false;
            }
            return true;
        }

}