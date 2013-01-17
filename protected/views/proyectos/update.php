<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */

$this->breadcrumbs=array(
	'Proyectoses'=>array('index'),
	$model->idtbl_Proyectos=>array('view','id'=>$model->idtbl_Proyectos),
	'Update',
);

$this->menu=array(
	array('label'=>'List Proyectos', 'url'=>array('index')),
	array('label'=>'Create Proyectos', 'url'=>array('create')),
	array('label'=>'View Proyectos', 'url'=>array('view', 'id'=>$model->idtbl_Proyectos)),
	array('label'=>'Manage Proyectos', 'url'=>array('admin')),
);
?>

<h1>Update Proyectos <?php echo $model->idtbl_Proyectos; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>