
<?php
/* @var $this InvestigadorController */
/* @var $model Investigador */
/* @var $form CActiveForm */
/* @var $periodo Periodos */
/* @var $horas array */
?>

<?php $form=$this->widget('ext.dynamicform.DynamicForm', array(
	'id'=>'dynamic-horas',
	'options' => array(
            'limit' => 100,
            'createColor' => 'green',
            'removeColor' => 'red',
            'duration' => 450,
            'data' => $horas,
        ),
        'form' => 'formhoras',
        'plus' => 'plus',
        'minus'=> 'minus'
));?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'investigador-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Los campos marcados con <span class="required">*</span> son obligatorios.</p>

	<?php echo $form->errorSummary(array($model,$periodo),'Se han detectado los siguientes errores:'); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nombre'); ?>
		<?php echo $form->textField($model,'nombre',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'nombre'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'apellido1'); ?>
		<?php echo $form->textField($model,'apellido1',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'apellido1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'apellido2'); ?>
		<?php echo $form->textField($model,'apellido2',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'apellido2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cedula'); ?>
		<?php echo $form->textField($model,'cedula',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'cedula'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'telefono'); ?>
		<?php echo $form->textField($model,'telefono',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'telefono'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'correo'); ?>
		<?php echo $form->textField($model,'correo',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'correo'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'grado'); ?>
		<?php echo $form->dropDownList($model, 'grado',
                        CHtml::listData(GradoAcademico::model()->findAll(), 'nombre', 'nombre-abreviacion'), array('empty'=>'Elija un grado académico')) ?>
		<?php echo $form->error($model,'grado', NULL, false); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'experiencia'); ?>
		<?php echo $form->textField($model,'experiencia',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'experiencia'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'ingreso'); ?>
		<?php echo $form->textField($model,'ingreso',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'ingreso'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'proyecto'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                        'attribute'=>'proyecto',
                        'model'=>$model,
                        'source'=>$this->createUrl('investigador/codigoautocomplete'),
                        'options'=>array(
                            'showAnim'=>'fold',
                        ),
                        'htmlOptions'=>array(
                            'size'=>20, 'maxlength'=>20
                        )
                        )); ?>
		<?php echo $form->error($model,'proyecto'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'rol'); ?>
		<?php echo $form->dropDownList($model, 'rol',
                        CHtml::listData(RolesInvestigadores::model()->findAll(), 'nombre', 'nombre'), array('empty'=>'Elija un rol')) ?>
		<?php echo $form->error($model,'rol', NULL, false); ?>
	</div>
        
        <div id="formhoras" class="row-box">
            <div class="row">
                <?php echo $form->labelEx($model,'horas'); ?>
                <?php echo CHtml::textField("cantidad_horas"); ?>
                <?php echo $form->error($model,'horas'); ?>
            </div>
        
            <div class="row">
                <?php echo CHtml::label('Tipo de Horas','tipo_horas'); ?>
                <?php echo CHtml::dropDownList('tipo_horas', 'empty',
                        CHtml::listData(TipoHoraInvestigador::model()->findAll(), 'nombre', 'nombre'), array('empty'=>'Elija una opción')) ?>
                <?php echo $form->error($model,'horas', NULL, false); ?>
            </div>
            <span style="clear:none;float:right;"><a id="minus" href="">[-]</a> <a id="plus" href="">[+]</a></span>
            <br>
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
                        ),
                        'htmlOptions'=>array(                            
                            'readonly' => 'readonly'
                        ),
                    ));?>
                <?php echo $form->error($periodo,'fin'); ?>
		
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Registrar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
