<?php
/* @var $this ConvenioController */
/* @var $data Convenio */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idtbl_convenio')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idtbl_convenio), array('view', 'id'=>$data->idtbl_convenio)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />


</div>