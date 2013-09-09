<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $investigadores CArrayDataProvider */

//Columnas de la tabla de los investigadores activos del proyecto.
$columns = array (
    array(
        'header'=>CHtml::encode(Investigador::model()->getAttributeLabel('cedula')),
        'name'=>'cedula',
    ),
    array(
        'header'=>CHtml::encode(Investigador::model()->getAttributeLabel('nombre')),
        'name'=>'nombre',
    ),
    array(
        'header'=>CHtml::encode(Investigador::model()->getAttributeLabel('apellido1')),
        'name'=>'apellido1',
    ),
    array(
        'header'=>CHtml::encode(Investigador::model()->getAttributeLabel('apellido2')),
        'name'=>'apellido2',
    ),
    array(
        'header'=>CHtml::encode(Investigador::model()->getAttributeLabel('rol')),
        'name'=>'rol',
    ),
    array(
        'header'=>CHtml::encode(Investigador::model()->getAttributeLabel('fin')),
        'name'=>'fin',
    ),
    array(
        'header'=>CHtml::encode(Investigador::model()->getAttributeLabel('VIE')),
        'name'=>'VIE',
    ),
    array(
        'header'=>CHtml::encode(Investigador::model()->getAttributeLabel('FUNDATEC')),
        'name'=>'Fundatec',
    ),
    array(
        'header'=>CHtml::encode(Investigador::model()->getAttributeLabel('Docencia')),
        'name'=>'Docencia',
    ),
    array(
        'header'=>CHtml::encode(Investigador::model()->getAttributeLabel('Reconocimiento')),
        'name'=>'Reconocimiento',
    )
    );
?>

<h3>Investigadores Activos</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'asistente-grid',
	'dataProvider'=>$investigadores,
	//'filter'=>$model,
	'columns'=>$columns,
           )); 
?>