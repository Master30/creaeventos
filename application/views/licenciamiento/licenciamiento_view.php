<?php
echo "Cuando adquieres una licencia puedes crear tantos eventos desees por el tiempo que hayas adquirido. Estas son las licencias que tienes hasta el momento: <br/><br/>";


?>
<table cellpadding="0" cellspacing="0" border="" class="display" id="tabla_usuarios" style="font-size: 15px;">
    <thead>
        <th class="primero">Fecha de Inicio</th>
        <th>Fecha de vencimiento</th>
        <th>D&iacute;as restantes</th>
        <th class="ultimo">Valor pagado</th>
    </thead>
    <tbody>
        <?php foreach ($licencias as $licencia){ ?>
        <tr>
            <td>
                <?php echo $this->auditoria_model->formato_fecha($licencia->Fecha_Inicio); ?>
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
        <?php } ?>
    </tbody>
</table><br/><br/>
<table width="100%">
    <thead>
        <th>Licencia por un mes</th>
        <th>Licencia por seis meses</th>
        <th>Licencia por un a&ntilde;o</th>
    </thead>
    <tbody>
        <tr>
            <td>
                <form target="MercadoPago" action="https://www.mercadopago.com/mco/buybutton" method="post">
                    <input type="image" src="https://www.mercadopago.com/org-img/MP3/buy_now_03.gif" alt="">
                    <input type="hidden" name="acc_id" value="14625306">
                    <input type="hidden" name="url_cancel" value="">
                    <input type="hidden" name="item_id" value="1">
                    <input type="hidden" name="name" value="1 Licencia Creaeventos.co">
                    <input type="hidden" name="currency" value="COL">
                    <input type="hidden" name="price" value="35000.00">
                    <input type="hidden" name="url_process" value="">
                    <input type="hidden" name="url_succesfull" value="">
                    <input type="hidden" name="url_post" value="">
                    <input type="hidden" name="shipping_cost" value="">
                    <input type="hidden" name="enc" value="smRk4HydyDX%2BzfeJrGtaQGY158I%3D">
                    <input type="hidden" name="extraPar" value="">
                </form>
            </td>
            <td>
                <form target="MercadoPago" action="https://www.mercadopago.com/mco/buybutton" method="post">
                    <input type="image" src="https://www.mercadopago.com/org-img/MP3/buy_now_03.gif" alt="">
                    <input type="hidden" name="acc_id" value="14625306">
                    <input type="hidden" name="url_cancel" value="">
                    <input type="hidden" name="item_id" value="1">
                    <input type="hidden" name="name" value="1 Licencia Creaeventos.co">
                    <input type="hidden" name="currency" value="COL">
                    <input type="hidden" name="price" value="175000.00">
                    <input type="hidden" name="url_process" value="">
                    <input type="hidden" name="url_succesfull" value="">
                    <input type="hidden" name="url_post" value="">
                    <input type="hidden" name="shipping_cost" value="">
                    <input type="hidden" name="enc" value="smRk4HydyDX%2BzfeJrGtaQGY158I%3D">
                    <input type="hidden" name="extraPar" value="">
                </form>
            </td>
            <td>
                <form target="MercadoPago" action="https://www.mercadopago.com/mco/buybutton" method="post">
                    <input type="image" src="https://www.mercadopago.com/org-img/MP3/buy_now_03.gif" alt="">
                    <input type="hidden" name="acc_id" value="14625306">
                    <input type="hidden" name="url_cancel" value="">
                    <input type="hidden" name="item_id" value="2">
                    <input type="hidden" name="name" value="1 Licencia Creaeventos.co Anual">
                    <input type="hidden" name="currency" value="COL">
                    <input type="hidden" name="price" value="350000.00">
                    <input type="hidden" name="url_process" value="">
                    <input type="hidden" name="url_succesfull" value="">
                    <input type="hidden" name="url_post" value="">
                    <input type="hidden" name="shipping_cost" value="">
                    <input type="hidden" name="enc" value="smRk4HydyDX%2BzfeJrGtaQGY158I%3D">
                    <input type="hidden" name="extraPar" value="">
                </form>
            </td>
        </tr>
    </tbody>
</table>
<script type="text/javascript"> 
    $(document).ready(function() {
        $('#tabla_usuarios').dataTable( {
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": true } );
    } );
</script>