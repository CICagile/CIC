
<?php
/* @var $this AsistenteController */
/* @var $model Asistente */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asistente-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Los campos marcados con <span class="required">*</span> son obligatorios.</p>

	<?php echo $form->errorSummary($model,'Se han detectado los siguientes errores:'); ?>

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
		<?php echo $form->labelEx($model,'numerocuenta'); ?>
		<?php echo $form->textField($model,'numerocuenta',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'numerocuenta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'banco'); ?>
		<?php echo $form->dropDownList($model, 'banco',
                                CHtml::listData(Banco::model()->findAll(), 'nombre', 'nombre'), array('empty'=>'Elija un banco')); ?>
		<?php echo $form->error($model,'banco', NULL, $enableAjaxValidation=false); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cuentacliente'); ?>
		<?php echo $form->textField($model,'cuentacliente',array('size'=>17,'maxlength'=>17)); ?>
		<?php echo $form->error($model,'cuentacliente'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'carnet'); ?>
		<?php echo $form->textField($model,'carnet',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'carnet'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'carrera'); ?>
		<?php echo $form->dropDownList($model, 'carrera',
                        CHtml::listData(Carrera::model()->findAll(), 'nombre', 'nombre'), array('empty'=>'Elija una carrera')); ?>
		<?php echo $form->error($model,'carrera', NULL, $enableAjaxValidation=false); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'codigo'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                        'attribute'=>'codigo',
                        'model'=>$model,
                        'sourceUrl'=>array('asistentecontroller/aclist'),
                        'name'=>'codigo_autocomplete',
                        'options'=>array(
                            'minLength'=>3
                        ),
                        'htmlOptions'=>array(
                            'size'=>20, 'maxlength'=>20
                        )
                        )); ?>
		<?php echo $form->error($model,'codigo'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'rol'); ?>
		<?php echo $form->dropDownList($model, 'rol',
                        CHtml::listData(RolAsistente::model()->findAll(), 'nombre', 'nombre'), array('empty'=>'Elija un rol')) ?>
		<?php echo $form->error($model,'rol', NULL, $enableAjaxValidation=false); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'horas'); ?>
		<?php echo $form->textField($model,'horas',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'horas'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'telefono'); ?>
		<?php echo $form->textField($model,'telefono',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'telefono'); ?>
	</div>
        
        <div class="row">
		<?php echo $form->labelEx($model,'correo'); ?>
		<?php echo $form->textField($model,'correo',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'correo'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Create'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->