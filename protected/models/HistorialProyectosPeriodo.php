<?php

/**
 * This is the model class for table "tbl_historialproyectosperiodos".
 *
 * The followings are the available columns in table 'tbl_historialproyectosperiodos':
 * @property integer $idtbl_historialproyectosperiodos
 * @property integer $idtbl_Proyectos
 * @property integer $idPeriodo
 * @property integer $idtbl_EstadosProyecto
 *
 * The followings are the available model relations:
 * @property Detalleestadoproyecto[] $detalleestadoproyectos
 * @property Estadosproyecto $idtblEstadosProyecto
 * @property Periodos $idPeriodo0
 * @property Proyectos $idtblProyectos
 */
class HistorialProyectosPeriodo extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return HistorialProyectosPeriodo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_historialproyectosperiodos';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('idtbl_Proyectos, idPeriodo', 'required'),
            array('idtbl_EstadosProyecto', 'required', 'on'=>'crear'),
            array('idtbl_Proyectos, idPeriodo, idtbl_EstadosProyecto', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('idtbl_historialproyectosperiodos, idtbl_Proyectos, idPeriodo, idtbl_EstadosProyecto', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'detalleestadoproyectos' => array(self::HAS_MANY, 'Detalleestadoproyecto', 'idtbl_historialproyectosperiodos'),
            'idtblEstadosProyecto' => array(self::BELONGS_TO, 'Estadosproyecto', 'idtbl_EstadosProyecto'),
            'idPeriodo0' => array(self::BELONGS_TO, 'Periodos', 'idPeriodo'),
            'idtblProyectos' => array(self::BELONGS_TO, 'Proyectos', 'idtbl_Proyectos'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'idtbl_historialproyectosperiodos' => 'Idtbl Historialproyectosperiodos',
            'idtbl_Proyectos' => 'Idtbl Proyectos',
            'idPeriodo' => 'Id Periodo',
            'idtbl_EstadosProyecto' => 'Idtbl Estados Proyecto',
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

        $criteria->compare('idtbl_historialproyectosperiodos', $this->idtbl_historialproyectosperiodos);
        $criteria->compare('idtbl_Proyectos', $this->idtbl_Proyectos);
        $criteria->compare('idPeriodo', $this->idPeriodo);
        $criteria->compare('idtbl_EstadosProyecto', $this->idtbl_EstadosProyecto);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Obtiene el id de un estado de la tabla tbl_estadosproyecto, a partir de un nombre
     * @param type $pNombreEstado
     * @return id del estado, o nulo si es un error 
     */
    public function obtenerIdEstadoPeriodoProyecto($pNombreEstado) {
        $conexion = Yii::app()->db;
        $call = 'CALL obtenerIdEstadoPeriodoProyecto(:pNombreEstado)';
        
        $command = $conexion->createCommand($call);
        $command->bindParam(':pNombreEstado', $pNombreEstado, PDO::PARAM_STR);
        $result = $command->queryRow();
        
        if($result != false){
            return $result["idtbl_EstadosProyecto"];
        }else{
            Yii::log("Error al buscar un id de estado de periodo con nombre" . $pNombreEstado, "error", "application.models.HistorialProyectosPeriodo");
            return null;
        }
    }
    
    
    public function agregarHistorialAProyecto($pIdProyecto, $pFechaInicial, $pFechaFinal, $pNombreEstado){
         
        $conexion = Yii::app()->db;
        $call = 'CALL agregarHistorialProyectosPeriodo(:pIdProyecto, :pFechaInicial, :pFechaFinal, :pNombreEstado)';

        $command = $conexion->createCommand($call);
        $command->bindParam(':pIdProyecto', $pIdProyecto, PDO::PARAM_INT);
        $command->bindParam(':pFechaInicial', $pFechaInicial);
        $command->bindParam(':pFechaFinal', $pFechaFinal);
        $command->bindParam(':pNombreEstado', $pNombreEstado, PDO::PARAM_STR);
        $rows_affected = $command->execute();
        
        if($rows_affected <= 0){
            Yii::log("Error al agregar nuevo historial a proyecto. IdProyecto:" . $pIdProyecto , "error", "application.models.HistorialProyectosPeriodo");
            return false;
        }
        else 
            return true;
    }
}