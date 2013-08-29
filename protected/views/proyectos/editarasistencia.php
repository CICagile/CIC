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

<h1>Editar asistencia de <?php echo $asistente->carnet?></h1>
<div class="form">
    <p class="note">Los campos marcados con <span class="required">*</span> son obligatorios.</p>
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'editar-rol',
    )); ?>
    <fieldset>
        <legend><h2>Cambiar Rol</h2></legend>
        <?php   $errorHoras = $asistente->getErrors(); //Se guardan por si el formulario de horas los necesita.
                $asistente->clearErrors('horas'); //para que no muestre los errores del formulario de horas.?> 
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
                'name' => 'Rol[inicio]',
                'value' => $periodos['rol']->attributes['inicio'],
                'language' => 'es',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'dd-mm-yy',
                    'changeYear' => true,
                    'changeMonth' => true,
                    'minDate' => $model->inicio,
                ),
                'htmlOptions' => array(
                    'readonly' => 'readonly'
                ),
            )); ?>
            <?php echo $form->error($periodos['rol'],'inicio'); ?>
            <br>Sólo corregir fecha inicio
                <input type="checkbox" name="correccion" id="vehicle" value="1"
                    <?php if ($periodos['rol']->inicio == $periodos['asistencia']->inicio && !$periodos['rol']->hasErrors())
                            echo 'DISABLED';
                    ?>
                />
            <div class="row-buttons">
                <?php echo CHtml::submitButton("Guardar rol"); ?>
            </div>
        </div>
    </fieldset>
    <?php $this->endWidget() ?>
</div>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'editar-horas',
    )); ?>
    <fieldset>
        <legend><h2>Cambiar Horas</h2></legend>
        <?php   $asistente->addErrors($errorHoras); //Se restauran los errores que fueron borrados.
                $asistente->clearErrors('rol'); //para que no muestre los errores del formulario de roles.?> 
        <?php echo $form->errorSummary(array($periodos['horas'],$asistente),'Se han detectado los siguientes errores:'); ?>
        <div class="row">
            <?php echo $form->labelEx($asistente,'horas'); ?>
            <?php echo $form->textField($asistente,'horas',array('size'=>4,'maxlength'=>4)); ?>
            <?php echo $form->error($asistente,'horas'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($periodos['horas'], 'inicio'); ?>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Horas[inicio]',
                'value' => $periodos['horas']->attributes['inicio'],
                'language' => 'es',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'dd-mm-yy',
                    'changeYear' => true,
                    'changeMonth' => true,
                    'minDate' => $model->inicio,
                ),
                'htmlOptions' => array(
                    'readonly' => 'readonly'
                ),
            )); ?>
            <?php echo $form->error($periodos['horas'],'inicio'); ?>
            <br>Sólo corregir fecha inicio
                <input type="checkbox" name="correccion" id="vehicle" value="1"
                    <?php if ($periodos['horas']->inicio == $periodos['asistencia']->inicio && !$periodos['horas']->hasErrors())
                            echo 'DISABLED';
                    ?>
                />
            <div class="row-buttons">
                <?php echo CHtml::submitButton("Guardar horas"); ?>
            </div>
        </div>
    </fieldset>
    <?php $this->endWidget() ?>
</div>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'editar-horas',
    )); ?>
    <fieldset>
        <legend><h2>Cambiar Período de Asistencia</h2></legend>
        <?php echo $form->errorSummary($periodos['asistencia'],'Se han detectado los siguientes errores:'); ?>
        <div class="row">
            <?php echo $form->labelEx($periodos['asistencia'], 'inicio'); ?>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Asistencia[inicio]',
                'value' => $periodos['asistencia']->attributes['inicio'],
                'language' => 'es',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'dd-mm-yy',
                    'changeYear' => true,
                    'changeMonth' => true,
                    'minDate' => $model->inicio,
                ),
                'htmlOptions' => array(
                    'readonly' => 'readonly'
                ),
            )); ?>
            <?php echo $form->error($periodos['asistencia'],'inicio'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($periodos['asistencia'], 'fin'); ?>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Asistencia[fin]',
                'value' => $periodos['asistencia']->attributes['fin'],
                'language' => 'es',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'dd-mm-yy',
                    'changeYear' => true,
                    'changeMonth' => true,
                    'maxDate' => $model->fin,
                ),
                'htmlOptions' => array(
                    'readonly' => 'readonly'
                ),
            )); ?>
            <?php echo $form->error($periodos['asistencia'],'fin'); ?>
            <div class="row-buttons">
                <?php echo CHtml::submitButton("Guardar período"); ?>
            </div>
        </div>
    </fieldset>
    <?php $this->endWidget() ?>
</div>