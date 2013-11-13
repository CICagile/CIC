

<h3>Reporte de historial de proyectos</h3>
<?php
//print_r($data_provider);

$this->breadcrumbs = array(
    'Asistente' => array('admin'),
    $model->nombre . " " . $model->apellido1 . " " . $model->apellido2,
);

$this->menu = array(
    array('label' => 'Ver Asistentes', 'url' => array('admin')),
    array('label' => 'Actualizar información del asistente', 'url' => array('updateDP', 'id' => $model->carnet)),
    array('label' => 'Reporte historial horas', 'url' => array('reportarHoras', 'id' => $model->carnet)),
);


$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'asistente-grid-historial',
    'dataProvider' => $data_provider,
    'columns' => array(
        array('header'=>'Código del Proyecto','value'=>'CHtml::link($data["codigo"], CHtml::normalizeUrl(array("/proyectos/","ver" => $data["idtbl_Proyectos"])))', 'type' => 'raw'),
        array('header'=>'Rol','value'=>'$data["rol"]'),
        array('header'=>'Inicio','value'=>'$data["inicio"]'),
        array('header'=>'Fin','value'=>'$data["fin"]'),
    ),
));

?>
