<?php

/**
 * This is the model class for table "tbl_proyectos".
 *
 * The followings are the available columns in table 'tbl_proyectos':
 * @property integer $idtbl_Proyectos
 * @property string $nombre
 * @property string $codigo

 * @property integer $idtbl_objetivoproyecto
 * @property integer $tipoproyecto
 * @property integer $idtbl_adscrito
 * @property integer $estado
 *
 * The followings are the available model relations:
 * @property Asistentes[] $tblAsistentes
 * @property Documentos[] $documentoses
 * @property Financiamientoexterno[] $financiamientoexternos
 * @property Periodos[] $_periodos
 * @property Presupuesto[] $presupuestos
 * @property Adscrito $idtblAdscrito
 * @property Objetivoproyecto $idtblObjetivoproyecto
 * @property Tipoproyecto $tipoproyecto0
 * @property Convenio[] $tblConvenios
 * @property Sectorbeneficiado[] $tblSectorbeneficiados
 * 
 * Para estado del proyecto 0 es Aprobado, 1 Ampliado
 * 
 */
class Proyectos extends CActiveRecord {

// <editor-fold defaultstate="collapsed" desc="Properties">
    public $fecha_inicio_search;
    public $fecha_fin_search;
    //Array Integer|String : almacena valores de sectores beneficiados
    public $idtbl_sectorbeneficiado;
    //Las variables fecha inicio y fecha fin se utilizan para simular el periodo del proyecto.
    public $idperiodo;
    public $inicio;
    public $fin;

// </editor-fold>
// Rules, relations, attribute labels, search
// <editor-fold defaultstate="collapsed" desc="Yii functions">
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Proyectos the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_proyectos';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nombre, codigo, idtbl_objetivoproyecto, tipoproyecto, idtbl_adscrito, idtbl_sectorbeneficiado, estado', 'required', 'message' => '{attribute} es requerido.'),
            array('codigo', 'unique', 'className' => 'Proyectos', 'message' => 'Ya existe un proyecto con ese código.'),
            array('idtbl_objetivoproyecto, tipoproyecto, idtbl_adscrito, estado', 'numerical', 'integerOnly' => true), /* removido idtbl_sectorbeneficiado, */
            array('nombre', 'length', 'min' => 3, 'max' => 500, 'tooShort' => 'El {attribute} debe ser mayor a {min} caracteres.', 'tooLong' => 'El {attribute} debe ser menor a {max} caracteres.'),
            array('codigo', 'length', 'min' => 2, 'max' => 20, 'tooShort' => 'El {attribute} debe ser mayor a {min} caracteres.', 'tooLong' => 'El {attribute} debe ser menor a {max} caracteres.'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            //array('idtbl_Proyectos, nombre, codigo, tbl_Periodos_idPeriodo', 'safe', 'on'=>'search'),
            array('idtbl_Proyectos, nombre, codigo, $fecha_inicio_search, $fecha_fin_search, estado', 'safe', 'on' => 'search'),
            array('idtbl_Proyectos, inicio, fin', 'safe', 'on' => 'cargarModelo'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            '_asistentes' => array(self::MANY_MANY, 'Asistentes', 'tbl_asistentes_has_tbl_proyectos(idtbl_Proyectos, idtbl_Asistentes)'),
            '_documentos' => array(self::HAS_MANY, 'Documentos', 'idtbl_Proyectos'),
            '_financiamientoexterno' => array(self::HAS_MANY, 'Financiamientoexterno', 'idtbl_Proyectos'),
            '_presupuestos' => array(self::HAS_MANY, 'Presupuesto', 'idtbl_Proyectos'),
            '_adscrito' => array(self::BELONGS_TO, 'Adscrito', 'idtbl_adscrito'),
            '_objetivoproyecto' => array(self::BELONGS_TO, 'Objetivoproyecto', 'idtbl_objetivoproyecto'),
            '_tipoproyecto' => array(self::BELONGS_TO, 'Tipoproyecto', 'tipoproyecto'),
            '_convenios' => array(self::MANY_MANY, 'Convenio', 'tbl_proyectos_convenio(idtbl_Proyectos, idtbl_convenio)'),
            '_sectorbeneficiados' => array(self::MANY_MANY, 'Sectorbeneficiado', 'tbl_proyectos_sectorbeneficiado(idtbl_Proyectos, idtbl_sectorbeneficiado)'),
            '_periodos' => array(self::MANY_MANY, 'Periodos', 'tbl_historialproyectosperiodos(idtbl_Proyectos, idPeriodo)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'idtbl_Proyectos' => 'Id proyecto',
            'nombre' => 'Nombre del proyecto',
            'codigo' => 'Código del proyecto',
            'fecha_inicio_search' => 'Fecha Inicio',
            'fecha_fin_search' => 'Fecha Final',
            'idtbl_objetivoproyecto' => 'Objetivo del proyecto',
            'tipoproyecto' => 'Tipo proyecto',
            'idtbl_adscrito' => 'Adscrito a',
            'estado' => 'Estado del proyecto',
            'idtbl_sectorbeneficiado' => 'Sector Beneficiado',
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
        $criteria->with = array('periodos');

        $criteria->compare('idtbl_Proyectos', $this->idtbl_Proyectos);
        $criteria->compare('nombre', $this->nombre, true);
        $criteria->compare('codigo', $this->codigo, true);
        $criteria->compare('periodos.inicio', $this->fecha_inicio_search, true);
        $criteria->compare('periodos.fin', $this->fecha_fin_search, true);
        $criteria->compare('idtbl_objetivoproyecto', $this->idtbl_objetivoproyecto);
        $criteria->compare('tipoproyecto', $this->tipoproyecto);
        $criteria->compare('idtbl_adscrito', $this->idtbl_adscrito);
        $criteria->compare('estado', $this->estado, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'attributes' => array(
                            'fecha_inicio_search' => array(
                                'asc' => 'periodos.inicio',
                                'desc' => 'periodos.inicio DESC',
                            ),
                            'fecha_fin_search' => array(
                                'asc' => 'periodos.fin',
                                'desc' => 'periodos.fin DESC',
                            ),
                            '*',
                        ),
                    ),
                ));
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Functions">
    public function agregarAsistenteProyecto($pidproyecto, $pcarnet, $pidrol, $pfechaini, $pfechafin, $phoras) {
        $conexion = Yii::app()->db;
        $call = 'CALL agregarAsistenteProyecto(:idproyecto,:carnet,:idrol,:fechaini,:fechafin, :horas)';
        $transaccion = Yii::app()->db->beginTransaction();
        try {
            $comando = $conexion->createCommand($call);
            $comando->bindParam(':idproyecto', $pidproyecto);
            $comando->bindParam(':carnet', $pcarnet);
            $comando->bindParam(':idrol', $pidrol);
            $comando->bindParam(':fechaini', $pfechaini);
            $comando->bindParam(':fechafin', $pfechafin);
            $comando->bindParam(':horas', $phoras);
            $comando->execute();
            $transaccion->commit();
        } catch (Exception $e) {
            $transaccion->rollback();
            return false;
        }
        return true;
    }

    /**
     * Esta funcion busca en la base de datos todos los asistentes que están activos
     * en el proyecto. Un asistente se considera activo si actualmente está asociado al
     * proyecto. En otras palabras si la fecha actual se encuentra entre su fecha de inicio y su fecha de fin de la asistencia.
     * @return CArraryDataProvider Retorna un CArrayDataProvider que se usa para mostrar datos en tablas. 
     */
    public function buscarAsistentesActivosDeProyecto() {
        $pk = $this->idtbl_Proyectos;
        $call = 'CALL buscarAsistentesActivosDeProyecto(:pk)';
        $conexion = Yii::app()->db;
        $comando = $conexion->createCommand($call);
        $comando->bindParam(':pk', $pk, PDO::PARAM_INT);
        $rawData = $comando->queryAll();
        $dataProvider = new CArrayDataProvider($rawData, array(
                    'keyField' => 'carnet',
                    'id' => 'Asistentes',
                    'sort' => array(
                        'defaultOrder' => 'apellido1 ASC',
                        'attributes' => array(
                            'carnet',
                            'nombre',
                            'apellido1',
                            'rol',
                            'horas',
                        //'fin', LA TABLA NO RECONOCE QUE ES UNA FECHA Y NO HACE UN BUEN ORDENAMIENTO
                        ),
                    ),
                ));
        return $dataProvider;
    }

    /* Esta funcion retorna la información del proyecto
     * y la información del periodo actual asociado al proyecto
     */

    public function obtenerProyectoconPeriodoActual($pIdProyecto) {
        $call = 'CALL obtenerProyectoConPeridoActual(:pIdProyecto)';
        $conexion = Yii::app()->db;
        $command = $conexion->createCommand($call);
        $command->bindParam(':pIdProyecto', $pIdProyecto, PDO::PARAM_INT);
        $model = $command->queryRow();

        if ($model == false)
            return null;
        else {
            $this->scenario = 'cargarModelo';
            $this->setAttributes($model); //Asociamos los atributos reales de un Proyecto                 
            $this->idperiodo = $model['idPeriodo']; //Asociamos el atributo simulado idperiodo
            $this->inicio = $model['inicio']; //Asociamos el atributos simulado inicio
            $this->fin = $model['fin']; //Asociamos el atributos simulado fin
            Proyectos::obtenerSectoresBeneficiados($pIdProyecto); //asociamos los valores de sectores beneficiados
            return $this; //Retornamos el objeto Proyecto
        }
    }

    public function obtenerProyectosActivos() {
        return Proyectos::executeNonTransactionalProcedureWithNoParameters('CALL obtenerProyectosActivos()');
    }

    public function obtenerProyectosAntiguos() {
        return Proyectos::executeNonTransactionalProcedureWithNoParameters('CALL obtenerProyectosAntiguos()');
    }

    /*
     * Da formato de lista a 
     */

    public function obtenerSectoresBeneficiadosConFormato() {
        $this->idtbl_sectorbeneficiado = Proyectos::listFormatBenefitedSectors($this->idtbl_sectorbeneficiado);
    }

    /*
     * Establece idtbl_sectorbeneficiado con un arreglo de sectores beneficiados
     * @param Integer $pIdProyecto
     */

    private function obtenerSectoresBeneficiados($pIdProyecto) {
        $call = 'CALL obtenerSectoresBeneficiados(:pIdProyecto)';
        $conexion = Yii::app()->db;
        $command = $conexion->createCommand($call);
        $command->bindParam(':pIdProyecto', $pIdProyecto, PDO::PARAM_INT);
        $model = $command->queryAll();

        if ($model == false)
            return null;
        else {
            $this->scenario = 'cargarModelo';
            $this->idtbl_sectorbeneficiado = $model;
        }
    }

    /*
     * Gives html format using <ul> tag to a list of sectors
     * @param Array $pSectorsArray
     * @return String
     */

    private function listFormatBenefitedSectors($pSectorsArray) {
        if (is_array($pSectorsArray)) {
            $html_list = '<ul>';
            foreach ($pSectorsArray as $sector) {
                $html_list .= '<li>' . $sector["nombre"] . '</li>';
            }
            $html_list .= '</ul>';
            return $html_list;
        }
        else return "No se ha especificado";
    }

    // <editor-fold defaultstate="collapsed" desc="Common private functions~">
    /*
     * Common actions made to execute any nontransactional procedure without parameters
     * IMPORTANT: result returned by yii's queryAll() function
     */
    private static function executeNonTransactionalProcedureWithNoParameters($pProcedureName) {
        $call = $pProcedureName;
        $conexion = Yii::app()->db;
        $command = $conexion->createCommand($call);
        $result = $command->queryAll();
        if (empty($result))
            return null;
        else
            return $result;
    }

    // </editor-fold>
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Constants">
    public $CODIGO_APROBADO = "0";
    public $CODIGO_AMPLIADO = "1";
    public $LABEL_APROBADO = 'Aprobado';
    public $LABEL_AMPLIADO = 'Ampliado';

// </editor-fold>
}

//fin modelo proyectos
