<?php
/* @var $this PersonaController */
/* @var $model Persona */

$this->breadcrumbs=array(
	'Personas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Persona', 'url'=>array('index')),
	array('label'=>'Manage Persona', 'url'=>array('admin')),
);
?>

<h1>Create Persona</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>