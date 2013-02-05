<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $asistente Asistente */
$this->breadcrumbs=array(
	'Proyectos'=>array('index'),
	$model->codigo,
);

$this->menu=array(
	array('label'=>'Ver Proyectos', 'url'=>array('index')),
	array('label'=>'Nuevo Proyecto', 'url'=>array('create')),        
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$model->idtbl_Proyectos)),
	/*array('label'=>'Delete Proyectos', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idtbl_Proyectos),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Proyectos', 'url'=>array('admin')),*/
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
        'viewButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("id"=>$data["carnet"]))',
        'updateButtonUrl'=>'Yii::app()->controller->createUrl("Proyectos/actualizarInfoAsistentes", array("id"=>"'.$model->idtbl_Proyectos.'"))',
        'deleteButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("id"=>$data["carnet"]))',),
);

?>

<h3>Detalle del proyecto.</h3>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'idtbl_Proyectos',
                'codigo',
		'nombre',		
		array(
                        'label' => $model->periodos->getAttributeLabel('inicio'),
                        'value' => $this->FechaMysqltoPhp($model->periodos->inicio),
                ),
                array(
                        'label' => $model->periodos->getAttributeLabel('fin'),
                        'value' => $this->FechaMysqltoPhp($model->periodos->fin),
                )
                
	),
)); 
      
?>

<p></p><h3>Asistentes activos</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'asistente-grid',
	'dataProvider'=>$asistente->buscarAsistentesActivosPorProyecto($model->idtbl_Proyectos),
	//'filter'=>$model,
	'columns'=>$columns,
           )); 
?>
