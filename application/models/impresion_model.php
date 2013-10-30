<?php
/**
 * Modelo que se encarga de gestionar la informaci&oacute;n
 * de los eventos para las impresiones.
 * @author 		John Arley Cano Salinas y oscar Humberto Morales
 * @copyright           John Arley Cano Salinas y oscar Humberto Morales
 */
Class Impresion_model extends CI_Model{            
    /**
     * Lista Los Usuarios registrados a un evento para la impresion de certificados 
     * y escarapelas por parte del creador del evento 
     * 
     * @param type $id_evento
     * @param type $Finalizo 1:escarapela 2:Certificado
     * @return type 
     */
    function listarAsistentesEventos($id_evento,$Finalizo){
        $filtroSql = "";
        if($Finalizo == 1){
            $filtroSql = "  AND (participanteevento.Estado = 1                            
                            OR participanteevento.Estado = 2)
                            AND eventos.Estado = 1 ";
        }if($Finalizo == 2){
            $filtroSql = "  AND (participanteevento.Estado = 2
                            OR  participanteevento.Estado = 5)";                           
        }
        
        $sql =
            "SELECT
                invitaciones.FK_IdEvento,
                entidades.Nombres,
                entidades.Apellidos,
                invitaciones.Correo,
                entidades.Email
            FROM
                invitaciones
                LEFT JOIN entidades ON invitaciones.Correo = entidades.Email
                LEFT JOIN participanteevento ON invitaciones.PK_IdInvitacion = participanteevento.Fk_IdInvitacion
                LEFT JOIN eventos ON eventos.PK_IdEvento = participanteevento.FK_IdEvento
            WHERE 
                invitaciones.FK_IdEvento =  ".$id_evento. 
                $filtroSql;

        //Retorna la consulta
        $registro = $this->db->query($sql); 
        return $registro->result();
    }//fin listarAsistentesEventos($id_evento,$Finalizo)
    
    
    /**
     * Lista el usuario logueado a un evento para la impresion de certificado 
     * y escarapela por parte del usuario del evento 
     * 
     * @param type $id_evento
     * @return type 
     */
    function listarAsistentesEventosUsuario($id_evento){      
        $sql =
            "SELECT
                invitaciones.FK_IdEvento,
                entidades.Nombres,
                entidades.Apellidos,
                invitaciones.Correo,
                entidades.Email
            FROM
                invitaciones
                LEFT JOIN entidades ON invitaciones.Correo = entidades.Email
                LEFT JOIN participanteevento ON invitaciones.PK_IdInvitacion = participanteevento.Fk_IdInvitacion
                LEFT JOIN eventos ON eventos.PK_IdEvento = participanteevento.FK_IdEvento
            WHERE 
                invitaciones.FK_IdEvento =  ".$id_evento
                ." AND entidades.PK_IdEntidad = ".$this->session->userdata('PK_IdEntidad');
        //print_r($sql);
        //exit();
        //Retorna la consulta
        $registro = $this->db->query($sql); 
        return $registro->result();
    }//fin listarAsistentesEventosUsuario($id_evento)
    
}//Fin Impresion_model
