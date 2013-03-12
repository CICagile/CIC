<?php
/* @var $this SectorBeneficiadoController */
/* @var $model SectorBeneficiado */

$this->breadcrumbs=array(
	'Sector Beneficiados'=>array('index'),
	$model->nombre=>array('view','id'=>$model->idtbl_sectorbeneficiado),
	'Actualizar',
);

$this->menu=array(
	array('label'=>'Crear Sector Beneficiado', 'url'=>array('create')),
);
?>

<h1>Actualizar <?php echo $model->nombre; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>