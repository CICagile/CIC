<?php
/* @var $this AsistenteController */
/* @var $model Asistente */

$this->breadcrumbs=array(
	'Asistente'=>array('index'),
	$model->nombre." ".$model->apellido1." ".$model->apellido2,
);

$this->menu=array(
	array('label'=>'Create Asistente', 'url'=>array('create')),
	array('label'=>'Update Asistente', 'url'=>array('update', 'id'=>$model->carnet)),
	array('label'=>'Delete Asistente', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->carnet),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Buscar Asistente', 'url'=>array('admin')),
);
?>

<h1>Ver Asistentes</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'nombre',
                'apellido1',
                'apellido2',
                'cedula',
                'numerocuenta',
                'banco',
                'cuentacliente',
                'codigo',
                'telefono',
                'correo'
	),
)); ?>

