<?php
/* @var $this SectorBeneficiadoController */
/* @var $model SectorBeneficiado */

$this->breadcrumbs=array(
	'Gestión del sistema'=>array('parametros/index'),
	'Sectores beneficiados'=>array('admin'),
        'Crear'
);
?>

<h1>Crear Sector Beneficiado</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>