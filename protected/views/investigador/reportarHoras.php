

<h3>Reporte de historial de horas por proyecto</h3>



<?php

$this->breadcrumbs = array(
    'Investigador' => array('admin'),
    $model->nombre . " " . $model->apellido1 . " " . $model->apellido2,
);

$this->menu = array(
    array('label' => 'Ver Investigadores', 'url' => array('admin')),
    array('label' => 'Exportar a excel', 'url' => array('HorasMes_excel')),
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

echo CHtml::ajaxButton ("Buscar",
                              CController::createUrl('ActualizarReporteHorasMes', array('pCedula' => $model->cedula)), 
                              array(
                                  'update' => '#horas-mes',
                                  'type' => 'post',
                                  'data' => array('mes_reporte'=>'js:$("#mes_reporte").val()'))
                        );

?>


<div id="horas-mes">
    <br/><br/><br/>
    El siguiente es un reporte de todas las horas registradas por proyecto
    <?php $this->renderPartial('_reporteHorasMes', array('data_provider'=>$data_provider)); ?>
</div>