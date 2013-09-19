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
    ),
    array(
        'class'=>'CButtonColumn',
        'template'=>'{view}{delete}',//{editar}{update}{delete}',
        'viewButtonUrl'=>'Yii::app()->controller->createUrl("Investigador/view", array("id"=>$data["cedula"]))',
        'viewButtonLabel' => 'Ver información detallada del asistente',
        'deleteButtonUrl'=>'',
        'deleteButtonLabel' => 'Desvincular Investigador',
        /*
        'updateButtonUrl'=>'Yii::app()->controller->createUrl("Proyectos/editarasistencia", array("id"=>'.$model->idtbl_Proyectos.',"carnet"=>$data["carnet"]))',
        'updateButtonLabel' => 'Actualizar información de la asistencia.',
        'deleteButtonUrl'=>'Yii::app()->controller->createURL("Asistente/desvincular",array("idtbl_Proyectos"=>'.$model->idtbl_Proyectos.',"carnet"=>$data["carnet"]))',
        'deleteButtonLabel' => 'Desvincular Asistente',
        'buttons' => array(
            'editar' => array(
                'label' => 'Editar información personal del asistente',
                'imageUrl' => Yii::app()->request->baseUrl . '/images/edit-user.png',
                'url' => 'Yii::app()->controller->createURL("Asistente/updateDP", array("id"=>$data["carnet"]))',
                        ),
                    ),*/
        ),
    );
?>

<h3>Investigadores Activos</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'investigador-grid',
	'dataProvider'=>$investigadores,
	'columns'=>$columns,
           )); 
?>