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
        'header'=>CHtml::encode('VIE'),
        'name'=>'vie',
    ),
    array(
        'header'=>CHtml::encode('Fundatec'),
        'name'=>'fundatec',
    ),
    array(
        'header'=>CHtml::encode('Docencia'),
        'name'=>'docencia',
    ),
    array(
        'header'=>CHtml::encode('Reconocimiento'),
        'name'=>'reconocimiento',
    )
    );
?>

<h3>Investigadores Activos</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'investigador-grid',
	'dataProvider'=>$investigadores,
	'columns'=>$columns,
           )); 
?>