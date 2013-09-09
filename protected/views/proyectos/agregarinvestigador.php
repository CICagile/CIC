<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $investigador Investigador */
/* @var $periodo Periodos */
/* @var $horas array */
$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$model->codigo => array('ver','id'=>$model->idtbl_Proyectos),
        'Agregar investigador',
);

$this->menu=array(	
        array('label'=>'Actualizar información del proyecto', 'url'=>array('actualizar', 'id'=>$model->idtbl_Proyectos)),
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Ver Proyecto', 'url'=>array('ver','id'=>$model->idtbl_Proyectos)),
        array('label'=>'Nuevo Proyecto', 'url'=>array('crear')),
);

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
	'id'=>'agregarinvestigador-form',
	'enableAjaxValidation'=>true,
)); ?>
        
    <h2>Agregar investigador al Proyecto: <?php echo $model->codigo?></h2>
    <p>Periodo del proyecto: <?php echo $model->inicio
    .' hasta '.$model->fin?></p>

	<p class="note">Campos con <span class="required">*</span> son obligatorios.</p>
        
        <?php echo $form->errorSummary(array($periodo,$investigador),'Se han detectado los siguientes errores:'); ?>
        
        <div class="row">
        <label for="codigo">Proyecto</label>  
        <?php echo $form->textArea($model,'nombre',array('readonly' => 'readonly', 'rows'=>'4' ,'cols'=> '50')); ?>                 
	</div>
               
        <div class="row">
        <label for="investigador">Investigador (cédula)<span class="required">* </span></label>
        <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'attribute'=>'cedula',
            'model'=>$investigador,
            'source'=>$this->createUrl('proyectos/investigadorautocomplete'),
            'options'=>array(
                'showAnim'=>'fold',
            ),
            'htmlOptions'=>array(
                'size'=>20, 'maxlength'=>20
            )
            )); ?>
        <?php echo $form->error($investigador,'cedula'); ?>
        </div>
        
        
        <div class="row">
            <?php echo $form->labelEx($investigador,'rol') ?>
            <?php echo $form->dropDownList($investigador, 'rol',
                        CHtml::listData(RolesInvestigadores::model()->findAll(), 'nombre', 'nombre'), array('empty'=>'Elija un rol')) ?>
            <?php echo $form->error($investigador,'rol', NULL, $enableAjaxValidation=false); ?>
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
        
        <div id="formhoras" class="row-box">
            <div class="row">
                <?php echo $form->labelEx($investigador,'horas'); ?>
                <?php echo CHtml::textField("cantidad_horas"); ?>
                <?php echo $form->error($investigador,'horas'); ?>
            </div>
        
            <div class="row">
                <?php echo CHtml::label('Tipo de Horas','tipo_horas'); ?>
                <?php echo CHtml::dropDownList('tipo_horas', 'empty',
                        CHtml::listData(TipoHoraInvestigador::model()->findAll(), 'nombre', 'nombre'), array('empty'=>'Elija una opción')) ?>
                <?php echo $form->error($investigador,'horas', NULL, false); ?>
            </div>
            <span style="clear:none;float:right;"><a id="minus" href="">[-]</a> <a id="plus" href="">[+]</a></span>
            <br>
        </div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Agregar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
