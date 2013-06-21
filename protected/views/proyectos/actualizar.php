<?php

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	'Actualizar proyecto',
);

$this->menu=array(
        array('label'=>'Ver información de este proyecto', 'url'=>array('ver', 'id'=>$modelproyectos->idtbl_Proyectos)),
	array('label'=>'Ver Proyectos', 'url'=>array('admin')),        	
);
?>

<h2>Actualizar Proyecto <?php echo $modelproyectos->codigo; ?></h2>


<?php echo $this->renderPartial('_formactualizar', array('modelproyectos'=>$modelproyectos, 'modelperiodos'=>$modelperiodos)); ?>