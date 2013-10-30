<?php
$imagenescarapela = $escarapela;
$imagencertificado = $certificado;

$campo_IdEvento = array('name' => 'IdEvento', 'id' => 'IdEvento', 'value' => set_value('IdEvento',$IdEvento), 'hidden' => '', 'style' => 'display:none');
$campo_nombre = array('name' => 'nombre', 'id' => 'nombre', 'style' => 'width: 710px', 'value' => set_value('nombre',$nombre));
$campo_descripcion = array('name' => 'descripcion', 'id' => 'descripcion', 'style' => 'width:710px', 'value' => set_value('descripcion',$descripcion));
$campo_fechainicio = array('name' => 'fechainicio', 'id' => 'fechainicio', 'value' => set_value('fechainicio',$fechainicio));
$campo_fechafin = array('name' => 'fechafin', 'id' => 'fechafin', 'value' => set_value('fechafin',$fechafin));
$campo_ubicacion = array('name' => 'ubicacion', 'id' => 'ubicacion', 'value' => set_value('ubicacion',$ubicacion));
$campo_direccion = array('name' => 'direccion', 'id' => 'direccion', 'value' => set_value('direccion',$direccion));
$campo_ciudad = array('name' => 'ciudad', 'id' => 'ciudad', 'value' => set_value('ciudad',$ciudad), 'hidden' => '', 'style' => 'display:none');
$campo_escarapela = array ("name" => "escarapela", "multiple"=>"multiple", "id"=>"escarapela", "onchange"=>"displayResultescarapela()", 'style' => 'width:110px');
$campo_imagenescarapela = array('name' => 'imagenescarapela', 'id' => 'imagenescarapela', 'value' => set_value('imagenescarapela',$imagenescarapela), 'readonly' => 'readonly');
$campo_certificado = array ("name" => "certificado",  "multiple"=>"multiple" , "id"=>"certificado", "onchange"=>"displayResultcertificado()", 'style' => 'width:110px');
$campo_imagencertificado = array('name' => 'imagencertificado', 'id' => 'imagencertificado', 'value' => set_value('imagencertificado',$imagencertificado), 'readonly' => 'readonly');
$campo_banner = array ("name" => "banner", "multiple"=>"multiple", "id"=>"banner", "onchange"=>"displayResultbanner()", 'style' => 'width:110px');
$campo_imagenbanner = array('name' => 'imagenbanner', 'id' => 'imagenbanner', 'value' => set_value('imagenbanner',$imagenbanner), 'readonly' => 'readonly');
$campo_maximoasistentes = array('name' => 'maximoasistentes', 'id' => 'maximoasistentes', 'value' => set_value('maximoasistentes',$maximoasistentes));
$PermitirImpresionUsuario = array('name' => 'PermitirImpresionUsuario', 'id' => 'PermitirImpresionUsuario', 'value' => '1', 'checked' => $PermitirImpresionU,'style' => 'margin:10px');

$ImpresionCertificadoHorizontal = array('name' => 'ImpresionCertificadoHorizontal', 'id' => 'ImpresionCertificadoHorizontal', 'value' => '1', 'checked' => $ImpresionCertificadoH,'style' => 'margin:10px');
$ImpresionCertificadoMedia = array('name' => 'ImpresionCertificadoMedia', 'id' => 'ImpresionCertificadoMedia', 'value' => '1', 'checked' => $ImpresionCertificadoM,'style' => 'margin:10px');

$submit = array(    'name'      => 'submit',    'id'        => 'submit',    'value'     => 'Guardar',);

//Array del botón regresar
$regresar = array( 'type' => 'button', 'name' => 'regresar', 'id' => 'regresar', 'value' => 'Regresar', 'onclick' => 'history.back()' );

$url = base_url().'evento_controller';

echo form_open_multipart('eventoregistro_controller/registro');
?>

