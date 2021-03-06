<div id="form" class="container_11">
    <table cellpadding="0" cellspacing="0" border="" class="display" id="example" style="font-size: 13px;">
        <thead>
            <tr>
                <th class="primero" width="5%">Nro.</th>
                <th width="20%">Fecha</th>
                <th width="12%">Hora</th>
                <th width="20%">Nombre</th>
                <th width="7%">Usuario</th>
                <th width="3%">Evento</th>
                <th class="ultimo"  width="33%" style="text-align: center">Acci&oacute;n</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $numero = 1;
            foreach($auditorias as $auditoria):
            ?>
            <tr>
                <td style="text-align: right;"><?php echo $numero; ?></td>
                <td><?php echo $this->auditoria_model->formato_fecha($auditoria->Fecha); ?></td>
                <td><?php echo $auditoria->Hora; ?></td>
                <td><?php echo $auditoria->Nombres.' '.$auditoria->Apellidos; ?></td>
                <td><?php echo $auditoria->Usuario; ?></td>
                <td style="text-align: right;"><?php echo $auditoria->Fk_Id_Evento; ?></td>
                <td><?php echo $auditoria->Descripcion; ?></td>
            </tr>
            <?php
            $numero++;
            endforeach;
            ?>
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