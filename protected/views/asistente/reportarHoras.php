

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

<h3>Seleccione un mes para filtrar el resultado</h3>

<?php
$this->widget('ext.EJuiMonthPicker.EJuiMonthPicker', array(
                'name' => 'mes_reporte',
                'id' => 'mes_reporte',
                'options' => array(
                    'dateFormat' => 'mm-yy',
                    'changeYear' => true,
                    'changeMonth' => true,
                ),
                'htmlOptions' => array(
                    'readonly' => 'readonly'
                ),
            ));
?>

<?php $this->renderPartial('_reporteHorasMes', array('data_provider'=>$data_provider)); ?>
