<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $periodos array */
/* @var $asistente Asistente */
$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$model->codigo => array('ver','id'=>$model->idtbl_Proyectos),
        'Editar asistencia',
);

$this->menu=array(	
        array('label'=>'Actualizar información del proyecto', 'url'=>array('actualizar', 'id'=>$model->idtbl_Proyectos)),
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Agregar investigador', 'url'=>array('agregarinvestigador', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Ver Proyecto', 'url'=>array('ver','id'=>$model->idtbl_Proyectos)),
        array('label'=>'Nuevo Proyecto', 'url'=>array('crear')),
);

?>

<div class="form">
    <p class="note">Los campos marcados con <span class="required">*</span> son obligatorios.</p>
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'editar-rol',
    )); ?>
    <h2>Cambiar Rol</h2>
    <?php echo $form->errorSummary(array($periodos['rol'],$asistente),'Se han detectado los siguientes errores:'); ?>
    <div class="row">
        <?php echo $form->labelEx($asistente,'rol'); ?>
        <?php echo $form->dropDownList($asistente, 'rol',
                CHtml::listData(RolAsistente::model()->findAll(), 'idtbl_RolesAsistentes', 'nombre'), array('empty'=>'Elija un rol')); ?>
        <?php echo $form->error($asistente,'rol'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($periodos['rol'], 'inicio'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => CHtml::activeName($periodos['rol'], 'inicio'),
            'value' => $periodos['rol']->attributes['inicio'],
            'language' => 'es',
            'options' => array(
                'showAnim' => 'fold',
                'dateFormat' => 'dd-mm-yy',
                'changeYear' => true,
                'changeMonth' => true,
            ),
            'htmlOptions' => array(
                'readonly' => 'readonly'
            ),
        )); ?>
        <?php echo $form->error($periodos['rol'],'inicio'); ?>
    </div>
    <?php $this->endWidget() ?>
</div>