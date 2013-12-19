<?php
/* @var $this BancoController */
/* @var $model Banco */

$this->breadcrumbs=array(
        'Gestión del sistema'=>array('parametros/index'),
	'Bancos'=>array('admin'),
	'Crear',
);

$this->menu=array(
	array('label'=>'Ver Bancos', 'url'=>array('admin')),
);
?>

<h3>Crear Banco</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>