<div id="form">
    <?php
    echo form_fieldset('Datos generales', 'class="fieldset"');
    echo form_fieldset_close();
    
    foreach($usuarios as $usuario):
        echo $id_entidad = $usuario->PK_IdEntidad;
        echo $email = $usuario->Email; 
        echo $nombre = $usuario->Nombres; 
    endforeach;
    ?>

    <?php
    echo form_fieldset('Licenciamiento', 'class="fieldset"');
    echo form_fieldset_close();
    ?>
    <table cellpadding="0" cellspacing="0" border="" class="display" id="example" style="font-size: 13px;">
        <thead>
            <tr>
                <th class="primero">Fecha de Inicio</th>
                <th>D&iacute;as Licencia</th>
                <th>Vence</th>
                <th>D&iacute;as restantes</th>
                <th class="ultimo">Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($licencias as $licencia): ?>
            <tr>
                <td>
                    <?php echo $this->auditoria_model->formato_fecha($licencia->Fecha_Inicio); ?>
                </td>
                <td style="text-align: right">
                    <?php echo $licencia->Tipo_Licencia; ?>
                </td>
                <td>
                    <?php
                    if($licencia->Fecha_Vencimiento == NULL){
                        echo '2 eventos';
                    }else{
                        echo $this->auditoria_model->formato_fecha($licencia->Fecha_Vencimiento);  
                    }
                    ?>
                </td>
                <td style="text-align: right">
                    <?php echo $licencia->Dias_Restantes; ?>
                </td>
                <td style="text-align: right">
                    <?php echo number_format($licencia->Valor, 0, '', '.'); ?>
                </td>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
    </table><br/>
    <?php
    echo form_fieldset('', 'class="fieldset"');
    echo form_fieldset_close();

    //Botones de licenciamientos
    echo form_open('licenciamiento/licenciamiento_mensual');
    echo form_hidden('id_entidad', $id_entidad);
    echo form_hidden('email', $email);
    echo form_hidden('nombre', $nombre);
    ?>

    <table width="100%">
        <tr>
            <td style="vertical-align: middle"><?php echo form_label('Licenciamiento mensual', 'tipo_licencia'); ?></td>
            <td style="vertical-align: middle"><?php echo form_input(array('name' => 'tipo_licencia', 'style' => 'text-align: right', 'value' => 30)); ?></td>
            <td style="vertical-align: middle"><?php echo form_label('Valor', 'valor'); ?></td>
            <td style="vertical-align: middle"><?php echo form_input(array('name' => 'valor', 'style' => 'text-align: right', 'value' => 35000)); ?></td>
            <td style="vertical-align: middle"><?php echo form_submit(array('name' => 'submit', 'id' => 'submit', 'value' => 'Agregar')); ?></td>
        </tr>
    </table>
    <?php
    echo form_close();
    //Botones de licenciamientos
    echo form_open('licenciamiento/licenciamiento_anual');
    echo form_hidden('id_entidad', $id_entidad);
    echo form_hidden('email', $email);
    echo form_hidden('nombre', $nombre);
    ?>
    <table width="100%">
        <tr>
            <td style="vertical-align: middle"><?php echo form_label('Licenciamiento anual&nbsp;&nbsp;&nbsp;&nbsp;', 'tipo_licencia'); ?></td>
            <td style="vertical-align: middle"><?php echo form_input(array('name' => 'tipo_licencia', 'style' => 'text-align: right', 'value' => 360)); ?></td>
            <td style="vertical-align: middle"><?php echo form_label('Valor', 'valor'); ?></td>
            <td style="vertical-align: middle"><?php echo form_input(array('name' => 'valor', 'style' => 'text-align: right', 'value' => 350000)); ?></td>
            <td style="vertical-align: middle"><?php echo form_submit(array('name' => 'submit', 'id' => 'submit', 'value' => 'Agregar')); ?></td>
        </tr>
    </table>
    <?php
    echo form_close();
    //Botones de licenciamientos
    echo form_open('licenciamiento/licenciamiento_adicional');
    echo form_hidden('id_entidad', $id_entidad);
    echo form_hidden('email', $email);
    echo form_hidden('nombre', $nombre);
    ?>
    <table width="100%">
        <tr>
            <td style="vertical-align: middle"><?php echo form_label('Licenciamiento adicional', 'tipo_licencia'); ?></td>
            <td style="vertical-align: middle"><?php echo form_input(array('name' => 'tipo_licencia', 'style' => 'text-align: right', 'value' => set_value('tipo_licencia'))); ?></td>
            <td style="vertical-align: middle"><?php echo form_label('Valor', 'valor'); ?></td>
            <td style="vertical-align: middle"><?php echo form_input(array('name' => 'valor', 'style' => 'text-align: right', 'value' => set_value('valor'))); ?></td>
            <td style="vertical-align: middle"><?php echo form_submit(array('name' => 'submit', 'id' => 'submit', 'value' => 'Agregar')); ?></td>
        </tr>
    </table>
    <?php
    echo form_close();
    echo form_fieldset('', 'class="fieldset"');
    echo form_fieldset_close();
    ?>
</div>
<script type='text/javascript'>
     $(document).ready(function(){
         /************************Scripts para las tablas************************/
        $('#example').dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            'fillSpace': true,
            "bAutoWidth": true

            //Este script establece un orden por cierta columna
            //"aaSorting": [[ 0, "asc" ]]
        });
     })
</script>