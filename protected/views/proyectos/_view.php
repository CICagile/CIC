<?php
/* @var $this ProyectosController */
/* @var $data Proyectos */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idtbl_Proyectos')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idtbl_Proyectos), array('view', 'id'=>$data->idtbl_Proyectos)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('codigo')); ?>:</b>
	<?php echo CHtml::encode($data->codigo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tbl_Periodos_idPeriodo')); ?>:</b>
	<?php echo CHtml::encode($data->tbl_Periodos_idPeriodo); ?>
	<br />


</div>