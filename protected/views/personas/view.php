<?php
/* @var $this PersonasController */
/* @var $model Personas */

$this->breadcrumbs=array(
	'Personas'=>array('index'),
	$model->nombre,
);

$this->menu=array(
	array('label'=>'Listar Personas', 'url'=>array('index')),
	array('label'=>'Create Personas', 'url'=>array('create')),
	array('label'=>'Update Personas', 'url'=>array('update', 'id'=>$model->idtbl_Personas)),
	array('label'=>'Delete Personas', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->idtbl_Personas),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Buscar Personas', 'url'=>array('admin')),
);
?>

<h1>Ver Personas</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'idtbl_Personas',
		'nombre',
		'apellido1',
		'apellido2',
		'cedula',
		'numerocuenta',
		'banco',
		'cuentacliente',
	),
)); ?>
