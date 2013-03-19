<?php

/**
 * This is the model class for table "tbl_sectorbeneficiado".
 *
 * The followings are the available columns in table 'tbl_sectorbeneficiado':
 * @property integer $idtbl_sectorbeneficiado
 * @property string $nombre
 *
 * The followings are the available model relations:
 * @property Proyectos[] $tblProyectoses
 */
class SectorBeneficiado extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SectorBeneficiado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        
        public function __toString() {
            return 'nombre';
        }
        
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_sectorbeneficiado';
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
			array('nombre', 'length', 'min'=>3, 'max'=>250, 'tooShort'=> 'El {attribute} debe ser mayor a {min} caracteres.', 'tooLong' => 'El {attribute} debe ser menor a {max} caracteres.'),
			array('idtbl_sectorbeneficiado, nombre', 'safe', 'on'=>'search'),
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
			'tblProyectoses' => array(self::MANY_MANY, 'Proyectos', 'tbl_proyectos_sectorbeneficiado(idtbl_sectorbeneficiado, idtbl_Proyectos)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'idtbl_sectorbeneficiado' => 'Idtbl Sectorbeneficiado',
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

		$criteria->compare('idtbl_sectorbeneficiado',$this->idtbl_sectorbeneficiado);
		$criteria->compare('nombre',$this->nombre,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        // <editor-fold defaultstate="collapsed" desc="Data fetching functions">
        
        /*
         * Retrieves a list of "SectorBeneficiado" rows from tbl_sectorbeneficiado, according to a project id
         * @param $pProjectId Integer: the project's primary key
         * @returns an array which contains the list of "SectorBeneficiado"
         */
        
        //-
        public static function getBenefitedSectorByProjectId($pProjectId){
            $criteria = new CDbCriteria;
            $criteria->select='t.nombre';
            $criteria->condition='idtbl_sectorbeneficiado=:postID';
            $criteria->params=array(':postID'=>$pProjectId);
            return SectorBeneficiado::model()->find($criteria);
        }
        //-
        
        // </editor-fold>

}