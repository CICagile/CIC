<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $dataProvider CArrayDataProvider */

//Columnas de la tabla de los asistentes activos del proyecto.
$columns = array (
    array(
        'header'=>CHtml::encode(Asistente::model()->getAttributeLabel('carnet')),
        'name'=>'carnet',
    ),
    array(
        'header'=>CHtml::encode(Asistente::model()->getAttributeLabel('nombre')),
        'name'=>'nombre',
    ),
    array(
        'header'=>CHtml::encode(Asistente::model()->getAttributeLabel('apellido1')),
        'name'=>'apellido1',
    ),
    array(
        'header'=>CHtml::encode(Asistente::model()->getAttributeLabel('apellido2')),
        'name'=>'apellido2',
    ),
    array(
        'header'=>CHtml::encode(Asistente::model()->getAttributeLabel('rol')),
        'name'=>'rol',
    ),
    array(
        'header'=>CHtml::encode('Horas'),
        'name'=>'horas',
    ),
    array(
        'header'=>CHtml::encode('Fin de la asistencia'),
        'name'=>'fin',
    ),
    array(
        'class'=>'CButtonColumn',
        'template'=>'{view}{update}{delete}',
        'viewButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/view", array("id"=>$data["carnet"]))',
        'viewButtonLabel' => 'Ver información detallada del asistente',
        'updateButtonUrl'=>'Yii::app()->controller->createUrl("Asistente/updateDP", array("id"=>$data["carnet"]))',
        'updateButtonLabel' => 'Editar información personal del asistente',
        'deleteButtonUrl'=>'Yii::app()->controller->createURL("Asistente/desvincular",array("idtbl_Proyectos"=>'.$model->idtbl_Proyectos.',"carnet"=>$data["carnet"]))',
        //'deleteButtonUrl'=>'Yii::app()->controller->createURL("Asistente/desvincular", array("id"=>$data["carnet"]))',
        'deleteButtonLabel' => 'Desvincular Asistente'
     ));
?>

<h3>Asistentes activos</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'asistente-grid',
	'dataProvider'=>$dataProvider,
	//'filter'=>$model,
	'columns'=>$columns,
           )); 
?>

<?php if ($dataProvider->totalItemCount > 0)
            echo CHtml::button('Editar información de los asistentes',array('submit'=>Yii::app()->controller->createUrl("Proyectos/actualizarInfoAsistentes", array("id"=>$model->idtbl_Proyectos))));
?>