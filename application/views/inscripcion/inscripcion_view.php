<?php
//Array del submit
$submit = array(
    'name'      => 'submit',
    'id'        => 'submit',
    'value'     => 'Guardar',
);

//Array del botón regresar
$salir = array(
    'type' => 'button',
    'name' => 'salir',
    'id' => 'salir',
    'value' => 'Cancelar y volver',
    'onclick'=> 'history.back()'
);

$campo_ciudad = array('name' => 'ciudad', 'id' => 'ciudad', 'value' => set_value('ciudad',$ciudad), 'hidden' => '', 'style' => 'display:none');

echo form_open('inscripcion/validar');
?>

<div id="form"  class="container_12">
    <div class="titulos_formularios">Inscr&iacute;bete</div><br>
    <div class="grid_11" id="registro_view" align="center">
        <table width="100%">
            <tr>
                <th><?php echo form_label('N&uacute;mero de documento*'); ?></th>
                <td>
                    <?php echo form_input(array('name' => 'cedula', 'value' => set_value('cedula'))); ?>
                    <span class="error">
                    <?php echo form_error('cedula'); ?>
                    </span>
                </td>
                <th><?php echo form_label('Nombre*'); ?></th>
                <td>
                    <?php echo form_input(array('name' => 'nombre', 'value' => set_value('nombre'))); ?>
                    <span class="error">
                    <?php echo form_error('nombre'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?php echo form_label('Apellido*'); ?></th>
                <td>
                    <?php echo form_input(array('name' => 'apellido', 'value' => set_value('apellido'))); ?>
                    <span class="error">
                    <?php echo form_error('apellido'); ?>
                    </span>
                </td>
                <th><?php echo form_label('Elija su nombre de usuario*&nbsp;'); ?></th>
                <td>
                    <?php echo form_input(array('name' => 'nombre_usuario', 'value' => set_value('nombre_usuario'))); ?>
                    <span class="error">
                    <?php echo form_error('nombre_usuario'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?php echo form_label('Elija su contrase&ntilde;a'); ?></th>
                <td>
                    <?php echo form_password(array('name' => 'password1', 'value' => '',)); ?>
                    <span class="error">
                        <?php echo form_error('password1'); ?>
                    </span>
                </td>
                <th><?php echo form_label('Repita su contrase&ntilde;a'); ?></th>
                <td>
                    <?php echo form_password(array('name' => 'password2', 'value' => '')); ?>
                    <span class="error">
                        <?php echo form_error('password2'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><?php echo form_label('Correo electr&oacute;nico');?></th>
                <td>
                    <?php echo form_input(array('name' => 'email', 'value' => set_value('email', ''))); ?>
                    <span class="error">
                    <?php echo form_error('email'); ?>
                    </span>
                </td>
                <th><?php echo form_label('N&uacute;mero de tel&eacute;fono'); ?></th>
                <td>
                    <?php echo form_input(array('name' => 'telefono', 'value' => set_value('telefono'))); ?>
                    <span class="error">
                        <?php echo form_error('telefono'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td><?php echo form_label('Ciudad'); ?></td>
                <td>
                    <?php echo form_input($campo_ciudad); ?> 
                    <input id="city" value="<?php echo $ciudad; ?>">
                </td>
                <td colspan="2">
                    <div id="log" style="font-size:0.9em; height: 25px; width: auto; border-radius: 4px; -moz-border-radius:4px; -webkit-border-radius:4px; border:1px solid #FFD966; background:#FFEEBC; overflow: hidden;" class="ui-widget-content">
                        <?php echo $ciudad; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div align="center">
                        <?php
                        echo form_submit($submit);
                        echo form_input($salir);
                        ?>
                    </div>
                </td>
            </tr>
        </table>
        <?php echo form_close(); ?>
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
</script>