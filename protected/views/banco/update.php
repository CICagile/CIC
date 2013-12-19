<?php
/* @var $this BancoController */
/* @var $model Banco */

$this->breadcrumbs=array(
        'Gestión del sistema'=>array('parametros/index'),
	'Bancos'=>array('admin'),
	'Modificar',
);

$this->menu=array(
	array('label'=>'Ver Bancos', 'url'=>array('admin')),
);
?>

<h3>Modificar <?php echo $model->nombre; ?></h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>