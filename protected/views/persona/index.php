<?php
/* @var $this PersonaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Personas',
);

$this->menu=array(
	array('label'=>'Create Persona', 'url'=>array('create')),
	array('label'=>'Manage Persona', 'url'=>array('admin')),
);
?>

<h1>Personas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
