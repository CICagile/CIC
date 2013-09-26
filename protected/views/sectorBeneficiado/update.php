<?php
/* @var $this SectorBeneficiadoController */
/* @var $model SectorBeneficiado */

$this->breadcrumbs=array(
	'Gestión del sistema'=>array('parametros/index'),
	'Sectores Beneficiados'=>array('admin'),
        'Modificar'
);

$this->menu=array(
	array('label'=>'Crear Sector Beneficiado', 'url'=>array('create')),
);
?>

<h1>Actualizar <?php echo $model->nombre; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>