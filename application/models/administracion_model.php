<?php
/**
 * Modelo que se encarga de muestra un compilado de la informacion de los
 * usuarios que se han registrado en la aplicacion
 * 
 * @author 		John Arley Cano Salinas y Oscar Morales
 * @copyright	&copy;  John Arley Cano Salinas y Oscar Morales
 */
Class Administracion_model extends CI_Model{
    /*
     * Lista los usuarios de la aplicaci&oacute;n con ciertas caracter&iacute;sticas
     */
    function usuarios(){
        $sql =
        "SELECT
            entidades.PK_IdEntidad,
            CONCAT(entidades.Nombres,' ', entidades.Apellidos) AS Nombres,
            entidades.CedulaEntidad AS Documento,
            entidades.EventosRegistrados AS Creados
        FROM
            entidades";
        
        //Se retorna la consulta
        return $this->db->query($sql)->result();
    }//Fin usuarios
    
    function detalle_usuario($id_entidad){
        $sql =
        "SELECT
            entidades.PK_IdEntidad,
            entidades.CedulaEntidad,
            entidades.Nombres,
            entidades.Apellidos,
            entidades.Ciudad,
            entidades.Telefono,
            entidades.Email,
            entidades.Usuario,
            entidades.EventosPermitidos,
            entidades.EventosRegistrados
        FROM
            entidades
        WHERE
            entidades.PK_IdEntidad = ".$id_entidad;
        
        //Se retorna la consulta
        return $this->db->query($sql)->result();
    }//Fin detalle_usuario
}//Fin Administracion_model
/* End of file administracion_model.php */
/* Location: ./creaeventos/application/controllers/administracion_model.php */