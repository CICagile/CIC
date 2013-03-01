<?php
/* @var $this ProyectosController */
/* @var $model Proyectos */

$this->breadcrumbs=array(
	'Proyectos'=>array('admin'),
	'Lista de Proyectos Antiguos',
);

$this->menu=array(
        array('label'=>'Ver Proyectos Activos', 'url'=>array('admin')),	
);
?>

<h1>Lista de Proyectos Antiguos</h1>
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
                    'template'=>'{view}{ampliar}',
                    'viewButtonUrl'=>'Yii::app()->controller->createUrl("proyectos/verantiguos", array("id"=>$data["idtbl_Proyectos"]))',
                    'viewButtonLabel' => 'Ver información detallada del proyecto.',  
                    'buttons'=>array
                    (                        
                        'ampliar' => array
                        (
                            'label'=>'Ampliar el proyecto.',
                            'imageUrl'=>Yii::app()->request->baseUrl.'/images/time_add.png',
                            'url'=>'Yii::app()->createUrl("proyectos/ampliarproyecto", array("id"=>$data["idtbl_Proyectos"]))',
                        ),  
                    ),
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
    $(".filters > td:last").css({"width" : "65px"});
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
    $('.filters > td:last').css({'width' : '65px'})
    
    
    $('.delete').click(function(e){
            e.preventDefault();            
    });
  });
</script>