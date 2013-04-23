<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $asistente Asistente */
/* @var $dataProvider CArrayDataProvider */
$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$model->codigo,
);

$this->menu=array(	
        array('label'=>'Actualizar información del proyecto', 'url'=>array('actualizar', 'id'=>$model->idtbl_Proyectos)),	
        array('label'=>'Ver proyectos activos', 'url'=>array('admin')),
        array('label'=>'Ver proyectos antiguos', 'url'=>array('adminantiguos')),
        
);

//Columnas de la tabla de los asistentes activos del proyecto.
$columns = array (
    array(
        'header'=>CHtml::encode($asistente->getAttributeLabel('carnet')),
        'name'=>'carnet',
    ),
    array(
        'header'=>CHtml::encode($asistente->getAttributeLabel('nombre')),
        'name'=>'nombre',
    ),
    array(
        'header'=>CHtml::encode($asistente->getAttributeLabel('apellido1')),
        'name'=>'apellido1',
    ),
    array(
        'header'=>CHtml::encode($asistente->getAttributeLabel('apellido2')),
        'name'=>'apellido2',
    ),
    array(
        'header'=>CHtml::encode($asistente->getAttributeLabel('rol')),
        'name'=>'rol',
    ),
    array(
        'header'=>CHtml::encode('Horas'),
        'name'=>'horas',
    ),
    array(
        'header'=>CHtml::encode('Fin de la asistencia'),
        'name'=>'fin',
    ),
    array(
        'class'=>'CButtonColumn',
        'template'=>'{view}{update}',
        'viewButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("id"=>$data["carnet"]))',
        'viewButtonLabel' => 'Ver información detallada del asistente',
        'updateButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/updateDP", array("id"=>$data["carnet"]))',
        'updateButtonLabel' => 'Editar información personal del asistente',
));

?>

<h3>Detalle del proyecto.</h3>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(            
                'codigo',
		'nombre',
                array(
                        'label' => 'Estado del proyecto',
                        'value' => ($model->estado == $model->codaprobado)? $model->labelaprobado : $model->labelampliado,
                ),
		array(
                        'label' => 'Fecha Inicio',
                        'value' => $this->FechaMysqltoPhp($model->inicio),
                ),
                array(
                        'label' => 'Fecha finalización',
                        'value' => $this->FechaMysqltoPhp($model->fin),
                ),
                '_tipoproyecto.nombre',                
                '_objetivoproyecto.nombre',
                '_adscrito.nombre'             
	),
)); 
      
?>

<br/>
<br/>
<h3>Asistentes</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'asistente-grid',
	'dataProvider'=>$dataProvider,
	//'filter'=>$model,
	'columns'=>$columns,
           )); 
?>