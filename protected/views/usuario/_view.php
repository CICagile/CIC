<?php
/* @var $this UsuarioController */
/* @var $data Usuario */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idtbl_usuario')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idtbl_usuario), array('view', 'id'=>$data->idtbl_usuario)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idtbl_rolusuario')); ?>:</b>
	<?php echo CHtml::encode($data->idtbl_rolusuario); ?>
	<br />


</div>