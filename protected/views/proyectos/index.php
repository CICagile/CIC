<?php
/* @var $this ProyectosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Proyectos',
);

$this->menu=array(
	array('label'=>'Nuevo Proyecto', 'url'=>array('create')),
	//array('label'=>'Manage Proyectos', 'url'=>array('admin')),
);
?>

<h1>Lista de Proyectos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
