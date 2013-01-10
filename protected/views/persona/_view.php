<?php
/* @var $this PersonaController */
/* @var $data Persona */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idtbl_Personas')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idtbl_Personas), array('view', 'id'=>$data->idtbl_Personas)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('apellido1')); ?>:</b>
	<?php echo CHtml::encode($data->apellido1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('apellido2')); ?>:</b>
	<?php echo CHtml::encode($data->apellido2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cedula')); ?>:</b>
	<?php echo CHtml::encode($data->cedula); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('numerocuenta')); ?>:</b>
	<?php echo CHtml::encode($data->numerocuenta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cuentacliente')); ?>:</b>
	<?php echo CHtml::encode($data->cuentacliente); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('idtbl_Bancos')); ?>:</b>
	<?php echo CHtml::encode($data->idtbl_Bancos); ?>
	<br />

	*/ ?>

</div>