<div id="form" class="container_12">
    <div class="grid_11" id="registro_view">
        <!--Campos obligatorios-->
        <?php
        echo form_fieldset('Estos datos son obligatorios para crear tu evento', 'class="fieldset"');
        echo form_fieldset_close();
        ?>
        <table width="100%">
            <tr>
                <td>
                    <?php echo form_label('Evento'); ?>
                </td>
                <td colspan="13">
                    <?php
                    echo form_input($campo_IdEvento);  					
                    echo form_input($campo_ciudad);  					
                    echo form_input($campo_nombre);                     
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <span class="error">
                    <?php echo form_error('nombre'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo form_label('Inicia el (Haz click)'); ?>
                </td>
                <td>
                    <?php echo form_input($campo_fechainicio);?>
                </td>
                <td>
                    <?php echo form_label('Finaliza el (Haz click)'); ?>
                </td>
                <td>
                    <?php echo form_input($campo_fechafin); ?>
                </td>
                <td>
                    <?php echo form_label('Asistentes'); ?>
                </td>
                <td>
                    <?php echo form_input($campo_maximoasistentes); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <span class="error">
                    <?php echo form_error('fechainicio'); ?>
                    </span>
                </td>
                <td></td>
                <td>
                    <span class="error">
                    <?php echo form_error('fechafin'); ?>                        
                    </span>
                </td>
                <td></td>
                <td>
                    <span class="error">
                        <?php echo form_error('maximoasistentes'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo form_label('Describe el evento'); ?>
                </td>
                <td colspan="10">
                    <?php echo form_textarea($campo_descripcion); ?>
                </td>
            </tr>
        </table>
        
        <!--Imagenes de escarapela y certificado-->
        <?php
        echo form_fieldset('Subir Escarapela', 'class="fieldset"');
        echo form_fieldset_close();
        ?>
        <div class="contenedor_inicio">
            <table width="100%">
                <tr>
                    <td style="vertical-align: middle"><strong><label style="font-size: 10px; color: blue;">El tama&ntilde;o debe ser de 9x13 cms. o 532x768 px</label></strong></td>
                    <td style="vertical-align: middle"><?php echo form_upload($campo_escarapela); ?></td>
                    <td style="vertical-align: middle"><?php echo form_input($campo_imagenescarapela); ?></td>
                    <td style="vertical-align: middle"><?php echo anchor_popup(site_url('escarapela/muestra'), 'Imprime una muestra'); ?></td> 
                </tr>
                <tr>
                    <td colspan="4">
                        <?php
                        echo form_checkbox($PermitirImpresionUsuario);
                        echo form_label('¿Los registrados pueden imprimir la escarapela?');
                        ?>
                    </td>
                    <span class="error">
                    <?php echo form_error('PermitirImpresionUsuario'); ?>
                    </span>   
                </tr>
            </table>
        </div>
        <?php
        echo form_fieldset('Subir Certificado', 'class="fieldset"');
        echo form_fieldset_close();
        ?>
        <div class="contenedor_inicio">
            <table width="100%">
                <tr>
                    <td style="vertical-align: middle"><strong><label style="font-size: 10px; color: blue;">El tama&ntilde;o debe ser de 18x14cms. o 1062x826 px</label></strong></td>
                    <td style="vertical-align: middle"><?php echo form_upload($campo_certificado); ?></td>
                    <td style="vertical-align: middle"> <?php echo form_input($campo_imagencertificado); ?></td>
                    <td style="vertical-align: middle"><?php echo anchor_popup(site_url('certificado/muestra'), 'Imprime una muestra'); ?></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <?php echo form_checkbox($ImpresionCertificadoHorizontal); ?>
                        <?php echo form_label('El certificado del evento ser&aacute; hoja completa'); ?>
                    </td>
                    <span class="error">
                        <?php echo form_error('ImpresionCertificadoHorizontal'); ?>
                    </span>
                </tr>
                <tr>
                    <td colspan="4">
                        <?php echo form_checkbox($ImpresionCertificadoMedia); ?>
                        <?php echo form_label('El certificado del evento ser&aacute; hoja media'); ?>
                    </td>
                    <span class="error">
                        <?php echo form_error('ImpresionCertificadoMedia'); ?>
                    </span>
                </tr>
            </table>
        </div>
        <?php
        echo form_fieldset('Subir Banner', 'class="fieldset"');
        echo form_fieldset_close();
        ?>
        <div class="contenedor_inicio">
            <table width="100%">
                <tr>
                    <td style="vertical-align: middle"><strong><label style="font-size: 10px; color: blue;">El tama&ntilde;o debe ser de 31x4 cms. o 900x105 px</label></strong></td>
                    <td style="vertical-align: middle"><?php echo form_upload($campo_banner); ?></td>
                    <td style="vertical-align: middle"><?php echo form_input($campo_imagenbanner); ?></td>
                    <td style="vertical-align: middle"><?php echo anchor_popup(site_url('escarapela/banner'), 'Imprime una muestra'); ?></td>
                </tr>
            </table>
        </div>
        
        <!--Otros datos-->
        <?php
        echo form_fieldset('Datos de ubicaci&oacute;n', 'class="fieldset"');
        echo form_fieldset_close();
        ?>
        <table width="100%">
            <tr>
                <th><?php echo form_label('Lugar'); ?></th>
                <td><?php echo form_input($campo_ubicacion); ?></td>
                <th><?php echo form_label('Direcci&oacute;n'); ?></th>
                <td><?php echo form_input($campo_direccion); ?></td>
            </tr>
            <tr>
                <th><?php echo form_label('Ciudad'); ?></th>
                <td><input id="city" value  = "<?php echo $ciudad; ?>"></td>
                <td colspan="2">
                    <div id="log" style="font-size:0.9em; height: 25px; width: 400px; border-radius: 4px; -moz-border-radius:4px; -webkit-border-radius:4px; border:1px solid #FFD966; background:#FFEEBC; overflow: hidden;" class="ui-widget-content">
                        <?php echo $ciudad; ?>
                    </div>
                </td> 
            </tr>
        </table>
        <?php
        echo form_fieldset('', 'class="fieldset"');
        echo form_fieldset_close();
        ?>
        <table width="100%">
            <tr>
                <td>
                    <?php
                    echo form_submit($submit);
                    echo form_close();
                    ?>
                </td>
                <td>
                    <?php                    
                    echo form_input($regresar);
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>                  
<script>    
    $(function() {
        function log( message ) {        
            document.getElementById('log').innerHTML = message;
            document.getElementById('ciudad').value = message;        
        }

        $( "#city" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "http://ws.geonames.org/searchJSON",
                    dataType: "jsonp",
                    data: {
                        featureClass: "P",
                        style: "full",
                        maxRows: 12,
                        name_startsWith: request.term
                    },
                    success: function( data ) {
                        response( $.map( data.geonames, function( item ) {
                            return {
                                label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
                                value: item.name
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                log( ui.item ?
                    ui.item.label :
                    "Nada seleccionado: El dato era " + this.value);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });    
    });

    //funcion que asigna imagen  escarapela   
    function displayResultescarapela()
    {
        var xi=document.getElementById("escarapela").value;
        var separador = "\\";
        var xf = xi.split(separador);
        var x = xf[xf.length-1];
        var separador = ".";
        var extension = x.split(separador);
        //alert(extension[extension.length-1]);
        if (extension[extension.length-1] != "JPG" && extension[extension.length-1] != "jpg" && extension[extension.length-1] != "PNG" && extension[extension.length-1] != "png" && extension[extension.length-1] != "GIF" && extension[extension.length-1] != "gif"){
            //Mensaje de error
            alert("El archivo seleccionado es invalido");
            document.getElementById("escarapela").value = "";            
            return false;
        }  
        document.getElementById("imagenescarapela").value = x;
    }

    //funcion que asigna imagen  certificado
    function displayResultcertificado()
    {
        var yi=document.getElementById("certificado").value;
        var separador = "\\";
        var yf = yi.split(separador);
        var y = yf[yf.length-1];
        var separador = ".";
        var extension = y.split(separador);        
        if (extension[extension.length-1] != "JPG" && extension[extension.length-1] != "jpg" && extension[extension.length-1] != "PNG" && extension[extension.length-1] != "png" && extension[extension.length-1] != "GIF" && extension[extension.length-1] != "gif"){
            //Mensaje de error
            alert("El archivo seleccionado es invalido");
            document.getElementById("certificado").value = "";            
            return false;
        }
        document.getElementById("imagencertificado").value = y;
    }
    
     //funcion que asigna imagen  banner
    function displayResultbanner()
    {
        var ui=document.getElementById("banner").value;
        var separador = "\\";
        var uf = ui.split(separador);
        var u = uf[uf.length-1];
        var separador = ".";
        var extension = u.split(separador);        
        if (extension[extension.length-1] != "JPG" && extension[extension.length-1] != "jpg" && extension[extension.length-1] != "PNG" && extension[extension.length-1] != "png" && extension[extension.length-1] != "GIF" && extension[extension.length-1] != "gif"){
            //Mensaje de error
            alert("El archivo seleccionado es invalido");
            document.getElementById("banner").value = "";            
            return false;
        }
        document.getElementById("imagenbanner").value = u;
    }
    
    //Funcion usada para controlar las fechas y horas
    $(function () {
           var now = new Date();
           
            $('#fechainicio').scroller({
                preset: 'datetime',
                minDate: new Date(now.getYear(), now.getMonth(), now.getDate()),
                theme: 'default',
                display: 'modal',
                mode: 'scroller',
                dateOrder: 'dd mm yy',
                dateFormat: 'dd-mm-yy'
            });
            
            var now1 = new Date();
            $('#fechafin').scroller({
                preset: 'datetime',
                minDate: new Date(now1.getYear(), now1.getMonth(), now1.getDate()),
                theme: 'default',
                display: 'modal',
                mode: 'scroller',
                dateOrder: 'dd mm yy',
                dateFormat: 'dd-mm-yy'
            });
    });
    
    //Funcion usada para validar check box de impresion certificados
    //validacion ImpresionCertificadoHorizontal
    $(document).ready(function(){    
        $("#ImpresionCertificadoHorizontal").click(function() {  
            if($("#ImpresionCertificadoHorizontal").is(':checked')) {  
                document.getElementById("ImpresionCertificadoHorizontal").checked = true;
                document.getElementById("ImpresionCertificadoMedia").checked = false;
            } else {  
                document.getElementById("ImpresionCertificadoHorizontal").checked = true;
                document.getElementById("ImpresionCertificadoMedia").checked = false;
            }  
        });    
    });
    //validacion ImpresionCertificadoMedia
    $(document).ready(function(){    
        $("#ImpresionCertificadoMedia").click(function() {  
            if($("#ImpresionCertificadoMedia").is(':checked')) {  
                document.getElementById("ImpresionCertificadoHorizontal").checked = false;
                document.getElementById("ImpresionCertificadoMedia").checked = true;  
            } else {  
                document.getElementById("ImpresionCertificadoHorizontal").checked = false;
                document.getElementById("ImpresionCertificadoMedia").checked = true;  
            }  
        });    
    });
</script>