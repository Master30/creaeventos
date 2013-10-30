<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');

/**
 * Controlador del m&oacute;dulo de Eventos a los que voy a asistir.
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy; Oscar Humberto Morales y John Arley Cano
 */
class Mis_eventos extends CI_Controller{
    
    /**
    * Funci&oacute;n constructora de la clase contratista. 
    * 
    * Se hereda el mismo constructor de la clase Controller para evitar sobreescribirlo y de esa manera 
    * conservar el funcionamiento de controlador.
    * 
    * @access	public
    */
    function __construct() {
        //con esta linea se hereda el constructor de la clase Controller
        parent::__construct();
        //Verifica si el usuario est&aacute; logueado
        if($this->session->userdata('PK_IdEntidad') != TRUE)
        {            
            //Redirecci&oacute;n al controlador de sesi&oacute;n
            redirect('sesion_controller');                        
        }
        //Se cargan las librer&iacute;as y helpers
        $this->load->helper('html');
    }
    
    /**
     * Muestra la vista principal del m&oacute;dulo A los que voy a asistir.
     * 
     * @access	public
     */
    function index(){        
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Eventos a los que voy a asistir';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Revisa tus eventos. Cada imagen es una acci&oacute;n diferente que puedes realizar';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'eventoaasistir/eventoaasistir_view';
        //se carga tabla Eventos
        $this->data['eventos'] = $this->evento_model->listarEventosaAsistir();
        //se carga el template
        $this->load->view('includes/template', $this->data);
        //print_r($this->evento_model->listarEventosaAsistir());
    }//Fin index()        
    
    function confirmar_asistencia(){
        $id_evento = $this->input->post('id_evento');
        $id_invitacion = $this->input->post('id_invitacion');
        //Se valida que aun haya cupos
        if ($this->evento_model->validar_cupo($id_evento) == 0){
            //Se envia el mensaje de error
            $this->data['mensaje_error'] = "Lo sentimos, ya no hay cupos para asistir al evento.";
            
            //se establece el titulo de la pag&iacute;na
            $this->data['titulo'] = 'Mis eventos';
            //Se establece el t&iacute;tulo del formulario
            $this->data['titulo_formulario'] = 'El cupo para asistir al evento ya se ha llenado. Puedes contactar con '.$this->session->userdata('nombre_usuario').' '.$this->session->userdata('apellido_usuario');
            //se establece la vista que tiene el contenido principal
            $this->data['contenido_principal'] = 'eventoaasistir/eventoaasistir_view';
            //se carga tabla Eventos
            $this->data['eventos'] = $this->evento_model->listarEventosaAsistir();
            //se carga el template
            $this->load->view('includes/template', $this->data);
            
            //Se ingresa el registro de auditor&iacute;a
            $this->auditoria_model->cupo_agotado_registro($id_evento);
        }else{
            //Se preparan los datos a enviar
            $confirmacion = array(
                'FK_IdEntidad' => $this->session->userdata('PK_IdEntidad'),
                'FK_IdEvento' => $id_evento,
                'Estado' => 1,
                'Fk_IdInvitacion' => $id_invitacion
            );

            //Se guarda en la base de datos y se suma el asistente
            $this->evento_model->confirmar_asistencia($confirmacion);
            $this->evento_model->agregar_participante($id_evento);

            //Se ingresa el registro de auditor&iacute;a
            $this->auditoria_model->confirmar_registro($id_evento);

            //Se envia el mensaje de &eacute;xito
            $this->data['mensaje_exito'] = "Felicidades! has quedado registrado al evento!";

            //se establece el titulo de la pag&iacute;na
            $this->data['titulo'] = 'Mis eventos';
            //Se establece el t&iacute;tulo del formulario
            $this->data['titulo_formulario'] = 'Revisa tus eventos. Cada imagen es una acci&oacute;n diferente que puedes realizar';
            //se establece la vista que tiene el contenido principal
            $this->data['contenido_principal'] = 'eventoaasistir/eventoaasistir_view';
            //se carga tabla Eventos
            $this->data['eventos'] = $this->evento_model->listarEventosaAsistir();
            //se carga el template
            $this->load->view('includes/template', $this->data);
        }
    }//Fin confirmar_asistencia
    
    function reingresar(){
        //Se obtiene el id del evento
        $id_evento = $this->input->post('id_evento');
        $id_invitacion = $this->input->post('id_invitacion');
        
        //Se valida que aun haya cupos
        if ($this->evento_model->validar_cupo($id_evento) == 0){
            //Se envia el mensaje de error
            $this->data['mensaje_error'] = "Lo sentimos, ya no hay cupos para asistir al evento.";
            
            //Se ingresa el registro de auditor&iacute;a
            $this->auditoria_model->cupo_agotado_reingreso($id_evento);
            
            //se establece el titulo de la pag&iacute;na
            $this->data['titulo'] = 'Mis eventos';
            //Se establece el t&iacute;tulo del formulario
            $this->data['titulo_formulario'] = 'El cupo para asistir al evento ya se ha llenado. Puedes contactar con '.$this->session->userdata('nombre_usuario').' '.$this->session->userdata('apellido_usuario');
            //se establece la vista que tiene el contenido principal
            $this->data['contenido_principal'] = 'eventoaasistir/eventoaasistir_view';

            //se carga tabla Eventos
            $this->data['eventos'] = $this->evento_model->listarEventosaAsistir();
            //se carga el template
            $this->load->view('includes/template', $this->data);
        }else{
            //Se env&iacute;a al modelo y se agrega uno mas
            $this->evento_model->reingresar($id_invitacion);
            $this->evento_model->agregar_participante($id_evento);
            
            //Se ingresa el registro de auditor&iacute;a
            $this->auditoria_model->reingresar($id_evento);

            //Se envia el mensaje de exito
            $this->data['mensaje_exito'] = "Te has registrado de nuevo al evento exitosamente.";
            //se establece el titulo de la pag&iacute;na
            $this->data['titulo'] = 'Mis eventos';
            //Se establece el t&iacute;tulo del formulario
            $this->data['titulo_formulario'] = 'Revisa tus eventos. Cada imagen es una acci&oacute;n diferente que puedes realizar';
            //se establece la vista que tiene el contenido principal
            $this->data['contenido_principal'] = 'eventoaasistir/eventoaasistir_view';

            //se carga tabla Eventos
            $this->data['eventos'] = $this->evento_model->listarEventosaAsistir();
            //se carga el template
            $this->load->view('includes/template', $this->data);
        }
    }//Fin reingresar()
    
    function rechazar_asistencia(){
        //Se obtiene el id del evento
        $id_evento = $this->input->post('id_evento');
        $id_invitacion = $this->input->post('id_invitacion');
        
        //Se env&iacute;a al modelo
        $this->evento_model->cancelar_registro($id_invitacion);
        
        //Se ingresa el registro de auditor&iacute;a y se resta un participante
        $this->auditoria_model->cancelar_asistencia($id_evento);
        $this->evento_model->eliminar_participante($id_evento);
        
        //Se envia el mensaje de exito
        $this->data['mensaje_exito'] = "Has cancelado tu registro al evento.";
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Mis eventos';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Revisa tus eventos. Cada imagen es una acci&oacute;n diferente que puedes realizar';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'eventoaasistir/eventoaasistir_view';
 
        //se carga tabla Eventos
        $this->data['eventos'] = $this->evento_model->listarEventosaAsistir();
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin rechazar_asistencia
}//Fin mis_eventos
/* End of file mis_eventos.php */
/* Location: ./creaeventos/application/controllers/mis_eventos.php */
