<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'proyectos-formactualizar',
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
            ));
    ?>


    <p class="note">Campos con <span class="required">*</span> son obligatorios.</p>
        <?php echo $form->errorSummary(array($modelproyectos)); ?>

    <div class="row">
        <?php echo $form->labelEx($modelproyectos, 'codigo'); ?>
<?php echo $form->textField($modelproyectos, 'codigo', array('size' => 20, 'maxlength' => 20)); ?>
<?php echo $form->error($modelproyectos, 'codigo'); ?>               
    </div>

    <div class="row">
        <?php echo $form->labelEx($modelproyectos, 'nombre'); ?>
<?php echo $form->textArea($modelproyectos, 'nombre', array('size' => 60, 'maxlength' => 500, 'style' => 'width:100%; heigth:100%;')); ?>
<?php echo $form->error($modelproyectos, 'nombre'); ?>
    </div>
    
 <div class="row">
        <?php echo $form->labelEx($modelproyectos, 'fecha_inicio_search'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'fecha_inicio',
            //'value' => $modelperiodos->attributes['inicio'],
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
        ));
        ?>
        <?php /*echo $form->error($modelperiodos, 'inicio');*/ ?>

    </div>

    <div class="row">
        <?php echo $form->labelEx($modelproyectos, 'fecha_fin_search'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'fecha_fin', //CHtml::activeName($modelperiodos, 'fin'),
            //'value' => $modelperiodos->attributes['fin'],
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
        ));
        ?>
        <?php /*echo $form->error($modelperiodos, 'fin');*/ ?>

    </div>
    
    <div class="row">
        <?php echo $form->labelEx($modelproyectos, 'tipoproyecto'); ?>
        <?php echo $form->dropDownList($modelproyectos, 'tipoproyecto', CHtml::listData(TipoProyecto::model()->findAll(), 'idtbl_tipoproyecto', 'nombre'), array('empty' => 'Elija el tipo de proyecto', 'id' => 'Proyectos_tipoproyecto'))
        ?>		 
<?php echo $form->error($modelproyectos, 'tipoproyecto'); ?>
    </div>        

    <div class="row">
        <?php echo $form->labelEx($modelproyectos, 'idtbl_objetivoproyecto'); ?>
        <?php echo $form->dropDownList($modelproyectos, 'idtbl_objetivoproyecto', CHtml::listData(ObjetivoProyecto::model()->findAll(), 'idtbl_objetivoproyecto', 'nombre'), array('empty' => 'Elija el objetivo del proyecto', 'id' => 'Proyectos_idtbl_objetivoproyecto'))
        ?>
<?php echo $form->error($modelproyectos, 'idtbl_objetivoproyecto'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($modelproyectos, 'idtbl_adscrito'); ?>
        <?php echo $form->dropDownList($modelproyectos, 'idtbl_adscrito', CHtml::listData(Adscrito::model()->findAll(), 'idtbl_adscrito', 'nombre'), array('empty' => 'Elija la adscripción del proyecto', 'id' => 'Proyectos_idtbl_adscrito'))
        ?>
    <?php echo $form->error($modelproyectos, 'idtbl_adscrito'); ?>
    </div>  
    
    <?php
    
    //leftOption contiene todos los sectores beneficiados
    $leftOption = CHtml::listData(SectorBeneficiado::model()->findAll(), 'idtbl_sectorbeneficiado', 'nombre');    
    $rightOption = "";
    if(isset($modelproyectos->idtbl_sectorbeneficiado)){
        $leftOption = ProyectosSectorbeneficiado::getDifference($leftOption,$modelproyectos->idtbl_sectorbeneficiado);
        $rightOption = CHtml::listData($modelproyectos->idtbl_sectorbeneficiado, 'idtbl_sectorbeneficiado', 'nombre');
    }
      
    $this->widget('application.extensions.optiontransferselect.Optiontransferselect', array(
        'leftTitle' => 'Sectores disponibles',
        'rightTitle' => 'Sectores beneficiados',
        'name' => 'ProyectosSource[idtbl_sectorbeneficiado][]',
        'list' => $leftOption,
        'doubleList' => $rightOption,
        'doubleName' => 'Proyectos[idtbl_sectorbeneficiado][]'));
    ?>
    


    <div class="row buttons">
<?php echo CHtml::submitButton('Guardar cambios'); ?>
    </div>



<?php $this->endWidget(); ?>

</div>