<?php
/**
 * Modelo que se encarga de guardar un registro en la base de datos
 * de las acciones que se realizan en la aplicaci&oacute;n
 * @author 		John Arley Cano Salinas y Oscar Morales
 * @copyright	&copy;  John Arley Cano Salinas y Oscar Morales
 */
Class Auditoria_model extends CI_Model{
    /*
     * Agrega la auditor&iacute;a cuando un usuario se inscribe en la
     * aplicaci&oacute;n
     */
    function nueva_inscripcion($nombres, $apellidos, $id_entidad){
        $auditoria = array(
            'Fk_Id_Entidad' => $id_entidad,
            'descripcion' => $nombres.' '.$apellidos.' se inscribe en la aplicación',
            'Fk_Id_Auditoria_Tipo' => 1
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin nueva_inscripcion
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario solicita restablecer
     * la contraseña
     */
    function restablecer_clave($documento){
        $auditoria = array(
            'descripcion' => 'El usuario con documento número '.$documento.' pide recordar su contraseña',
            'Fk_Id_Auditoria_Tipo' => 2
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin restablecer_clave
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario intenta iniciar sesi&oacute;n
     * 
     */
    function intento_inicio_sesion($Entidad){
        $auditoria = array(
            'descripcion' => 'El usuario '.$Entidad.' intenta iniciar sesión',
            'Fk_Id_Auditoria_Tipo' => 3
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin intento_inicio_sesion()
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario inicia sesi&oacute;n
     * 
     */
    function inicio_sesion(){
        //Accion de auditoria
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'descripcion' => 'Inicia sesión',
            'Fk_Id_Auditoria_Tipo' => 4
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin inicio_sesion()
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario cierra sesi&oacute;n
     * 
     */
    function cierre_sesion(){
        //Accion de auditoria
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'descripcion' => 'Cierra sesión',
            'Fk_Id_Auditoria_Tipo' => 5
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin  cierre_sesion
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario crea un evento nuevo
     * 
     */
    function evento_nuevo($Id_Evento, $campo_nombre){
        //Accion de auditoria
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'descripcion' => 'Crea el evento '.$campo_nombre,
            'Fk_Id_Evento' => $Id_Evento,
            'Fk_Id_Auditoria_Tipo' => 6
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin  evento_nuevo
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario actualiza un evento
     * 
     */
    function evento_actualizado($Id_Evento, $campo_nombre){
        //Accion de auditoria
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'descripcion' => 'Actualiza el evento '.$campo_nombre,
            'Fk_Id_Evento' => $Id_Evento,
            'Fk_Id_Auditoria_Tipo' => 7
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin  evento_actualizado
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario se inscribe en la
     * aplicaci&oacute;n justo en el momento de ingresar a un evento, sin previo
     * registro ni previa invitaci&oacute;n
     */
    function nueva_inscripcion_inmediata($nombres, $id_entidad, $id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $id_entidad,
            'descripcion' => $nombres.' es inscrito en la aplicación en el momento del evento',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 8
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin nueva_inscripcion_inmediata
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario se registra a un
     * evento espec&iacute;fico
     */
    function confirmar_registro($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Se ha registrado al evento',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 9
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin confirmar_registro
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario se registra nuevamente
     * a un evento al cual habia decidido cancelar su registro
     */
    function reingresar($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Se ha registrado nuevamente al evento',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 12
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin reingresar
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario se registra nuevamente
     * a un evento al cual habia decidido cancelar su registro
     */
    function confirmar_asistencia($id_entidad, $id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $id_entidad,
            'Descripcion' => 'Ha asistido al evento',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 13
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin confirmar_asistencia
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario abandona un evento y no tiene
     * derecho a terminarlo
     */
    function desertar($id_entidad, $id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $id_entidad,
            'Descripcion' => 'Ha sido desertado del evento',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 14
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin desertar
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario abandona un evento y no tiene
     * derecho a terminarlo
     */
    function reintegrar($id_entidad, $id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $id_entidad,
            'Descripcion' => 'Ha sido reintegrado al evento',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 15
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin reintegrar
    
    /*
     * Agrega la auditor&iacute;a cuando se finaliza un evento
     */
    function abrir_evento($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Ha abierto el evento',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 17
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin abrir_evento
    
    /*
     * Agrega la auditor&iacute;a cuando se finaliza un evento
     */
    function finalizar_evento($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Ha finalizado el evento',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 16
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin finalizar_evento
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario cancela su asistencia a un
     * evento espec&iacute;fico
     */
    function cancelar_asistencia($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Ha cancelado su registro al evento',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 11
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin cancelar_asistencia
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario env&iacute;a invitaciones
     * a un evento
     */
    function enviar_invitaciones($total, $id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Ha enviado '.$total.' invitaciones al evento',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 10
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin confirmar_asistencia
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario env&iacute;a invitaciones
     * a un evento
     */
    function cupo_agotado_registro($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'No pudo registrarse al evento porque se agotaron los cupos',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 18
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin cupo_agotado_registro
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario env&iacute;a invitaciones
     * a un evento
     */
    function cupo_agotado_reingreso($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'No pudo reingresar al evento porque se agotaron los cupos',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 19
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin cupo_agotado_reingreso
    
    /*
     * Agrega la auditor&iacute;a cuando no se le permite crear m&aacute;s eventos
     * porque ha llegado al l&iacute;mite
     */
    function cupo_agotado_eventos($campo_nombre){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'No pudo crear el evento '.$campo_nombre.' porque llegó al límite permitido',
            'Fk_Id_Auditoria_Tipo' => 20
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin cupo_agotado_eventos
    
    /*
     * Agrega la auditor&iacute;a cuando imprime una escarapela de muestra
     */
    function escarapela_muestra($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Imprimió una escarapela de muestra',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 21
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin escarapela_muestra
    
    /*
     * Agrega la auditor&iacute;a cuando imprime una escarapela de muestra
     */
    function escarapela_masiva($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Realizó impresión masiva de escarapelas',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 22
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin escarapela_masiva
    
    /*
     * Agrega la auditor&iacute;a cuando imprime una escarapela de muestra
     */
    function escarapela_individual($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Realizó impresión individual de escarapelas',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 23
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin escarapela_masiva
    
    /*
     * Agrega la auditor&iacute;a cuando imprime una escarapela de muestra
     */
    function escarapela_individual_media($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Realizó impresión individual de escarapelas en media hoja',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 24
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin escarapela_individual_media
    
    /*
     * Agrega la auditor&iacute;a cuando imprime una escarapela de muestra
     */
    function certificado_muestra($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Imprimió certificado de muestra',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 25
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin certificado_muestra
    
    /*
     * Agrega la auditor&iacute;a cuando imprime una escarapela de muestra
     */
    function certificado_masivo($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Realizó impresión masiva de certificados',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 26
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin certificado_masivo
    
    /*
     * Agrega la auditor&iacute;a cuando imprime una escarapela de muestra
     */
    function certificado_individual($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Realizó impresión individual de certificados',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 27
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin certificado_individual
    
    /*
     * Agrega la auditor&iacute;a cuando imprime una escarapela de muestra
     */
    function certificado_individual_media($id_evento){
        $auditoria = array(
            'Fk_Id_Entidad' => $this->session->userdata('PK_IdEntidad'),
            'Descripcion' => 'Realizó impresión individual de certificados en media hoja',
            'Fk_Id_Evento' => $id_evento,
            'Fk_Id_Auditoria_Tipo' => 28
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin certificado_individual_media
    
    /*
     * Agrega la auditor&iacute;a cuando se agrega una licencia anual
     * 
     */
    function licenciamiento_mensual($id_entidad){
        //Accion de auditoria
        $auditoria = array(
            'Fk_Id_Entidad' => $id_entidad,
            'descripcion' => 'Se activa la licencia mensual',
            'Fk_Id_Auditoria_Tipo' => 29
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin licenciamiento_mensual()
    
    /*
     * Agrega la auditor&iacute;a cuando se agrega una licencia anual
     * 
     */
    function licenciamiento_anual($id_entidad){
        //Accion de auditoria
        $auditoria = array(
            'Fk_Id_Entidad' => $id_entidad,
            'descripcion' => 'Se activa la licencia anual',
            'Fk_Id_Auditoria_Tipo' => 30
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin licenciamiento_anual()
    
    /*
     * Agrega la auditor&iacute;a cuando se agrega una licencia anual
     * 
     */
    function licenciamiento_adicional($id_entidad, $tipo_licencia){
        //Accion de auditoria
        $auditoria = array(
            'Fk_Id_Entidad' => $id_entidad,
            'descripcion' => 'Se activa un licenciamiento adicional por '.$tipo_licencia." días",
            'Fk_Id_Auditoria_Tipo' => 31
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin licenciamiento_adicional()
    
    /*
     * Agrega la auditor&iacute;a cuando un usuario envia solicitud de inrteres en la aplicacion
     * 
     */
    function nuevo_interesado($nombre){
        //Accion de auditoria
        $auditoria = array(
            'descripcion' => $nombre.' ha enviado un email',
            'Fk_Id_Auditoria_Tipo' => 32
        );
        $this->db->insert('auditoria', $auditoria);
    }//Fin licenciamiento_adicional()
    
    /**
    * Lista todas las acciones de auditor&iacute;a del sistema.
    *
    * @access	public
    */
    function listar_auditoria(){
        $sql =
        "SELECT
            auditoria.Id_Auditoria,
            DATE_FORMAT(auditoria.Fecha_Hora,'%d-%m-%Y') AS Fecha,
            DATE_FORMAT(auditoria.Fecha_Hora,'%h:%i:%s %p') AS Hora,
            auditoria.Fk_Id_Entidad,
            IFNULL(entidades.Usuario, '') AS Usuario,
            auditoria.Descripcion,
            auditoria.Fk_Id_Auditoria_Tipo,
            auditoria.Fk_Id_Evento,
            entidades.Nombres,
            entidades.Apellidos
        FROM
            auditoria
        LEFT JOIN entidades ON entidades.PK_IdEntidad = auditoria.Fk_Id_Entidad
        ORDER BY
            auditoria.Id_Auditoria DESC";
        
        //Se retorna la consulta
        return $this->db->query($sql)->result();
    }//Fin listar_auditoria()
    
    /**
    * Formatea las fechas de manera que salgan los meses y d&iacute;s
     * alfab&eacute;ticos
    *
    * @access	public
    */
    function formato_fecha($fecha){
        //Si No hay fecha, devuelva vac&iacute;o en vez de 0000-00-00
        if($fecha == '0000-00-00'){
            return false;
        }
        
        $dia_num = date("j", strtotime($fecha));
        $dia = date("N", strtotime($fecha));
        $mes = date("m", strtotime($fecha));
        $anio_es = date("Y", strtotime($fecha));

        //Nombres de los d&iacute;as
        if($dia == "1"){ $dia_es = "Lunes"; }
        if($dia == "2"){ $dia_es = "Martes"; }
        if($dia == "3"){ $dia_es = "Miercoles"; }
        if($dia == "4"){ $dia_es = "Jueves"; }
        if($dia == "5"){ $dia_es = "Viernes"; }
        if($dia == "6"){ $dia_es = "Sabado"; }
        if($dia == "7"){ $dia_es = "Domingo"; }

        //Nombres de los meses
        if($mes == "1"){ $mes_es = "enero"; }
        if($mes == "2"){ $mes_es = "febrero"; }
        if($mes == "3"){ $mes_es = "marzo"; }
        if($mes == "4"){ $mes_es = "abril"; }
        if($mes == "5"){ $mes_es = "mayo"; }
        if($mes == "6"){ $mes_es = "junio"; }
        if($mes == "7"){ $mes_es = "julio"; }
        if($mes == "8"){ $mes_es = "agosto"; }
        if($mes == "9"){ $mes_es = "septiembre"; }
        if($mes == "10"){ $mes_es = "octubre"; }
        if($mes == "11"){ $mes_es = "noviembre"; }
        if($mes == "12"){ $mes_es = "diciembre"; }
        
        $fecha = /*$dia_es." ".*/$dia_num." de ".$mes_es." de ".$anio_es;
        
        return $fecha;
    }//Fin formato_fecha()
    
    /*
     * Tabla licencia
     * 
     */
    function tabla_licencia(){
        /*
        * Se crea la tabla que se mostrar&aacute; en el momento que se necesite licenciar
        */
       $tabla_licencia = '<table cellpadding="0" cellspacing="0" border="" class="display" id="tabla_usuarios" style="font-size: 15px;">';
       $tabla_licencia .= '<thead>';
       $tabla_licencia .= '<tr>';

       $tabla_licencia .= '<th class="primero"></th>';
       $tabla_licencia .= '<th>Licencia gratuita</th>';
       $tabla_licencia .= '<th>Licencia mensual</th>';
       $tabla_licencia .= '<th>Licencia semestral</th>';
       $tabla_licencia .= '<th class="ultimo">Licencia anual</th>';

       $tabla_licencia .= '</tr>';
       $tabla_licencia .= '</thead>';

       $tabla_licencia .= '<tbody>';
       $tabla_licencia .= '<tr>';                
       $tabla_licencia .= '<td><b>Cantidad de eventos</b></td>';
       $tabla_licencia .= '<td>2 eventos</td>';
       $tabla_licencia .= '<td>Ilimitado</td>';
       $tabla_licencia .= '<td>Ilimitado</td>';
       $tabla_licencia .= '<td>Ilimitado</td>';
       $tabla_licencia .= '</tr>';

       $tabla_licencia .= '<tr>';
       $tabla_licencia .= '<td><b>Valor</b></td>';
       $tabla_licencia .= '<td>0 COP</td>';
       $tabla_licencia .= '<td>35.000 COP</td>';
       $tabla_licencia .= '<td>175.0000 COP</td>';
       $tabla_licencia .= '<td>350.000 COP</td>';
       $tabla_licencia .= '</tr>';

       $tabla_licencia .= '<tr>';
       $tabla_licencia .= '<td></td>';
       $tabla_licencia .= '<td></td>';
       $tabla_licencia .= '<td>';
       $tabla_licencia .= '<form target="MercadoPago" action="https://www.mercadopago.com/mco/buybutton" method="post">';
       $tabla_licencia .= '<input type="image" src="https://www.mercadopago.com/org-img/MP3/buy_now_03.gif" alt="">';
       $tabla_licencia .= '<input type="hidden" name="acc_id" value="14625306">';
       $tabla_licencia .= '<input type="hidden" name="url_cancel" value="">';
       $tabla_licencia .= '<input type="hidden" name="item_id" value="1">';
       $tabla_licencia .= '<input type="hidden" name="name" value="1 Licencia Creaeventos.co">';
       $tabla_licencia .= '<input type="hidden" name="currency" value="COL">';
       $tabla_licencia .= '<input type="hidden" name="price" value="35000.00">';
       $tabla_licencia .= '<input type="hidden" name="url_process" value="">';
       $tabla_licencia .= '<input type="hidden" name="url_succesfull" value="">';
       $tabla_licencia .= '<input type="hidden" name="url_post" value="">';
       $tabla_licencia .= '<input type="hidden" name="shipping_cost" value="">';
       $tabla_licencia .= '<input type="hidden" name="enc" value="smRk4HydyDX%2BzfeJrGtaQGY158I%3D">';
       $tabla_licencia .= '<input type="hidden" name="extraPar" value="">';
       $tabla_licencia .= '</form>';
       $tabla_licencia .= '</td>';
       $tabla_licencia .= '<td>';
       $tabla_licencia .= '<form target="MercadoPago" action="https://www.mercadopago.com/mco/buybutton" method="post">';
       $tabla_licencia .= '<input type="image" src="https://www.mercadopago.com/org-img/MP3/buy_now_03.gif" alt="">';
       $tabla_licencia .= '<input type="hidden" name="acc_id" value="14625306">';
       $tabla_licencia .= '<input type="hidden" name="url_cancel" value="">';
       $tabla_licencia .= '<input type="hidden" name="item_id" value="2">';
       $tabla_licencia .= '<input type="hidden" name="name" value="1 Licencia Creaeventos.co Semestral">';
       $tabla_licencia .= '<input type="hidden" name="currency" value="COL">';
       $tabla_licencia .= '<input type="hidden" name="price" value="175000.00">';
       $tabla_licencia .= '<input type="hidden" name="url_process" value="">';
       $tabla_licencia .= '<input type="hidden" name="url_succesfull" value="">';
       $tabla_licencia .= '<input type="hidden" name="url_post" value="">';
       $tabla_licencia .= '<input type="hidden" name="shipping_cost" value="">';
       $tabla_licencia .= '<input type="hidden" name="enc" value="smRk4HydyDX%2BzfeJrGtaQGY158I%3D">';
       $tabla_licencia .= '<input type="hidden" name="extraPar" value="">';
       $tabla_licencia .= '</form>';
       $tabla_licencia .= '</td>';
       $tabla_licencia .= '<td>';
       $tabla_licencia .= '<form target="MercadoPago" action="https://www.mercadopago.com/mco/buybutton" method="post">';
       $tabla_licencia .= '<input type="image" src="https://www.mercadopago.com/org-img/MP3/buy_now_03.gif" alt="">';
       $tabla_licencia .= '<input type="hidden" name="acc_id" value="14625306">';
       $tabla_licencia .= '<input type="hidden" name="url_cancel" value="">';
       $tabla_licencia .= '<input type="hidden" name="item_id" value="2">';
       $tabla_licencia .= '<input type="hidden" name="name" value="1 Licencia Creaeventos.co Anual">';
       $tabla_licencia .= '<input type="hidden" name="currency" value="COL">';
       $tabla_licencia .= '<input type="hidden" name="price" value="350000.00">';
       $tabla_licencia .= '<input type="hidden" name="url_process" value="">';
       $tabla_licencia .= '<input type="hidden" name="url_succesfull" value="">';
       $tabla_licencia .= '<input type="hidden" name="url_post" value="">';
       $tabla_licencia .= '<input type="hidden" name="shipping_cost" value="">';
       $tabla_licencia .= '<input type="hidden" name="enc" value="smRk4HydyDX%2BzfeJrGtaQGY158I%3D">';
       $tabla_licencia .= '<input type="hidden" name="extraPar" value="">';
       $tabla_licencia .= '</form>';
       $tabla_licencia .= '</td>';
       $tabla_licencia .= '</tr>';
       $tabla_licencia .= '</tbody>';

       $tabla_licencia .= '</table>';
       
       return $tabla_licencia;
    }//Fin tabla_licencia
    
}//Fin auditoria_model()