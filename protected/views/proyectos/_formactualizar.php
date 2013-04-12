<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'proyectos-formactualizar',
        'enableAjaxValidation' => true,
        'clientOptions' => array('validateOnSubmit' => true),
            ));
    ?>
<?php $BENEFIT_MULTIPLE_CHOICE = 'Mantenga presionada la tecla CTRL para seleccionar múltiples opciones';
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
    /* http://www.yiiframework.com/extension/optiontransferselect/ */
    //option transfers
    $leftOption = CHtml::listData(SectorBeneficiado::model()->findAll(), 'idtbl_sectorbeneficiado', 'nombre');    
    //Proyectos::model()->obtenerSectoresBeneficiados(model()->idtbl_sectorbeneficiado);
    //$modelproyectos = Proyectos::model()->obtenerProyectoconPeriodoActual(1);
    $rightOption = CHtml::listData($modelproyectos->idtbl_sectorbeneficiado, 'idtbl_sectorbeneficiado', 'nombre');
    
    print_r($rightOption);
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

</div><!-- form -->