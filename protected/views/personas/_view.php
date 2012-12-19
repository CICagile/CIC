<?php
/* @var $this PersonasController */
/* @var $data Personas */
?>

<div class="view">

      	<b><?php echo CHtml::encode($data->getAttributeLabel('Nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Apellido1')); ?>:</b>
	<?php echo CHtml::encode($data->apellido1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Apellido2')); ?>:</b>
	<?php echo CHtml::encode($data->apellido2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Cedula')); ?>:</b>
	<?php echo CHtml::encode($data->cedula); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Numero Cuenta')); ?>:</b>
	<?php echo CHtml::encode($data->numerocuenta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Banco')); ?>:</b>
	<?php echo CHtml::encode($data->banco); ?>
	<br />

	
	<b><?php echo CHtml::encode($data->getAttributeLabel('Cuenta Cliente')); ?>:</b>
	<?php echo CHtml::encode($data->cuentacliente); ?>
	<br />

	 

</div>