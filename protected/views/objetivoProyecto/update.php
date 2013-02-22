<?php
/* @var $this ObjetivoProyectoController */
/* @var $model ObjetivoProyecto */

$this->breadcrumbs=array(
	'Objetivo Proyectos'=>array('index'),
	$model->idtbl_objetivoproyecto=>array('view','id'=>$model->idtbl_objetivoproyecto),
	'Update',
);

$this->menu=array(
	array('label'=>'List ObjetivoProyecto', 'url'=>array('index')),
	array('label'=>'Create ObjetivoProyecto', 'url'=>array('create')),
	array('label'=>'View ObjetivoProyecto', 'url'=>array('view', 'id'=>$model->idtbl_objetivoproyecto)),
	array('label'=>'Manage ObjetivoProyecto', 'url'=>array('admin')),
);
?>

<h1>Update ObjetivoProyecto <?php echo $model->idtbl_objetivoproyecto; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>