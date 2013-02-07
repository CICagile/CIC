<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$modelproyectos->codigo=>array('view','id'=>$modelproyectos->idtbl_Proyectos),
	'Actualizar',
);

$this->menu=array(
        array('label'=>'Ver Proyectos', 'url'=>array('admin')),
	array('label'=>'Registrar Proyecto', 'url'=>array('create')),	
	
);
?>

<h1>Actualizar Proyecto: <?php echo $modelproyectos->codigo; ?></h1>

<?php echo $this->renderPartial('_form', array('modelproyectos'=>$modelproyectos, 'modelperiodos' => $modelperiodos,)); ?>