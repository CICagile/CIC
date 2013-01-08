<?php
/* @var $this ProyectosController */
/* @var $data Proyectos */
?>

<div class="view">
    
         <b><?php echo CHtml::encode($data->getAttributeLabel('codigo')); ?>:</b>	
         <?php echo CHtml::link(CHtml::encode($data->codigo), array('view', 'id'=>$data->idtbl_Proyectos)); ?>
	 <br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />
</div>