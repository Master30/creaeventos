<?php
Class Usuario_model extends CI_Model{
    
    function validar_usuario($nombre_usuario){
        $this->db->where('Usuario', $nombre_usuario);
        $query = $this->db->get('entidades');
        if($query->num_rows() > 0 ){
            return false;
        }else{
            return true;
        }
    }//Fin validar_usuario()
    
    function validar_email($email){
        $this->db->where('Email', $email);
        $query = $this->db->get('entidades');
        if($query->num_rows() > 0 ){
            return false;
        }else{
            return true;
        }
    }//Fin validar_email()
    
    function insertar_usuario($campo_cedula, $campo_nombre, $campo_apellido, $campo_usuario, $campo_password1, $campo_email, $campo_telefono, $campo_restablecer, $campo_ciudad){
        $data = array(
            'CedulaEntidad' => $campo_cedula,
            'Nombres' => $campo_nombre,
            'Apellidos' => $campo_apellido,
            'Usuario' => $campo_usuario,
            'Clave' => $campo_password1,
            'Email' => $campo_email,
            'Telefono' => $campo_telefono,
            'Estado' => 1,
            'EventosPermitidos' => 2,
            'RestablecerClave' => $campo_restablecer,
            'Ciudad' => $campo_ciudad
        );
        return $this->db->insert('entidades', $data);
    }
    
    function validar_login($nombre_usuario, $password){
        //columnas a retornar
        $this->db->select('PK_IdEntidad');      //Campo de IdEntidad                              
        $this->db->select('Nombres');           //Nombres 
        $this->db->select('Apellidos');         //Apellidos		
        $this->db->select('Email');             //Email
        $this->db->select('Estado');            //Estado
        //validacion
        $this->db->where('Usuario',$nombre_usuario);      //Se valida Usuario 
        $this->db->where('Clave',$password);        //Se valida clave

        //retorno consulta
        $resultado = $this->db->get('entidades')->row();                		

        return $resultado;
    }
    
    function restaurar_clave($campo_cedula, $campo_email){
        //columnas a retornar                                   
        $this->db->select('Nombres');           //Nombres 
        $this->db->select('Apellidos');         //Apellidos
        $this->db->select('Usuario');           //Campo de IdEntidad   
        $this->db->select('RestablecerClave');  //Apellidos		

        //validacion
        $this->db->where('Email',$campo_email);                 //Se valida Usuario 
        $this->db->where('CedulaEntidad',$campo_cedula);        //Se valida clave

        //retorno consulta
        $resultado = $this->db->get('entidades')->row();                		

        return $resultado;
    }
    
    /**
    * Inserta un usuario en la base de datos
    *
    * @access	public
    */
    function insertar_usuario_confirmado($usuario){
        $this->db->insert('entidades', $usuario);
    }//Fin insertar_usuario_confirmado
    
    /**
    * Funcion que valida si un Email ya se encuentra registrado en un evento 
    * para no reenviar una invitaci&oacute;n
    * 
    * @param type $email
    * @param type $IdEvento
    * @return boolean 
    */
    function validarinvitacion_email($email, $IdEvento){
        $this->db->where('Correo', $email);
        $this->db->where('FK_IdEvento',$IdEvento);
        $query = $this->db->get('invitaciones');        
        if($query->num_rows() > 0 ){
            return false;
        }else{
            return true;
        }//Fin validarinvitacion_email ($email, $IdEvento)
    }//Fin validarinvitacion_email($email, $IdEvento)
}//Fin Usuario_model
