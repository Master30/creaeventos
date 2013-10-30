<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<title><?php echo $titulo; ?></title>

<!-- estilos -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/styles.css" media="screen"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/south-street/jquery-ui-1.9.1.custom.css" type="text/css" />
<!-- timepicker <link rel="stylesheet" href="css/jquery.ui.timepicker.css" type="text/css" />  -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/960.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/text.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/reset.css" media="screen"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/demo_table_jui.css" type="text/css" />
<!-- css Hora y fecha -->
<link href="<?php echo base_url(); ?>css/mobiscroll-2.2.custom.min.css" rel="stylesheet" type="text/css" />

<!-- icono -->
<link rel="shortcut icon" href="<?php echo base_url(); ?>img/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo base_url(); ?>img/favicon.ico" type="image/x-icon">

<!--//Para cargar las librerias de jquery -->
<script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/jquery.ui.button.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/jquery.ui.accordion.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/jquery.ui.draggable.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/jquery.ui.position.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/jquery.ui.dialog.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/jquery.ui.resizable.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/jquery.effects.core.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.bgiframe-2.1.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.8.2.js"></script>        <!--//Para cargar las ciudades -->
<script type="text/javascript" src="<?php echo base_url(); ?>js/ui/1.9.1/jquery-ui.js"></script>  <!--//Para cargar las ciudades -->
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/mobiscroll-2.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/Editor/tiny_mce.js"></script>
<script type='text/javascript'>
    //Estas acciones se realizarán cuando el DOM esté listo
    $(document).ready(function(){
        //para que se vea bien el diseño en explorer 6,7 y 8
        var e = ("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(',');
        for (var i=0; i<e.length; i++) {
            document.createElement(e[i]);
        }
        
       
        // Estos son los scripts para el menu
        $('#menu li').hover(function (){
            $(this).addClass('selected');
            $(this).find('ul:first').slideDown();
        },
        
        function (){
            $(this).removeClass('selected');
            $(this).find('ul.menuDrpDwn').hide();
        });
        
        $('.menuDrpDwn li').hover(function (){
            $(this).find('ul:first').show('slide',{direction: 'left'}, 10000);
        },

        function (){
            $(this).find('ul').hide();
        });
        //Fin scripst menu
        
        //Estilo de los botones 
        $( "#form input[type=submit], #form input[type=button], .boton").button()
        
        //Accordion
        $( "#accordion" ).accordion ({
            autoHeight: false,
            navigation: true
        });
        
        //Este script se utiliza para posicionar el puntero en el primer campo de cada formulario
        $(window).load(function () {
            $(':input:visible:enabled:first').focus();
        });
        
        
        //Buscar simple modal
        //Este script ejecuta las alertas de la aplicacion
        $( "#dialog-message" ).dialog({
            resizable: false,
            modal: true,
            buttons: {
                Ok: function() {
                    $( this ).dialog( "close" );
                }
            }
    });
    
    //Scripts para el menú
    var numeroPuntos = 1;
    var maxPuntos = 1000;
    var timerID = 0;
    var cargado = false;
 
    });
    
    //Este script establece el efecto para los mensajes de error, de informacion y de exito
    setTimeout(function(){
        $(".info, .errores, .exito, .alerta").fadeOut(800).fadeIn(800).fadeOut(500);
    }, 3000);   
    
    tinyMCE.init({
	mode : "textareas"
    });
</script>

<div class="container_12">
    <div id="logo"></div>
    <!-- Ventana de mensajes --> 
    <?php if (isset($mensaje_info)) { ?> 
        <div class="info"> 
            <?php echo $mensaje_info; ?> 
        </div>
    <?php
    }
    if(isset($mensaje_exito)){ ?>
        <div class="exito"> 
            <?php echo $mensaje_exito; ?> 
        </div>
    <?php
    }
    if(isset($mensaje_alerta)){ ?>
        <div class="alerta"> 
            <?php echo $mensaje_alerta; ?> 
        </div>
    <?php
    }
    if(isset($mensaje_error)){ ?>
        <div class="errores"> 
            <?php echo $mensaje_error; ?> 
        </div>
    <?php
    }
    ?>
    <!-- FIN Mensajes -->
    <?php
    //Habilita el menú si y solo si el usuario esta logueado
    if ($this->session->userdata('PK_IdEntidad') == TRUE) {
    ?>
    <!-----Menu principal----->
    <div id="menu">
        <div id="menu-left"></div>
        <div id="menu">
            <ul>
                <li>
                    <a href="<?php echo site_url('inicio'); ?>"><span>Inicio</span></a>
                </li>
                <li class=""><a href="#"><span>Eventos</span></a>
                    <ul class="menuDrpDwn">
                        <li><a href="<?php echo site_url('evento_controller'); ?>">Creados por m&iacute;</a></li>
                        <li><a href="<?php echo site_url('mis_eventos'); ?>">Mis Eventos</a></li>					
                    </ul>
                </li>
                <?php if ($this->session->userdata('PK_IdEntidad') == 1){ ?>
                    <li>
                        <a href=""><span>Administraci&oacute;n</span></a>
                        <ul class="menuDrpDwn">
                            <li><a href="<?php echo site_url('administracion/auditoria'); ?>">Auditor&iacute;a</a></li>
                            <li><a href="<?php echo site_url('administracion/usuarios'); ?>">Usuario</a></li>
                        </ul>
                    </li>
                <?php } ?>
                <li><a href="<?php echo site_url('sesion_controller/cerrar'); ?>"><span>Cerrar sesi&oacute;n</span></a></li>
            </ul>
        </div>
        <div id="menu-left"></div>
        <div class="clear"></div>
        <div class="titulos_formularios"><?php echo $titulo_formulario; ?></div>
    </div>
    <?php
    }
    ?>
</div>
