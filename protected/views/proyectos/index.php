<?php
/* @var $this ProyectosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Proyectoses',
);

$this->menu=array(
	array('label'=>'Create Proyectos', 'url'=>array('create')),
	array('label'=>'Manage Proyectos', 'url'=>array('admin')),
);
?>

<h1>Proyectoses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
