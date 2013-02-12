<?php

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	'Registrar Proyectos',
);

$this->menu=array(
	array('label'=>'Ver Proyectos', 'url'=>array('admin'))	
);
?>

<h1>Actualizar Proyecto</h1>
<h4>Código: <?php $modelproyectos->codigo; ?></h4>

<?php echo $this->renderPartial('_formactualizar', array('modelproyectos'=>$modelproyectos, 'modelperiodos' => $modelperiodos,)); ?>