<?php
/* @var $this PersonasController */
/* @var $model Personas */

$this->breadcrumbs=array(
	'Personas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Personas', 'url'=>array('index')),
	array('label'=>'Manage Personas', 'url'=>array('admin')),
);
?>

<h1>Create Personas</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>