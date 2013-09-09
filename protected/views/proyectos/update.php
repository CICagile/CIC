<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$modelproyectos->codigo => array('ver','id'=>$modelproyectos->idtbl_Proyectos),
        'Actualizar información',
);

$this->menu=array(
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$modelproyectos->idtbl_Proyectos)),
        array('label'=>'Agregar investigador', 'url'=>array('agregarinvestigador', 'id'=>$modelproyectos->idtbl_Proyectos)),
        array('label'=>'Ver Proyecto', 'url'=>array('ver','id'=>$modelproyectos->idtbl_Proyectos)),
        array('label'=>'Nuevo Proyecto', 'url'=>array('crear')),
);
?>

<h1>Actualizar Proyecto: <?php echo $modelproyectos->codigo; ?></h1>

<?php echo $this->renderPartial('_form', array('modelproyectos'=>$modelproyectos, 'modelperiodos' => $modelperiodos,)); ?>