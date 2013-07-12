<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $periodo Periodos */
/* @var $asistente Asistente */
$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$model->codigo => array('ver','id'=>$model->idtbl_Proyectos),
        'Editar asistencia',
);

$this->menu=array(	
        array('label'=>'Actualizar información del proyecto', 'url'=>array('actualizar', 'id'=>$model->idtbl_Proyectos)),
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Agregar investigador', 'url'=>array('agregarinvestigador', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Ver Proyecto', 'url'=>array('ver','id'=>$model->idtbl_Proyectos)),
        array('label'=>'Nuevo Proyecto', 'url'=>array('crear')),
);

?>

<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'editar-rol',
    )); ?>
    <p class="note">Los campos marcados con <span class="required">*</span> son obligatorios.</p>
    <?php //echo $form->errorSummary(array($periodo,$asistente),'Se han detectado los siguientes errores:'); ?>
    <?php $this->endWidget() ?>
</div>