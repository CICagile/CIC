<?php
/* @var $this SectorBeneficiadoController */
/* @var $data SectorBeneficiado */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('idtbl_sectorbeneficiado')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->idtbl_sectorbeneficiado), array('view', 'id'=>$data->idtbl_sectorbeneficiado)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nombre')); ?>:</b>
	<?php echo CHtml::encode($data->nombre); ?>
	<br />


</div>