<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Controlador del m&oacute;dulo de Registro.
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy; Oscar Humberto Morales y John Arley Cano
 */
class Evento_controller extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        //si el usuario no esta logueado
        if($this->session->userdata('PK_IdEntidad') != TRUE)
        {            
            //redirecciono al controlador de sesion
            redirect('sesion_controller');                        
        }
        $this->load->helper('html');
        $this->load->model('licenciamiento_model');
    }
    
    function index(){
        //Se listan los eventos
        $this->data['eventos'] = $this->evento_model->listarEventos();
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Eventos creados por m&iacute;';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Estos son tus eventos. Si no has creado ninguno, puedes hacerlo haciendo clic en "Crear Evento"';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'evento/evento_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()
    
    function ver_evento(){
        //Se recibe el id del evento
        $id_evento = $this->input->post('id_evento');
        //Se carga el modelo
        $this->data['eventos'] = $this->evento_model->listarEventos($id_evento);
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Tu evento';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Información general del evento';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'evento/ver_evento_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin ver()
    
    function abrir_evento(){
        
        //Se obtiene el id del evento
        $id_evento = $this->input->post('id_evento');
        
        /*
         * Se hace el proceso de cierre de evento para los asistidos
         */
        $aprobados = array(
            'Estado' => 2 
        );
        $where = 'FK_IdEvento = '.$id_evento.' AND Estado = 5';
        $this->evento_model->abrir_evento_asistidos($aprobados, $where);
        $this->evento_model->abrir_evento($id_evento);
        
        //Se ingresa el registro de auditor&iacute;a
        $this->auditoria_model->abrir_evento($id_evento);
        
        //Se envia el mensaje de &eacute;xito
        $this->data['mensaje_exito'] = "Se ha abierto el evento correctamente";
        
        /*
        * Se carga la interfaz 
        */ 
        //Se obtiene el id del evento
        $this->data['id_evento'] = $id_evento;

        $this->data['inscritos'] = $this->evento_model->listar_asistentes($id_evento);
        //se establece el titulo de la p&aacute;gina
        $this->data['titulo'] = 'creaeventos.co - Buscar asistente';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Administración del evento';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'confirmar/buscar_usuario_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin abrir_evento()
    
    function finalizar_evento(){
        
        //Se obtiene el id del evento
        $id_evento = $this->input->post('id_evento');
        
        /*
         * Se hace el proceso de cierre de evento para todos los estados posibles
         */
        $invitados = array('Estado' => 6);
        $registrados = array('Estado' => 4);
        $aprobados = array('Estado' => 5);
        
        $where_registrados = 'FK_IdEvento = '.$id_evento.' AND Estado = 1';
        $where = 'FK_IdEvento = '.$id_evento.' AND Estado = 2';
        
        $this->evento_model->cerrar_evento_asistidos($registrados, $where_registrados);
        $this->evento_model->cerrar_evento_asistidos($aprobados, $where);
        $this->evento_model->cerrar_evento($id_evento);
        
        //Se ingresa el registro de auditor&iacute;a
        $this->auditoria_model->finalizar_evento($id_evento);
        
        //Se envia el mensaje de &eacute;xito
        $this->data['mensaje_exito'] = "Se ha cerrado el evento correctamente";
        
        /*
        * Se carga la interfaz 
        */ 
        //Se obtiene el id del evento
        $this->data['id_evento'] = $id_evento;

        $this->data['inscritos'] = $this->evento_model->listar_asistentes($id_evento);
        //se establece el titulo de la p&aacute;gina
        $this->data['titulo'] = 'creaeventos.co - Buscar asistente';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Bienvenido '.$this->session->userdata('nombre_usuario').', Revisa los eventos a los que estás invitado, crea y administra tus eventos';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'confirmar/buscar_usuario_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }
    
    function desertar(){
        //Se obtienen los id requeridos
        $id_entidad = $this->input->post('id_entidad');
        $id_evento = $this->input->post('id_evento');
        $id_invitacion = $this->input->post('id_invitacion');
        
        $this->evento_model->desertar($id_invitacion);
        //$this->evento_model->eliminar_participante($id_evento);
        
        //Se ingresa el registro de auditor&iacute;a
        $this->auditoria_model->desertar($id_entidad, $id_evento);
        
        //Se envia el mensaje de &eacute;xito
        $this->data['mensaje_exito'] = "Se ha retirado al usuario del evento correctamente";
        
        /*
        * Se carga la interfaz 
        */ 
        //Se obtiene el id del evento
        $this->data['id_evento'] = $id_evento;

        $this->data['inscritos'] = $this->evento_model->listar_asistentes($id_evento);
        //se establece el titulo de la p&aacute;gina
        $this->data['titulo'] = 'creaeventos.co - Buscar asistente';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Bienvenido '.$this->session->userdata('nombre_usuario').', Revisa los eventos a los que estás invitado, crea y administra tus eventos';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'confirmar/buscar_usuario_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin desertar()
    
    function reintegrar(){
        //Se obtienen los id requeridos
        $id_entidad = $this->input->post('id_entidad');
        $id_evento = $this->input->post('id_evento');
        $id_invitacion = $this->input->post('id_invitacion');
        
        $this->evento_model->reintegrar($id_invitacion);
        
        //Se ingresa el registro de auditor&iacute;a
        $this->auditoria_model->reintegrar($id_entidad, $id_evento);
        
        //Se envia el mensaje de &eacute;xito
        $this->data['mensaje_exito'] = "Se ha reintegrado al usuario correctamente";
        
        /*
        * Se carga la interfaz 
        */ 
        //Se obtiene el id del evento
        $this->data['id_evento'] = $id_evento;

        $this->data['inscritos'] = $this->evento_model->listar_asistentes($id_evento);
        //se establece el titulo de la p&aacute;gina
        $this->data['titulo'] = 'creaeventos.co - Buscar asistente';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Bienvenido '.$this->session->userdata('nombre_usuario').', Revisa los eventos a los que estás invitado, crea y administra tus eventos';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'confirmar/buscar_usuario_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin desertar()
}//Fin _controller
/* End of   file evento_controller.php */
/* Location: ./creaeventos/application/controllers/evento_controller.php */