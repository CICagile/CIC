<?php

/**
 * This is the model class for table "tbl_proyectos_sectorbeneficiado".
 *
 * The followings are the available columns in table 'tbl_proyectos_sectorbeneficiado':
 * @property integer $idtbl_Proyectos
 * @property integer $idtbl_sectorbeneficiado
 */
class ProyectosSectorbeneficiado extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProyectosSectorbeneficiado the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_proyectos_sectorbeneficiado';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('idtbl_Proyectos, idtbl_sectorbeneficiado', 'required'),
            array('idtbl_Proyectos, idtbl_sectorbeneficiado', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('idtbl_Proyectos, idtbl_sectorbeneficiado', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'idtbl_Proyectos' => 'Idtbl Proyectos',
            'idtbl_sectorbeneficiado' => 'Idtbl Sectorbeneficiado',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('idtbl_Proyectos', $this->idtbl_Proyectos);
        $criteria->compare('idtbl_sectorbeneficiado', $this->idtbl_sectorbeneficiado);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /* Function overload for save, this one is transactional
     * @param integer $pIdProyecto the project id
     * @param integer $pIdSectorBeneficiado the Sector's id
     * @return: the result from the save() function
     */

    public function saveBenefiedSector($pIdProyecto, $pIdSectorBeneficiado) {
        $this->idtbl_Proyectos = $pIdProyecto;
        $this->idtbl_sectorbeneficiado = $pIdSectorBeneficiado;
        $isSaveOk = $this->save();
        return $isSaveOk;
    }
    
    /*
     * Agrega sectores beneficiados a un proyecto
     * IMPORTANTE: debe ser llamado desde una transacción (se asume que es así)
     * @param Integer $pIdProyecto id del proyecto a insertar
     * @param Integer $pIdSectorBeneficiado id del serctor a insertar
     * @return Integer number of affected rows
     */
    public function addBenefitedSector($pIdProyecto, $pIdSectorBeneficiado){
        $conexion = Yii::app()->db;
        $call = 'CALL agregarSectorBeneficiado(:pIdProyecto,:pIdSector)';
        $comando = $conexion->createCommand($call);
        $comando->bindParam(':pIdProyecto', $pIdProyecto);
        $comando->bindParam(':pIdSector', $pIdSectorBeneficiado);
        return $comando->execute();
    }

}