<?php

if ($data_provider != null) {
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'investigador-grid-historial',
        'dataProvider' => $data_provider,
        'columns' => array(
            array('header' => 'Código del Proyecto', 'value' => '$data["codigo"]') /*'CHtml::link($data["codigo"], CHtml::normalizeUrl(array("/proyectos/","ver" => $data["idtbl_Proyectos"])))', 'type' => 'raw')*/,
            array('header' => 'Horas', 'value' => '$data["horas"]'),
            array('header' => 'Inicio', 'value' => '$data["inicio"]'),
            array('header' => 'Fin', 'value' => '$data["fin"]'),
            array('header' => 'Tipos de Hora', 'value' => '$data["tipo_hora"]'),
        ),
    ));
}else{
   echo '<br/>No se encontraron resultados';
    if(strlen($fecha_mes) > 3)
        echo ' para el mes que inicia el día ' . $fecha_mes;
}
?>