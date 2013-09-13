<?php
    $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'asistente-grid-historial',
    'dataProvider' => $data_provider,
    'columns' => array(
        array('header'=>'Código del Proyecto','value'=>'CHtml::link($data["codigo"], CHtml::normalizeUrl(array("/proyectos/","ver" => $data["idtbl_Proyectos"])))', 'type' => 'raw'),
        array('header'=>'Horas','value'=>'$data["horas"]'),
        array('header'=>'Inicio','value'=>'$data["inicio"]'),
        array('header'=>'Fin','value'=>'$data["fin"]'),
    ),
));
?>