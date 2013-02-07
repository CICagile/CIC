


<?php

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	'Registrar Proyectos',
);

$this->menu=array(
	array('label'=>'Ver Proyectos', 'url'=>array('admin'))	
);
?>

<h1>Registrar Proyecto</h1>

<?php // echo $this->renderPartial('_form', array('modelproyectos'=>$modelproyectos, 'modelperiodos' => $modelperiodos,)); ?>

<?php echo $this->renderPartial('_formcrear', array('modelproyectos'=>$modelproyectos, 'modelperiodos' => $modelperiodos,)); ?>