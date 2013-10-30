<?php
/**
 * Modelo que se encarga de gestionar la informaci&oacute;n
 * de las licencias por parte del usuario.
 * @author 		John Arley Cano Salinas y oscar Humberto Morales
 * @copyright           John Arley Cano Salinas y oscar Humberto Morales
 */
Class Licenciamiento_model extends CI_Model{
    /*
     * Insertar licenciamiento b&aacute;sico que consta de dos eventos
     */
    function licenciamiento_basico($licencia){
        //Se insertan los datos de la licencia
        $this->db->insert('licenciamiento', $licencia);
    }//Fin licenciamiento_basico()
    
    /*
     * Insertar licenciamiento mensual
     */
    function licenciamiento_mensual($licencia){
        //Se insertan los datos de la licencia
        $this->db->insert('licenciamiento', $licencia);
    }//Fin licenciamiento_mensual()
    
    /*
     * Insertar licenciamiento anual
     */
    function licenciamiento_anual($licencia){
        //Se insertan los datos de la licencia
        $this->db->insert('licenciamiento', $licencia);
    }//Fin licenciamiento_anual()
    
    /*
     * Insertar licenciamiento adicional
     */
    function licenciamiento_adicional($licencia){
        //Se insertan los datos de la licencia
        $this->db->insert('licenciamiento', $licencia);
    }//Fin licenciamiento_adicional()
    
    /*
     * Listar los usuarios con las licencias
     */
    function listar_licenciamientos($id_entidad){
        if($id_entidad){
            $entidad = 'WHERE entidades.PK_IdEntidad = '.$id_entidad;
        }else{
            $entidad = 'ORDER BY
            licenciamiento.Fecha_Inicio DESC';
        }
        $sql =
        "SELECT
            entidades.Pk_IdEntidad,
            entidades.Nombres,
            entidades.Apellidos,
            licenciamiento.Fecha_Inicio,
            licenciamiento.Tipo_Licencia,
            adddate(licenciamiento.Fecha_Inicio, licenciamiento.Tipo_Licencia) AS Fecha_Vencimiento,
            DATEDIFF(adddate(licenciamiento.Fecha_Inicio, licenciamiento.Tipo_Licencia), CURDATE()) AS Dias_Restantes,
            licenciamiento.Valor,
            ((DATEDIFF(adddate(licenciamiento.Fecha_Inicio, licenciamiento.Tipo_Licencia), CURDATE()))*100) / licenciamiento.Tipo_Licencia AS Porcentaje
        FROM
            entidades
        INNER JOIN licenciamiento ON licenciamiento.Fk_Id_Entidad = entidades.PK_IdEntidad
            $entidad
        ";
        
        //Se retorna la consulta
        return $this->db->query($sql)->result();
    }//Fin listar_licenciamiento()
    
    /*
     * Verifica el licenciamiento y avisa si est&aacute; cerca de cumplirse
     */
    function verificar_licencia($id_entidad){
        $sql =
        "SELECT
            licenciamiento.Fecha_Inicio,
            ((DATEDIFF(adddate(licenciamiento.Fecha_Inicio, licenciamiento.Tipo_Licencia), CURDATE()))*100) / licenciamiento.Tipo_Licencia AS Porcentaje
        FROM
            entidades
        INNER JOIN licenciamiento ON licenciamiento.Fk_Id_Entidad = entidades.PK_IdEntidad
        WHERE
            entidades.PK_IdEntidad = ".$id_entidad."
        ORDER BY
            licenciamiento.Fecha_Inicio DESC LIMIT 1";
        
        $licencia = $this->db->query($sql)->result();
        
        foreach($licencia as $item){
            if($item->Porcentaje == NULL){
                return 1;
            }elseif($item->Porcentaje < 30 && $item->Porcentaje > 0){
                return 2;
            }elseif($item->Porcentaje < 1){
                return 3;
            }else{
                return 4;
            }
        }//Fin foreach()
    }//Fin verificar_licencia()
    
    /*
     * Calcula los dias restantes de una licencia
     */
    function obtener_dias_restantes(){
        $sql =
        "SELECT
            DATEDIFF(adddate(licenciamiento.Fecha_Inicio, licenciamiento.Tipo_Licencia), CURDATE()) AS Dias_Restantes
        FROM
            entidades
        INNER JOIN licenciamiento ON licenciamiento.Fk_Id_Entidad = entidades.PK_IdEntidad
        WHERE
            entidades.PK_IdEntidad = ".$this->session->userdata('PK_IdEntidad')."
        ORDER BY
            licenciamiento.Fecha_Inicio DESC LIMIT 1";
        
        $dias = $this->db->query($sql)->result();
        foreach($dias as $dia){
            return $dia->Dias_Restantes;
        }//Fin foreach()
    }
}
/* End of file licenciamiento_model.php */
/* Location: ./creaeventos/application/controllers/licenciamiento_model.php */