<?php
/* @var $this ProyectosController */
/* @var $modelproyectos Proyectos */
/* @var $modelperiodos Periodos */

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	'Registrar Proyectos',
);

$this->menu=array(
	array('label'=>'Ver Proyectos', 'url'=>array('admin')),
	//array('label'=>'Manage Proyectos', 'url'=>array('admin')),
);
?>

<h1>Registrar Proyectos</h1>

<?php echo $this->renderPartial('_form', array('modelproyectos'=>$modelproyectos, 'modelperiodos' => $modelperiodos,)); ?>