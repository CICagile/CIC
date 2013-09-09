<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $asistente Asistente */
/* @var $periodo Periodos */
$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$model->codigo => array('ver','id'=>$model->idtbl_Proyectos),
        'Agregar asistente',
);

$this->menu=array(	
        array('label'=>'Actualizar información del proyecto', 'url'=>array('actualizar', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Agregar investigador', 'url'=>array('agregarinvestigador', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Ver Proyecto', 'url'=>array('ver','id'=>$model->idtbl_Proyectos)),
        array('label'=>'Nuevo Proyecto', 'url'=>array('crear')),
);
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'agregarasistente-form',
	'enableAjaxValidation'=>true,
)); ?>
        
    <h2>Agregar asistente al Proyecto: <?php echo $model->codigo?></h2>
    <p>Periodo del proyecto: <?php echo $model->inicio
    .' hasta '.$model->fin?></p>

	<p class="note">Campos con <span class="required">*</span> son obligatorios.</p>
        
        <?php echo $form->errorSummary(array($periodo,$asistente),'Se han detectado los siguientes errores:'); ?>
        
        <div class="row">
        <label for="codigo">Proyecto</label>  
        <?php echo $form->textArea($model,'nombre',array('readonly' => 'readonly', 'rows'=>'4' ,'cols'=> '50')); ?>                 
	</div>
               
        <div class="row">
        <label for="asistente">Asistente (carnet)<span class="required">* </span></label>
        <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'attribute'=>'carnet',
            'model'=>$asistente,
            'source'=>$this->createUrl('proyectos/asistenteautocomplete'),
            'options'=>array(
                'showAnim'=>'fold',
            ),
            'htmlOptions'=>array(
                'size'=>20, 'maxlength'=>20
            )
            )); ?>
        <?php echo $form->error($asistente,'carnet'); ?>
        </div>
        
        
        <div class="row">
            <?php echo $form->labelEx($asistente,'rol') ?>
            <?php echo $form->dropDownList($asistente, 'rol',
                        CHtml::listData(RolAsistente::model()->findAll(), 'idtbl_RolesAsistentes', 'nombre'), array('empty'=>'Elija un rol')) ?>
            <?php echo $form->error($asistente,'rol', NULL, $enableAjaxValidation=false); ?>
        </div>
        
        <div class="row">
		<?php echo $form->labelEx($periodo,'inicio'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => CHtml::activeName($periodo, 'inicio'),
                        'value' => $periodo->attributes['inicio'],
                        'language' => 'es',
                        'options' => array(                            
                            'showAnim'=>'fold',
                            'dateFormat'=>'dd-mm-yy',
                            'changeYear'=>true,
                            'changeMonth'=>true,
                            'minDate' => $model->inicio,
                        ),
                        'htmlOptions'=>array(                            
                            'readonly' => 'readonly'
                        ),
                    ));?>
                <?php echo $form->error($periodo,'inicio'); ?>
		
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($periodo,'fin'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => CHtml::activeName($periodo, 'fin'),
                        'value' => $periodo->attributes['fin'],
                        'language' => 'es',
                        'options' => array(                            
                            'showAnim'=>'fold',
                            'dateFormat'=>'dd-mm-yy',
                            'changeYear'=>true,
                            'changeMonth'=>true,
                            'maxDate' => $model->fin,
                        ),
                        'htmlOptions'=>array(                            
                            'readonly' => 'readonly'
                        ),
                    ));?>
                <?php echo $form->error($periodo,'fin'); ?>
	</div>
        
        <div class="row">
            <?php echo $form->labelEx($asistente,'horas'); ?>
            <?php echo $form->textField($asistente,'horas',array('size'=>4,'maxlength'=>4)); ?>
            <?php echo $form->error($asistente,'horas'); ?>
        </div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Agregar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
