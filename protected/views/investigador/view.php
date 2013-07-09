<?php
/* @var $this InvestigadorController */
/* @var $model investigador */

$this->breadcrumbs=array(
	'Investigador'=>array('admin'),
	$model->nombre." ".$model->apellido1." ".$model->apellido2,
);

$this->menu=array(
        array('label'=>'Ver Investigadores', 'url'=>array('admin')),	
	
	
);
?>

<h3>Información del Investigador</h3>

<?php 
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
                'cedula',
                'nombre',
                'apellido1',
                'apellido2',
                'telefono',
                'correo',
                'experiencia',
                'ingreso',
                'grado',
	),
));
?>
<br/>
<br/>

