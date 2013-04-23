<?php

/**
 * This is the model class for table "tbl_proyectos_sectorbeneficiado".
 *
 * The followings are the available columns in table 'tbl_proyectos_sectorbeneficiado':
 * @property integer $idtbl_Proyectos
 * @property integer $idtbl_sectorbeneficiado
 */
class ProyectosSectorbeneficiado extends CActiveRecord {

    // Rules, relations, attribute labels, search
// <editor-fold defaultstate="collapsed" desc="Yii functions">
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

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Functions">

    /*
     * Agrega uno o más sectores beneficiados a un proyecto
     * IMPORTANTE: se asume que es invocado desde una transacción activa
     * @param Integer $pIdProyecto id del proyecto al que se asocian sectores
     * @param Array[Integer] $pIdsSectoresBeneficiados
     * @return Boolean verdadero si se ejecutó correctamente, falso sino
     */
    public function saveAllBenefitedSectors($pIdProyecto, $pIdsSectoresBeneficiados) {
        $resultadoSector = true;
        foreach ($pIdsSectoresBeneficiados as $sector) {
            $is_sector_saved = ProyectosSectorbeneficiado::addBenefitedSector(
                            $pIdProyecto, $sector);
            $resultadoSector = ($is_sector_saved == 1) && $resultadoSector;
        }
        return $resultadoSector;
    }

    /*
     * Actualiza los sectores beneficiados asociados a un proyecto
     *  |-borra los no estén en $pNuevosSectores e inserta los nuevos valores
     * @param Integer $pIdProyecto id del proyecto a actualizar
     * $param Array[Integer $pAntiguosSectores
     * $param Array[Integer $pNuevosSectores
     * $return Boolean true si se ejecutó correctamente, en otro caso, false
     */

