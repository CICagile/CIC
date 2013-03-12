<?php
/* @var $this SectorBeneficiadoController */
/* @var $model SectorBeneficiado */

$this->breadcrumbs=array(
	'Sector Beneficiados'=>array('index'),
	'Crear',
);
?>

<h1>Crear Sector Beneficiado</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>