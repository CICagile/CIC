<?php
/* @var $this ProyectosController */
/* @var $modelproyectos Proyectos */
/* @var $modelperiodos Periodos */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'proyectos-form',	
        'enableAjaxValidation'=>true,   
    
)); ?>

	<p class="note">Campos con <span class="required">*</span> son obligatorios.</p>

	<?php echo $form->errorSummary(array($modelproyectos,$modelperiodos)); ?>
        
        <div class="row">
		<?php echo $form->labelEx($modelproyectos,'codigo'); ?>
		<?php echo $form->textField($modelproyectos,'codigo',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($modelproyectos,'codigo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($modelproyectos,'nombre'); ?>
		<?php echo $form->textField($modelproyectos,'nombre',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($modelproyectos,'nombre'); ?>
	</div>		
        
        <div class="row">
		<?php echo $form->labelEx($modelperiodos,'inicio'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => CHtml::activeName($modelperiodos, 'inicio'),
                        'value' => $modelperiodos->attributes['inicio'],
                        'language' => 'es',
                        'options' => array(                            
                            'showAnim'=>'fold',
                            'dateFormat'=>'dd-mm-yy',
                            'changeYear'=>true,
                            'changeMonth'=>true,
                        ),
                        'htmlOptions'=>array(                            
                            'readonly' => 'readonly'
                        ),
                    ));?>
                <?php echo $form->error($modelperiodos,'inicio'); ?>
		
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($modelperiodos,'fin'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => CHtml::activeName($modelperiodos, 'fin'),
                        'value' => $modelperiodos->attributes['fin'],
                        'language' => 'es',
                        'options' => array(                            
                            'showAnim'=>'fold',
                            'dateFormat'=>'dd-mm-yy',
                            'changeYear'=>true,
                            'changeMonth'=>true,
                        ),
                        'htmlOptions'=>array(                            
                            'readonly' => 'readonly'
                        ),
                    ));?>
                <?php echo $form->error($modelperiodos,'fin'); ?>
		
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($modelproyectos->isNewRecord ? 'Crear' : 'Guardar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->