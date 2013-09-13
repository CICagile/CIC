

<h3>Reporte de historial de horas por proyecto</h3>



<?php
//print_r($data_provider);

$this->breadcrumbs = array(
    'Asistente' => array('admin'),
    $model->nombre . " " . $model->apellido1 . " " . $model->apellido2,
);

$this->menu = array(
    array('label' => 'Ver Asistentes', 'url' => array('admin')),
    array('label' => 'Actualizar información del asistente', 'url' => array('updateDP', 'id' => $model->carnet)),
    array('label' => 'Reporte historial proyectos', 'url' => array('reportarProyectos', 'id' => $model->carnet)),
);
?>

<h4>Puede seleccionar un mes para filtrar el resultado</h4>

<?php
$this->widget('ext.EJuiMonthPicker.EJuiMonthPicker', array(
                'name' => 'mes_reporte',
                'id' => 'mes_reporte',
                'value' => date('m-Y'),
                'options' => array(
                    'dateFormat' => 'mm-yy',
                    'changeYear' => true,
                    'changeMonth' => true,
                ),
                'htmlOptions' => array(
                    'readonly' => 'readonly'
                ),
            ));

echo CHtml::ajaxButton ("Filtrar",
                              CController::createUrl('ActualizarReporteHorasMes', array('pCarnet'=>'112233445566')), 
                              array(
                                  'update' => '#horas-mes',
                                  'type' => 'post',
                                  //'success'=>'function(data){alert("OK");}',
                                  'data' => array('mes_reporte'=>'js:$("#mes_reporte").val()'))
                        );

?>


<div id="horas-mes">
    <br/><br/><br/>
    El siguiente es un reporte de todas las horas registradas por proyecto
    <?php $this->renderPartial('_reporteHorasMes', array('data_provider'=>$data_provider)); ?>
</div>