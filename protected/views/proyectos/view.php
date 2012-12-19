<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */

$this->breadcrumbs=array(
	'Proyectoses'=>array('index'),
	$model->idtbl_Proyectos,
);

$this->menu=array(
	array('label'=>'List Proyectos', 'url'=>array('index')),
	array('label'=>'Create Proyectos', 'url'=>array('create')),
	array('label'=>'Update Proyectos', 'url'=>array('update', 'id'=>$model->idtbl_Proyectos)),
	array('label'=>'Delete Proyectos', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idtbl_Proyectos),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Proyectos', 'url'=>array('admin')),
);
?>

<h1>View Proyectos #<?php echo $model->idtbl_Proyectos; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idtbl_Proyectos',
		'nombre',
		'codigo',
		'tbl_Periodos_idPeriodo',
	),
)); ?>
