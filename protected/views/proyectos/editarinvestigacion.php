<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */
/* @var $periodos array */
/* @var $investigador Investigador */
$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	$model->codigo => array('ver','id'=>$model->idtbl_Proyectos),
        'Editar Investigación',
);

$this->menu=array(	
        array('label'=>'Actualizar información del proyecto', 'url'=>array('actualizar', 'id'=>$model->idtbl_Proyectos)),
	array('label'=>'Agregar asistente', 'url'=>array('agregarasistente', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Agregar investigador', 'url'=>array('agregarinvestigador', 'id'=>$model->idtbl_Proyectos)),
        array('label'=>'Ver Proyecto', 'url'=>array('ver','id'=>$model->idtbl_Proyectos)),
        array('label'=>'Nuevo Proyecto', 'url'=>array('crear')),
);

?>

<h1>Editar Investigación de <?php echo $investigador->nombre . ' ' . $investigador->apellido1;?></h1>
<div class="form">
    <p class="note">Los campos marcados con <span class="required">*</span> son obligatorios.</p>
    <?php /*$form = $this->beginWidget('CActiveForm', array(
        'id' => 'editar-rol',
    )); ?>
    <fieldset>
        <legend><h2>Cambiar Rol</h2></legend>
        <?php /*$errorHoras = $investigador->getErrors(); //Se guardan por si el formulario de horas los necesita.
                $investigador->clearErrors('horas'); //para que no muestre los errores del formulario de horas.?> 
        <?php echo $form->errorSummary(array($periodos['rol'],$investigador),'Se han detectado los siguientes errores:'); ?>
        <div class="row">
            <?php echo $form->labelEx($investigador,'rol'); ?>
            <?php echo $form->dropDownList($investigador, 'rol',
                    CHtml::listData(RolAsistente::model()->findAll(), 'idtbl_RolesAsistentes', 'nombre'), array('empty'=>'Elija un rol')); ?>
            <?php echo $form->error($investigador,'rol'); ?>
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
        <?php   $investigador->addErrors($errorHoras); //Se restauran los errores que fueron borrados.
                $investigador->clearErrors('rol'); //para que no muestre los errores del formulario de roles.?> 
        <?php echo $form->errorSummary(array($periodos['horas'],$investigador),'Se han detectado los siguientes errores:'); ?>
        <div class="row">
            <?php echo $form->labelEx($investigador,'horas'); ?>
            <?php echo $form->textField($investigador,'horas',array('size'=>4,'maxlength'=>4)); ?>
            <?php echo $form->error($investigador,'horas'); ?>
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
</div>*/?>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'editar-horas',
    )); ?>
    <fieldset>
        <legend><h2>Cambiar Período de Investigación</h2></legend>
        <?php echo $form->errorSummary($periodos['investigacion'],'Se han detectado los siguientes errores:'); ?>
        <div class="row">
            <?php echo $form->labelEx($periodos['investigacion'], 'inicio'); ?>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Investigacion[inicio]',
                'value' => $periodos['investigacion']->attributes['inicio'],
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
            <?php echo $form->error($periodos['investigacion'],'inicio'); ?>
        </div>
        <div class="row">
            <?php echo $form->labelEx($periodos['investigacion'], 'fin'); ?>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Investigacion[fin]',
                'value' => $periodos['investigacion']->attributes['fin'],
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
            <?php echo $form->error($periodos['investigacion'],'fin'); ?>
            <div class="row-buttons">
                <?php echo CHtml::submitButton("Guardar período"); ?>
            </div>
        </div>
    </fieldset>
    <?php $this->endWidget() ?>
</div>