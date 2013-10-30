<table cellpadding="0" cellspacing="0" style="font-size: 0.8em" border="" class="display" id="licencia" style="font-size: 15px;">
    <thead>
        <th class="primero">Usuario</th>
        <th>Licencia</th>
        <th>Vencimiento</th>
        <th>D&iacute;as Restantes</th>
        <th>Valor</th>
        <th class="ultimo">Opciones</th>
    </thead>
    <tbody>
        <?php foreach($licencias as $licencia): ?>
        <tr>
            <td><?php echo $licencia->Nombres." ".$licencia->Apellidos; ?></td>
            <td style="text-align: right"><?php echo $licencia->Tipo_Licencia; ?></td>
            <?php
            if($licencia->Tipo_Licencia == NULL){
                ?><td>Gratis</td><?php
            }else{
                ?><td><?php $licencia->Fecha_Vencimiento; ?></td><?php
            }
            ?>
            <td style="text-align: right"><?php echo $licencia->Dias_Restantes; ?></td>
            <td style="text-align: right"><?php echo "$ ".number_format($licencia->Valor, 0, '', '.'); ?></td>
            <td>
                <?php
                echo form_open('administracion/detalle_usuario');
                echo form_hidden('id_entidad', $licencia->Pk_IdEntidad);
                echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/detalles.png', 'width' => '25', 'height' => '25'));
                echo form_close();
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script type="text/javascript"> 
    $(document).ready(function() {
        $('#licencia').dataTable( {
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": true } );
    } );
</script>
