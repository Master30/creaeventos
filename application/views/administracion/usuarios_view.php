<?php $icono_detalles = array('src' => 'img/iconos/detalles.png', 'title' => 'Detalles del usuario', 'align' => 'left', 'width' => '30', 'height' => '30'); ?>
<div id="form" class="container_11">
    <table cellpadding="0" cellspacing="0" border="" class="display" id="example" style="font-size: 13px;">
        <thead>
            <tr>
                <th class="primero">Nombres</th>
                <th>Documento</th>
                <th>Eventos creados</th>
                <th class="ultimo">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($usuarios as $usuario):
            ?>
            <tr>
                <td><?php echo $usuario->Nombres; ?></td>
                <td><?php echo $usuario->Documento; ?></td>
                <td><?php echo $usuario->Creados; ?></td>
                <td>
                    <?php
                    echo form_open('administracion/detalle_usuario');
                    echo form_hidden('id_entidad', $usuario->PK_IdEntidad);
                    echo form_submit(array('type' => 'image', 'src' => base_url().'img/iconos/detalles.png', 'width' => '30', 'height' => '30'));
                    echo form_close();
                    
                    //echo anchor(site_url('administracion/detalle_usuario').'/'.$usuario->PK_IdEntidad,  img($icono_detalles));
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
            "bAutoWidth": true,

            //Este script establece un orden por cierta columna
            "aaSorting": [[ 0, "asc" ]]
        });
     })
</script>
