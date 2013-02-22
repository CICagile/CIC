<?php
/* @var $this ObjetivoProyectoController */
/* @var $model ObjetivoProyecto */

$this->breadcrumbs=array(
	'Objetivo Proyectos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ObjetivoProyecto', 'url'=>array('index')),
	array('label'=>'Manage ObjetivoProyecto', 'url'=>array('admin')),
);
?>

<h1>Create ObjetivoProyecto</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>