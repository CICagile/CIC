<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	'Lista de Proyectos',
);

$this->menu=array(	
	array('label'=>'Crear Proyecto', 'url'=>array('crear')),
);
?>

<h1>Lista de Proyectos Activos</h1>
<p>¿Buscando proyectos antiguos? <?php echo CHtml::link('Ver aquí',array('proyectos/adminantiguos')); ?></p>
<?php
    
    $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'area-grid',
    'afterAjaxUpdate'=>'cssgrid',
    'dataProvider'=>$dataProvider,
    'columns'=>array(
                array(
                      'header' => 'Codigo',
                      'name' => 'codigo', 
                ),
                array(
                      'header' => 'Nombre del Proyecto',
                      'name' => 'nombre', 
                ),  				
		array(
                      'header' => 'Fecha inicio',
                      'name' => 'inicio',                       
                ),
                array(
                      'header' => 'Fecha fin',
                      'name' => 'fin',                      
                ), 
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{view}{update}',
                    'viewButtonUrl'=>'Yii::app()->controller->createUrl("proyectos/ver", array("id"=>$data["idtbl_Proyectos"]))',
                    'viewButtonLabel' => 'Ver información detallada del proyecto.',
                    'updateButtonUrl'=>'Yii::app()->controller->createUrl("proyectos/actualizar", array("id"=>$data["idtbl_Proyectos"]))',
                    'updateButtonLabel' => 'Actualizar información del proyecto.',
               ),
        ),
    'filter'=>$filtersForm,
));


//Esto se utiliza para mantener el estilo css de las columnas del Grid luego de un request AJAX
$string = "'";//Esto string se utiliza para poder crear correctamente el string del Script
Yii::app()->clientScript->registerScript('css-grid', '
function cssgrid(id,data)
{
    $('.$string.'[name="FiltersForm[inicio]"]'.$string.').css({"width" : "70px"});
    $('.$string.'[name="FiltersForm[fin]"]'.$string.').css({"width" : "70px"});
    $('.$string.'[name="FiltersForm[codigo]"]'.$string.').css({"width" : "110px"});
}

'   
);
?>
<script type="text/javascript">
  //Esto se utiliza para mantener el estilo css de las columnas el Grid en Load
  $(document).ready(function() {
    $('[name="FiltersForm[inicio]"]').css({'width' : '70px'})
    $('[name="FiltersForm[fin]"]').css({'width' : '70px'}) 
    $('[name="FiltersForm[codigo]"]').css({'width' : '110px'})
    
    
    $('.delete').click(function(e){
            e.preventDefault();            
    });
  });
</script>