    public function updateBenefitedSectors($pIdProyecto, $pAntiguosSectores, $pNuevosSectores) {
        if (!is_array($pNuevosSectores)) //caso en el que no se han ingresado nuevos sectores
            return true;
        if (is_array($pNuevosSectores[0]))
            return true;
        
        if (is_array($pAntiguosSectores)) { //si no hay sectores beneficiados, no es un arreglo
            
            $antiguos_sectores = array();
            
            foreach ($pAntiguosSectores as $sector_antiguo){
                array_push($antiguos_sectores,$sector_antiguo["idtbl_sectorbeneficiado"]);
            }
            
            $sectores_a_borrar = array_diff($antiguos_sectores,$pNuevosSectores); //array_udiff($pAntiguosSectores, $pNuevosSectores, 'ProyectosSectorbeneficiado::compareSectorArrayWithId');
            $sectores_a_insertar = array_diff($pNuevosSectores, $antiguos_sectores); //array_udiff($pNuevosSectores, $pAntiguosSectores, 'ProyectosSectorbeneficiado::compareIdWithSectorArray');
            
            /*$sectoresInsertar = implode(",", $sectores_a_insertar);
            Yii::log("**Sectores a insertar " . $sectoresInsertar , "info", "application.
                models.ProyectosSectorBeneficiado");*/
        }else
            $sectores_a_insertar = $pNuevosSectores;

        $transaccion = Yii::app()->db->beginTransaction();

        if(isset($sectores_a_borrar)){
            $sectoresBorrar = implode(",",$sectores_a_borrar);
            Yii::log("Sectores a borrar " . $sectoresBorrar , "info", "application.
                models.ProyectosSectorBeneficiado");
        }
        
        
        
        
        if (isset($sectores_a_borrar))//verificacion por si no habian sectores beneficiados antes
            $resultado_borrar =
                    ProyectosSectorbeneficiado::deleteBenefitedSectors($pIdProyecto, $sectores_a_borrar);
        else
            $resultado_borrar = true;

        $resultado_insertar = // true;
                ProyectosSectorbeneficiado::saveAllBenefitedSectors($pIdProyecto, $sectores_a_insertar);

        if ($resultado_borrar && $resultado_insertar) {
            $transaccion->commit();
            Yii::log("Cambio exitoso en sectores beneficiados del proyecto: " . $pIdProyecto, "info", "application.
                models.ProyectosSectorBeneficiado");
            return true;
        } else {
            Yii::log("Fallo al actualizar sectores beneficiados del proyecto: " . $pIdProyecto, "warning", "application.
                models.ProyectosSectorBeneficiado");
            $transaccion->rollback;
            return false;
        }
    }

    /*
     * Compara el valor del campo idtbl_sectorbeneficiado de $pSectorsArray con $pIdSector
     * @param Array $pSectorsArray arreglo de la forma array("idtbl_sectorbeneficiado"=>"valor","nombre"=>nombre
     * @param Array $pIdSector
     * @returns Boolean resultado de la comparación
     */

    public function compareSectorArrayWithId($pSectorsArray, $pIdSector) {
        if (is_array($pSectorsArray))
            if ($pSectorsArray["idtbl_sectorbeneficiado"] == $pIdSector){
                return 0; //indica que son iguales
            }
            else{
                return 1;
                }
        else
            return 1;
    }

    public function compareIdWithSectorArray($pIdSector, $pSectorsArray) {
        if (is_array($pSectorsArray))
            if ($pIdSector == $pSectorsArray["idtbl_sectorbeneficiado"]){
                return 0; //indica que son iguales
            }else if(is_array($pIdSector)){
                return 0;
            }
            else{
                if(is_array($pIdSector))
                    $arrcomma = implode(",", $pIdSector);
                else $arrcomma = $pIdSector;
                
                Yii::log("++son diferentes ". $arrcomma . " y " . $pSectorsArray["idtbl_sectorbeneficiado"] , "info", "application.
                models.ProyectosSectorBeneficiado");
                return 1;
            }
                
        else{//pSectorsArray no es un array
            if($pSectorsArray == $pIdSector)
                return 0;
            else
                return 1;
            
            }
    }

    /*
     * Agrega un sector beneficiado a un proyecto
     * IMPORTANTE: debe ser llamado desde una transacción (se asume que es así)
     * @param Integer $pIdProyecto id del proyecto a insertar
     * @param Integer $pIdSectorBeneficiado id del serctor a insertar
     * @return Integer number of affected rows
     */

    private function addBenefitedSector($pIdProyecto, $pIdSectorBeneficiado) {
        $conexion = Yii::app()->db;
        $call = 'CALL agregarSectorBeneficiado(:pIdProyecto,:pIdSector)';
        $comando = $conexion->createCommand($call);
        $comando->bindParam(':pIdProyecto', $pIdProyecto);
        $comando->bindParam(':pIdSector', $pIdSectorBeneficiado);
        return $comando->execute();
    }

    /*
     * Elimina la asociación entre un proyecto y N sectores
     * IMPORTANTE: se asume que se invoca desde una transaccion activa
     * @param Integer $pIdProyecto id del proyecto
     * //cambiar la forma del parametro que sigue por un entero normal
     * @param Array[Sector] sector tiene la forma array("idtbl_sectorbeneficiado"=>"valor","nombre"=>nombre
     * @return Boolean verdadero si se ejecutó correctamente, falso sino
     */

    private function deleteBenefitedSectors($pIdProyecto, $pSectors) {
        $resultadoSector = true;
        foreach ($pSectors as $sector) {
            $is_sector_saved = ProyectosSectorbeneficiado::deleteBenefitedSector(
                            $pIdProyecto, $sector);
            $resultadoSector = ($is_sector_saved == 1) && $resultadoSector; //valida que sólo borre 1
        }
        return $resultadoSector;
    }

    /*
     * Elimina la asociación entre un proyecto y un sector beneficiado
     * IMPORTANTE: se asume que se llama desde una transacción activa
     * @param Integer $pIdProyecto id del proyecto
     * @param Integer $pIdSector id del sector
     * @return Integer number of affected rows
     */

    private function deleteBenefitedSector($pIdProyecto, $pIdSector) {
        $conexion = Yii::app()->db;
        $call = 'CALL borrarSectorBeneficiadoAsociadoAProyecto(:pIdProyecto,:pIdSector)';
        $comando = $conexion->createCommand($call);
        $comando->bindParam(':pIdProyecto', $pIdProyecto);
        $comando->bindParam(':pIdSector', $pIdSector);
        return $comando->execute();
    }

// </editor-fold>
}