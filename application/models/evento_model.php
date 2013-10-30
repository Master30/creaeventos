<?php
/**
 * Modelo que se encarga de gestionar la informaci&oacute;n
 * de los eventos.
 * @author 		John Arley Cano Salinas y oscar Humberto Morales
 * @copyright           John Arley Cano Salinas y oscar Humberto Morales
 */

Class Evento_model extends CI_Model{
    /*
     * Muestra los estados de eventos del usuario como
     * asistente a eventos
     * 
     */
    function topicos_estados($estado){
        switch ($estado){
            case NULL:
                $estado = 'IS NULL';
                break;
        }//Fin switch
        
        $sql =
        "SELECT
            count(invitaciones.PK_IdInvitacion) AS Estado
        FROM
            invitaciones
        LEFT JOIN participanteevento ON participanteevento.Fk_IdInvitacion = invitaciones.PK_IdInvitacion
        WHERE
            invitaciones.Correo = '".$this->session->userdata('mail_usuario')."' AND
            participanteevento.Estado ".$estado;
        
        $estados = $this->db->query($sql)->result();
        
        foreach ($estados as $estado){
            return  $estado->Estado;
        }//Fin foreach()
        
        //Retorna la consulta
        return $this->db->query($sql)->result(); 
    }//Fin topicos_estados
    
    /*
     * Muestra los estados de eventos del usuario como
     * asistente a eventos
     * 
     */
    function topicos_eventos(){
        $sql =
        "SELECT
            count(eventos.PK_IdEvento) AS Eventos
        FROM
            eventos
        WHERE
            eventos.PK_IdEntidad = ".$this->session->userdata('PK_IdEntidad');
        
        $eventos = $this->db->query($sql)->result();
        
        foreach ($eventos as $evento){
            return  $evento->Eventos;
        }//Fin foreach()
        
        //Retorna la consulta
        return $this->db->query($sql)->result(); 
    }//Fin topicos_eventos
    
    /*
     * Muestra los estados de eventos del usuario como
     * asistente a eventos
     * 
     */
    function topicos_invitaciones(){
        $sql =
        "SELECT
            count(eventos.PK_IdEvento) AS Invitaciones
        FROM
            eventos
        INNER JOIN invitaciones ON invitaciones.FK_IdEvento = eventos.PK_IdEvento
        WHERE
            eventos.PK_IdEntidad = ".$this->session->userdata('PK_IdEntidad');
        
        $invitaciones = $this->db->query($sql)->result();
        
        foreach ($invitaciones as $invitacion){
            return  $invitacion->Invitaciones;
        }//Fin foreach()
        
        //Retorna la consulta
        return $this->db->query($sql)->result(); 
    }//Fin topicos_eventos
    
    /*
     * Guarda en la base de datos los datos del evento.
     * @access	public
     * @return
     * 
     */
    function insertar_Evento(
        $campo_nombre, $campo_descripcion, $campo_fechainicio,
        $campo_fechafin, $campo_ubicacion, $campo_direccion,
        $campo_ciudad, $campo_maximoasistentes, $campo_IdEntidad,
        $campo_Impresionusuario, $campo_ImpresionCertificadoH, 
        $campo_ImpresionCertificadoM){
        
        $campo_fechainicio = $this->fecha_mysql($campo_fechainicio);
        $campo_fechafin = $this->fecha_mysql($campo_fechafin);
        
        $data = array(
            "NombreEvento" => $campo_nombre,
            "DescripcionEvento" => $campo_descripcion,
            "FechaInicio" => $campo_fechainicio,
            "FechaFin" => $campo_fechafin,
            "DescripcionUbicacion" => $campo_ubicacion,
            "Direccion_Completa" => $campo_direccion,
            "Ciudad" => $campo_ciudad,
            "MaximoParticipantes" => $campo_maximoasistentes,         
            "PK_IdEntidad" => $campo_IdEntidad,
            "PermitirImpresionUsuario" => $campo_Impresionusuario,
            "ImpresionCertificadoHorizontal" => $campo_ImpresionCertificadoH,
            "ImpresionCertificadoMedia" => $campo_ImpresionCertificadoM
        );
        
        return $this->db->insert("eventos", $data);
    }//fin insertar_Evento()
    
    /**
    * Actualiza en la base de datos los datos del evento seleccionado para
    * la modificaci&oacute;n.
    *
    * @access	public
    * @return	
    */
    function actualizar_Evento( 
        $Id_Evento, $campo_nombre, $campo_descripcion, $campo_fechainicio,
        $campo_fechafin, $campo_ubicacion, $campo_direccion, $campo_ciudad,
        $campo_maximoasistentes, $campo_IdEntidad, $campo_Impresionusuario,
        $campo_ImpresionCertificadoH, $campo_ImpresionCertificadoM){
        
        $campo_fechainicio = $this->fecha_mysql($campo_fechainicio);
        $campo_fechafin = $this->fecha_mysql($campo_fechafin);
        
        $data = array(
            "NombreEvento" => $campo_nombre,
            "DescripcionEvento" => $campo_descripcion,
            "FechaInicio" => $campo_fechainicio,
            "FechaFin" => $campo_fechafin,
            "DescripcionUbicacion" => $campo_ubicacion,
            "Direccion_Completa" => $campo_direccion,
            "Ciudad" => $campo_ciudad,
            "MaximoParticipantes" => $campo_maximoasistentes,           
            "PK_IdEntidad" => $campo_IdEntidad,         
            "PermitirImpresionUsuario" => $campo_Impresionusuario,          
            "ImpresionCertificadoHorizontal" => $campo_ImpresionCertificadoH,
            "ImpresionCertificadoMedia" => $campo_ImpresionCertificadoM
        );
        
        $this->db->where('PK_IdEvento', $Id_Evento);
        return $this->db->update("eventos", $data);
    }//finactualizar_Evento()  
    
   /**
    * Consulta un evento o todos, seg&uacute;n el filtro los eventos.
    *
    * @access	public
    * @return	
    */
    function listarEventos($filtro=NULL){
        $IdEntidad = $this->session->userdata('PK_IdEntidad');
        if($filtro!=NULL){
            $filtro = 'AND eventos.PK_IdEvento = '.$filtro;
        }else{
            $filtro = '';
        }
        
        $sql =
        "SELECT
            eventos.PK_IdEvento,
            eventos.NombreEvento,
            eventos.DescripcionEvento,
            DATE_FORMAT(eventos.FechaInicio,'%d-%m-%Y') AS FechaInicio,
            DATE_FORMAT(eventos.FechaFin,'%d-%m-%Y') AS FechaFin,
            DATE_FORMAT(eventos.Fechainicio,'%h:%i %p') AS HoraInicio,
            DATE_FORMAT(eventos.Fechafin,'%h:%i %p') AS HoraFin,
            eventos.DescripcionUbicacion,
            eventos.Direccion_Completa,
            eventos.Escarapela_Evento,
            eventos.Certificado_Evento,
            eventos.Banner_Evento,
            eventos.MaximoParticipantes,
            eventos.Ciudad,
            eventos.Estado,
            eventos.Numero_Registrados,
            (SELECT DISTINCT
                    count(participanteevento.FK_IdEntidad) AS Confirmados
            FROM
                    participanteevento
            WHERE 
                    (participanteevento.Estado = 2
                    OR  participanteevento.Estado = 5)
                    AND participanteevento.FK_IdEvento = eventos.PK_IdEvento
            ) Confirmados,
            eventos.ImpresionCertificadoHorizontal,
            eventos.ImpresionCertificadoMedia
        FROM
            eventos
        WHERE   eventos.PK_IdEntidad = $IdEntidad
        $filtro"; 

        //Retorna la consulta
        $registro = $this->db->query($sql); 
        return $registro->result();
    }//finlistarEventos()
    
    /**
    * Carga un evento para su modificaci&oacute;n.
    *
    * @access	public
    * @return	
    */
    function cargarEventos($IdEvento){
        $IdEntidad = $this->session->userdata('PK_IdEntidad'); 
        $sql =
            "SELECT
                eventos.PK_IdEvento,
                eventos.NombreEvento,
                eventos.DescripcionEvento,
                DATE_FORMAT(eventos.FechaInicio,'%d-%m-%Y %h:%i %p') AS FechaInicio,
                DATE_FORMAT(eventos.FechaFin,'%d-%m-%Y %h:%i %p') AS FechaFin,
                eventos.DescripcionUbicacion,
                eventos.Direccion_Completa,
                eventos.Ciudad,
                eventos.Escarapela_Evento,
                eventos.Certificado_Evento,
                eventos.MaximoParticipantes,
                eventos.PK_IdEntidad,
                eventos.Banner_Evento,
                eventos.PermitirImpresionUsuario,
                eventos.ImpresionCertificadoHorizontal,
                eventos.ImpresionCertificadoMedia
            FROM
                eventos
            WHERE   eventos.PK_IdEntidad = $IdEntidad
                AND eventos.PK_IdEvento = $IdEvento"; 

        //retorno consulta
        $registro = $this->db->query($sql);         
        
        return $registro->result(); 
    }//fin cargarEventos($IdEvento)
    
    /**
    * lista los eventos a los cuales un usuario ha sido invitado o asistir&aacute;.
    *
    * @access	public
    * @return	
    */
    function listarEventosaAsistir(){
        $IdEntidad = $this->session->userdata('PK_IdEntidad');
        $sql = 
        "SELECT
            participanteevento.Estado,
            eventos.NombreEvento,
            eventos.PK_IdEvento,
            DATE_FORMAT(eventos.FechaInicio, '%d-%m-%Y') AS FechaInicio,
            DATE_FORMAT(eventos.FechaFin, '%d-%m-%Y') AS FechaFin,
            invitaciones.PK_IdInvitacion,
            eventos.Estado AS Estado_Evento,
            eventos.PermitirImpresionUsuario
        FROM
            invitaciones
            LEFT JOIN participanteevento ON participanteevento.Fk_IdInvitacion = invitaciones.PK_IdInvitacion
            LEFT JOIN entidades ON participanteevento.FK_IdEntidad = entidades.PK_IdEntidad
            RIGHT JOIN eventos ON invitaciones.FK_IdEvento = eventos.PK_IdEvento
        WHERE
            invitaciones.Correo = '".$this->session->userdata('mail_usuario')."'";

        //retorno consulta
        $registro = $this->db->query($sql);         
        return $registro->result();        
    }//Fin listarEventosaAsistir()
    
    /**
    * Lista las invitaciones a un evento.
    *
    * @access	public
    * @return	
    */
    function cargarEventoInvitacion($IdEvento){
        $IdEntidad = $this->session->userdata('PK_IdEntidad'); 
        
        $sql =
        "SELECT
            eventos.PK_IdEvento,
            entidades.Email,
            entidades.PK_IdEntidad
        FROM
            eventos
            LEFT JOIN invitaciones ON eventos.PK_IdEvento = invitaciones.FK_IdEvento
            LEFT JOIN entidades ON entidades.Email = invitaciones.Correo
        WHERE       eventos.PK_IdEntidad = $IdEntidad
            AND eventos.PK_IdEvento = $IdEvento "; 
        
        //Se Retorna la consulta
        $registro = $this->db->query($sql);
        
        $paramss = $registro->result();
        return $paramss;
    }//Fin cargarEventoInvitacion($IdEvento)
    
    /**
    * Actualiza los asistentes a un evento.
    *
    * @access	public
    * @return	
    */
    function actualizarAsistente_Evento($Id_Evento){
        $campo_IdEntidad = $this->session->userdata('PK_IdEntidad');
        $data = array(
           "PK_IdParticipanteEvento" => "",           
           "FK_IdEvento" => $Id_Evento,           
           "FK_IdEntidad" => $campo_IdEntidad       
        );
        
        $this->db->where('FK_IdEvento', $Id_Evento);
        return $this->db->update("participanteevento", $data);
    }//actualizarAsistente_Evento($Id_Evento)
    
    /**
    * Lista invitaciones.
    *
    * @access	public
    * @return	
    */
    function listarEventosInvitaciones(){
        $IdEntidad = $this->session->userdata('PK_IdEntidad'); 
        $sql =
        "SELECT
            eventos.PK_IdEvento,
            eventos.NombreEvento,
            eventos.DescripcionEvento,
            DATE_FORMAT(eventos.FechaInicio,'%Y-%m-%d') AS FechaInicio,
            DATE_FORMAT(eventos.FechaFin,'%Y-%m-%d') AS FechaFin
        FROM
            eventos
            LEFT JOIN invitaciones ON eventos.PK_IdEvento = invitaciones.FK_IdEvento
            LEFT JOIN entidades ON entidades.Email = invitaciones.Correo
            LEFT JOIN participanteevento ON invitaciones.FK_IdEvento = participanteevento.FK_IdEvento
        WHERE       eventos.PK_IdEntidad  = $IdEntidad 
        ORDER BY    FechaInicio DESC "; 

        //retorno consulta
        $registro = $this->db->query($sql);
        
        $params = $registro->result();        
        return $params;
    }//Fin listarEventosInvitaciones()
    
    /**
    * Guarda en la base de datos La invitaci&oacute;n al evento,
    * sean una o varias.
    *
    * @access	public
    * @return	
    */
    function insertar_invitacion($email){
        //Se insertan los datos principales del contrato
        $this->db->insert('invitaciones', $email);
    }//Fin insertar_invitacion()
    
    /**
    * Guarda en la base de datos La confirmaci&oacute;n de asistencia
    * al evento,
    *
    * @access	public
    * @return	
    */
    function confirmar_asistencia($confirmacion){
        //Se insertan los datos de la confirmaci&oacute;n
        $this->db->insert('participanteevento', $confirmacion);
    }//Fin confirmar_asistencia
    
    /**
    * Actualiza en la base de datos La confirmaci&oacute;n de asistencia
    * al evento,
    *
    * @access	public
    * @return	
    */
    function actualizar_invitacion($id_invitacion){
        $invitacion = array(       
           "Estado" => 2  
        );
        
        $this->db->where('Fk_IdInvitacion', $id_invitacion);
        return $this->db->update("participanteevento", $invitacion);
    }//Fin actualizar_invitacion()
    
    /**
    * Actualiza en la base de datos La deserci&oacute;n de asistencia
    * al evento por parte del usuario,
    *
    * @access	public
    * @return	
    */
    function desertar($id_invitacion){
        $invitacion = array(       
           "Estado" => 3  
        );
        
        $this->db->where('Fk_IdInvitacion', $id_invitacion);
        return $this->db->update("participanteevento", $invitacion);
    }//Fin desertar()
    
    /**
    * Actualiza en la base de datos el reingreso del asistente al evento
    *
    * @access	public
    * @return	
    */
    function reintegrar($id_invitacion){
        $invitacion = array(       
           "Estado" => 2  
        );
        
        $this->db->where('Fk_IdInvitacion', $id_invitacion);
        return $this->db->update("participanteevento", $invitacion);
    }//Fin desertar()
    
    /**
    * Cancela el registro a un evento, cambiando el estado en la base de datos.
    *
    * @access	public
    * @return	
    */
    function cancelar_registro($id_invitacion){
        $invitacion = array(       
           "Estado" => 4  
        );
        
        $this->db->where('Fk_IdInvitacion', $id_invitacion);
        return $this->db->update("participanteevento", $invitacion);
    }//Fin cancelar_registro()
    
    /**
    * Guarda en base de datos la imagen que se ha asignado para el certificado
    * correspondiente al evento
    *
    * @access	public
    * @return	
    */
    function guardarCertificado($Id_Evento, $name){        
        $data = array(       
           "Certificado_Evento" => $name   
        );
        
        $this->db->where('PK_IdEvento', $Id_Evento);
        return $this->db->update("eventos", $data);
    }//Fin guardarCertificado($Id_Evento, $name)
    
    /**
    * Guarda en base de datos la imagen que se ha asignado para la escarapela
    * correspondiente al evento
    *
    * @access	public
    * @return	
    */
    function guardarEscarapela($Id_Evento, $name){        
        $data = array(       
           "Escarapela_Evento" => $name   
        );
        $this->db->where('PK_IdEvento', $Id_Evento);
        return $this->db->update("eventos", $data);
    }//Fin guardarEscarapela($Id_Evento, $name)
    
    /**
    * Guarda en base de datos la imagen que se ha asignado para el banner
    * correspondiente al evento
    *
    * @access	public
    * @return	
    */
    function guardarBanner($Id_Evento, $name){        
        $data = array(       
           "Banner_Evento" => $name   
        );
        $this->db->where('PK_IdEvento', $Id_Evento);
        return $this->db->update("eventos", $data);
    }//Fin guardarBanner($Id_Evento, $name)
    
    /**
    * Lista en la base de datos los asistentes a un evento
    *
    * @access	public
    * @return	
    */
    function listar_asistentes($id_evento){
        $sql = 
            'SELECT DISTINCT
                entidades.PK_IdEntidad,
                invitaciones.PK_IdInvitacion,
                invitaciones.Correo,
                invitaciones.FK_IdEvento,
                eventos.NombreEvento,
                entidades.Nombres,
                entidades.Apellidos,
                participanteevento.Estado,
                eventos.Estado AS Estado_Evento
            FROM
                invitaciones
                INNER JOIN eventos ON invitaciones.FK_IdEvento = eventos.PK_IdEvento
                LEFT JOIN participanteevento ON participanteevento.Fk_IdInvitacion = invitaciones.PK_IdInvitacion
                LEFT JOIN entidades ON invitaciones.Correo = entidades.Email
            WHERE
                invitaciones.FK_IdEvento = '.$id_evento;

            //Se retorna la consulta
            return $this->db->query($sql)->result();
    }//Fin listar_asistentes
                
    /**
    * Retorna el id del evento que se acaba de crear.
    *
    * @access	
    * @param	
    * @param	
    * @return	
    */
    function obtener_id_evento(){
        return @mysql_insert_id($this->PK_IdEvento);
    }//Fin obtener_id_evento()
    
    /**
    * Valida en la base de datos los datos del usuario que est&aacute; tratando
    * de loguearse.
    *
    * @access	public
    * @return	
    */
    function validar_inscripcion($id_evento, $email){
        $sql = 
            "SELECT
                invitaciones.PK_IdInvitacion,
                invitaciones.Correo,
                eventos.PK_IdEvento,
                entidades.CedulaEntidad,
                entidades.Nombres,
                entidades.Apellidos,
                entidades.Empresa,
                entidades.Telefono,
                entidades.Email,
                entidades.Usuario
            FROM
                invitaciones
                INNER JOIN eventos ON eventos.PK_IdEvento = invitaciones.FK_IdEvento
                LEFT JOIN entidades ON invitaciones.Correo = entidades.Email
            WHERE
                eventos.PK_IdEvento = ".$id_evento." AND
                entidades.Email = '".$email."'";

        //Se retorna la consulta
        return $this->db->query($sql)->result();
    }//Fin validar_inscripcion()
    
    /**
    * Funci&oacute;n de fecha.
    *
    * @access	public
    * @return	
    */
    function fecha_mysql($fecha){
        list($dia,$mes,$anio)=explode("-",$fecha);
        $horas = substr($anio,4,3);        
        $minutos = trim(substr($anio,8,2));
        $PM = trim(substr($anio,10,3));
        if($PM=="PM"){
            $horas = $horas+12;
        }
        
        $hora = $horas.":".$minutos;        
        $anio = substr($anio,0,4);
        
        return $anio."-".$mes."-".$dia." ".$hora;
    }
    
    /************************************************************************
     * M&eacute;todos encargados de cerrar el evento y cambiar los estados
     * correspondientes
     ***********************************************************************/
    
    /*
     * Cierra el evento para las personas que Est&aacute;n registradas al evento.
     */
    function cerrar_evento_registrados($registrados, $where_registrados){
        $this->db->update('participanteevento', $registrados, $where_registrados);
    }//Fin cerrar_evento_registrados()
    
    /*
     * Cierra el evento para las personas que asistieron al evento.
     */
    function cerrar_evento_asistidos($aprobados, $where){
        $this->db->update('participanteevento', $aprobados, $where);
    }//Fin cerrar_evento_asistidos()
    
    /*
     * Cambia el estado al evento para que quede inactivo
     */
    function cerrar_evento($id_evento){
        $this->db->where('PK_IdEvento', $id_evento);
        $this->db->update('eventos', array('Estado' => 0));
    }//Fin cerrar_evento()
    
    /************************************************************************
     * M&eacute;todos encargados de abrir el evento y cambiar los estados
     * correspondientes
     ***********************************************************************/
    
    /*
     * Abre el evento para las personas que fueron invitadas al evento
     * y no han aceptado la invitaci&oacute;n.
     */
    function abrir_evento_invitados(){
        
    }//Fin abrir_evento_invitados()
    
    /*
     * Abre el evento para las personas que Est&aacute;n registradas al evento.
     */
    function abrir_evento_registrados(){
        
    }//Fin abrir_evento_registrados()
    
    /*
     * Abre el evento para las personas que asistieron al evento.
     */
    function abrir_evento_asistidos($aprobados, $where){
        $this->db->update('participanteevento', $aprobados, $where);
    }//Fin abrir_evento_asistidos()
    
    /*
     * Cambia el estado al evento para que quede activo
     */
    function abrir_evento($id_evento){
        $this->db->where('PK_IdEvento', $id_evento);
        $this->db->update('eventos', array('Estado' => 1));
    }//Fin abrir_evento()
    
    
    /**
    * Vuelve a generar el registro a un evento,
     * cambiando el estado en la base de datos.
    *
    * @access	public
    * @return	
    */
    function reingresar($id_invitacion){
        $invitacion = array(       
           "Estado" => 1  
        );
        
        $this->db->where('Fk_IdInvitacion', $id_invitacion);
        return $this->db->update("participanteevento", $invitacion);
    }//Fin reingresar()
    
    /*
     * Verifica que al momento de registrarse o reingresar a un evento
     * aun haya cupos para poderlo hacer
     */
    function validar_cupo($id_evento){
        $sql = 
        'SELECT
            (eventos.MaximoParticipantes - eventos.Numero_Registrados) AS Cupo
        FROM
            eventos
        WHERE
            eventos.PK_IdEvento = '.$id_evento;
        
        $cupos = $this->db->query($sql)->result();
        
        foreach ($cupos as $cupo){
            return  $cupo->Cupo;
        }//Fin foreach()
    }//Fin validar_cupo
    
    /*
     * Muestra la cantidad de usuarios por cada estado de evento
     */
    function estados_evento($id_evento, $estado){
        if($estado == NULL){$estado = 'IS NULL';}else{$estado = '= '.$estado;}
        $sql =
        "SELECT DISTINCT
            COUNT(eventos.NombreEvento) AS Numero
        FROM
            invitaciones
            INNER JOIN eventos ON invitaciones.FK_IdEvento = eventos.PK_IdEvento
            LEFT JOIN participanteevento ON participanteevento.Fk_IdInvitacion = invitaciones.PK_IdInvitacion
            LEFT JOIN entidades ON invitaciones.Correo = entidades.Email
        WHERE
            invitaciones.FK_IdEvento = ".$id_evento." AND
            participanteevento.Estado ".$estado."
        GROUP BY
            participanteevento.Estado";
        
        $estados = $this->db->query($sql)->result();
        if($estados == NULL){
            return 0;
        }else{
            foreach ($estados as $estado){
            return $estado->Numero;
        }
        
       
        }//Fin foreach()
    }//Fin estados_evento()
    
    /*
     * Verifica que al momento de registrarse o reingresar a un evento
     * aun haya cupos para poderlo hacer
     */
    function validar_creacion_evento($id_entidad){
        $sql = 
        'SELECT
            (entidades.EventosPermitidos - entidades.EventosRegistrados) AS Cupo
        FROM
            entidades
        WHERE
            entidades.PK_IdEntidad ='.$id_entidad;
        
        $cupos = $this->db->query($sql)->result();
        
        foreach ($cupos as $cupo){
            return  $cupo->Cupo;
        }//Fin foreach()
    }//Fin validar_creacion_evento
    
    /*
     * Agrega un n&uacute;mero mas a los asistentes
     * 
     */
    function agregar_participante($id_evento){
        //Se trae la cantidad de asistentes que van hasta el momento y se 
        //agrega uno mas
        $sql = 
        'SELECT
            eventos.Numero_Registrados+1 AS Registro
        FROM
            eventos
        WHERE
            eventos.PK_IdEvento = '.$id_evento;
        
        $registros = $this->db->query($sql)->result();

        foreach ($registros as $registro){
            $asistentes = $registro->Registro;
        }//Fin foreach()

        //Se actualizan el dato
        $this->db->where('PK_IdEvento', $id_evento);
        $this->db->update('eventos', array('Numero_Registrados' => $asistentes));
    }//Fin agregar_participante
    
    /*
     * Agrega un n&uacute;mero mas a los asistentes
     * 
     */
    function eliminar_participante($id_evento){
        //Se trae la cantidad de asistentes que van hasta el momento y se 
        //elimina uno
        $sql = 
        'SELECT
            eventos.Numero_Registrados-1 AS Registro
        FROM
            eventos
        WHERE
            eventos.PK_IdEvento = '.$id_evento;
        
        $registros = $this->db->query($sql)->result();

        foreach ($registros as $registro){
            $asistentes = $registro->Registro;
        }//Fin foreach()

        //Se actualizan el dato
        $this->db->where('PK_IdEvento', $id_evento);
        $this->db->update('eventos', array('Numero_Registrados' => $asistentes));
    }//Fin eliminar_participante
    
    
    /*
     * Agrega un n&uacute;mero mas a los asistentes
     * 
     */
    function agregar_evento_permitido(){
        //Se trae la cantidad de asistentes que van hasta el momento y se 
        //agrega uno mas
        $sql = 
        'SELECT
            entidades.EventosRegistrados + 1 AS Registro
        FROM
            entidades
        WHERE
            entidades.PK_IdEntidad = '.$this->session->userdata('PK_IdEntidad');
        
        $registros = $this->db->query($sql)->result();

        foreach ($registros as $registro){
            $asistentes = $registro->Registro;
        }//Fin foreach()

        //Se actualizan el dato
        $this->db->where('PK_IdEntidad', $this->session->userdata('PK_IdEntidad'));
        $this->db->update('entidades', array('EventosRegistrados' => $asistentes));
    }//Fin agregar_participante
    
     /**
    * Consulta un evento o todos, seg&uacute;n el filtro los eventos.
    *
    * @access	public
    * @return	
    */
    function listarEventosUsuario($filtro=NULL){
        $IdEntidad = $this->session->userdata('PK_IdEntidad');
        if($filtro!=NULL){
            $filtro = 'AND eventos.PK_IdEvento = '.$filtro;
        }else{
            $filtro = '';
        }
        
        $sql =
        "SELECT
            eventos.PK_IdEvento,
            eventos.NombreEvento,
            eventos.DescripcionEvento,
            DATE_FORMAT(eventos.FechaInicio,'%d-%m-%Y') AS FechaInicio,
            DATE_FORMAT(eventos.FechaFin,'%d-%m-%Y') AS FechaFin,
            DATE_FORMAT(eventos.Fechainicio,'%h:%i %p') AS HoraInicio,
            DATE_FORMAT(eventos.Fechafin,'%h:%i %p') AS HoraFin,
            eventos.DescripcionUbicacion,
            eventos.Direccion_Completa,
            eventos.Escarapela_Evento,
            eventos.Certificado_Evento,
            eventos.Banner_Evento,
            eventos.MaximoParticipantes,
            eventos.Ciudad,
            eventos.Estado,
            eventos.ImpresionCertificadoHorizontal,
            eventos.ImpresionCertificadoMedia
        FROM
            eventos
        WHERE   eventos.PK_IdEvento >0
        $filtro"; 

        //Retorna la consulta
        $registro = $this->db->query($sql); 
        return $registro->result();
    }//fin listarEventosUsuario()
}//Fin Evento_model
