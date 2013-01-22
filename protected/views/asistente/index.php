<?php
/* @var $this AsistenteController */

$this->breadcrumbs=array(
	'Asistente',
);

$this->menu=array(
	array('label'=>'Create Asistentes', 'url'=>array('create')),
	array('label'=>'Manage Asistentes', 'url'=>array('admin')),
);
?>

<h1>Asistentes</h1>

<?php 
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

