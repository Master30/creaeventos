<?php
//Zona horaria
date_default_timezone_set('America/Bogota');

if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');

/**
 * Controlador para confirmar la asistencia al evento
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy; Oscar Humberto Morales y John Arley Cano
 */
class Confirmar_controller extends CI_Controller{
    
    /**
    * Funci&oacute;n constructora de la clase Contrato. 
    * 
    * Se hereda el mismo constructor de la clase Controller para evitar sobreescribirlo y de esa manera 
    * conservar el funcionamiento de controlador.
    * 
    * @access	public
    */
    function __construct() {
        parent::__construct();
        //si el usuario no esta logueado
        if($this->session->userdata('PK_IdEntidad') != TRUE)
        {            
            //redirecciono al controlador de sesion
            redirect('sesion_controller');                        
        }
        $this->load->helper('html');
        $this->load->model(array('email_model', 'usuario_model', 'licenciamiento_model'));
        $this->load->library('encrypt');
    }
    
     /**
     * 
     * @access	public
     */
    function index(){
        //Se obtiene el id del evento
        $id_evento = $this->input->post('id_evento');
        $this->data['id_evento'] = $this->input->post('id_evento');

        $this->data['inscritos'] = $this->evento_model->listar_asistentes($id_evento);
        //print_r($this->evento_model->listar_asistentes($id_evento));
        //se establece el titulo de la p&aacute;gina
        $this->data['titulo'] = 'Administración de eventos';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Aqu&iacute; puedes confirmar asistencias al evento y desertar asistentes. Tambi&eacute;n puedes agregar participantes nuevos';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'confirmar/buscar_usuario_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()
    
    /**
     * Verifica y retorna true si el usuario ingresado existe.
     * 
     * @access	private
     */
    function _validar_usuario($nombre_usuario){
        return $this->usuario_model->validar_usuario($nombre_usuario);
    }//Fin _validar_usuario
    
    /**
     * Verifica y retorna true si el email ingresado existe.
     * 
     * @access	private
     */
    function _validar_email($email){
        return $this->usuario_model->validar_email($email);
    }//Fin _validar_email
    
    /*
     * 
     */
    function confirmar_nuevo(){
        /*
         * Crear reglas de validaci&oacute;n para los campos
         * La protecci&oacute;n contra ataques XSS se configur&oacute; desde config.php
         * trim limpia los espacios en blanco
         * min_lenght define el m&iacute;nimo de un campo
         * md5 encripta la contrase&ntilde;a
         * callback me verificar&aacute; que ese campo no exista en la base de datos
         */
        $this->form_validation->set_rules('cedula', 'El n&uacute;mero de c&eacute;dula', 'required|numeric|trim|callback__validar_usuario');
        $this->form_validation->set_rules('nombre', 'El nombre', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim|callback__validar_email');
        
        //Si el m&eacute;todo devuelve FALSE, la validaci&oacute;n no se llev&oacute; corretamente
        $this->form_validation->set_message('required', '%s es obligatorio');
        $this->form_validation->set_message('valid_email', 'El email no es v&aacute;lido');
        $this->form_validation->set_message('numeric', 'En %s se permiten n&uacute;meros y no deben llevar espacios en blanco');
        $this->form_validation->set_message('_validar_usuario', 'Este n&uacute;mero de c&eacute;dula ya existe en la base de datos');
        $this->form_validation->set_message('_validar_email', 'Este correo electr&oacute;nico ya est&aacute; registrado en nuestra base de datos.');
        
        //Esta es la condici&oacute;n que ejecuta las reglas y no lo deja pasar. Si el m&eacute;todo devuelve FALSE, la validaci&oacute;n no se llev&oacute; corretamente
        if ($this->form_validation->run() == FALSE)
        {
            //Se muestra el mensaje de alerta
            $this->data['mensaje_alerta'] = "Aun no se puede completar el registro.";
            $this->index();
        }
        else
        {
            //Se obtiene el id del evento
            $id_evento = $this->input->post('id_evento');
            
            /*
             * Se preparan los campos
             */
            $usuario = array(
                'CedulaEntidad' => $this->input->post('cedula'),
                'Nombres' => $this->input->post('nombre'),
                'Apellidos' => $this->input->post('apellido'),
                'Email' =>  $this->input->post('email'),
                'Usuario' => $this->input->post('cedula'),
                'Clave' => md5($this->input->post('cedula')),
                'Estado' => 1,
                'EventosPermitidos' => 2,
                'RestablecerClave' => $this->encrypt->encode($this->input->post('cedula'))
            );
            
            //Se inserta el usuario
            $this->usuario_model->insertar_usuario_confirmado($usuario);
            
            //Se toma el id del usuario que se ha acabado de crear
            $id_entidad = $this->db->insert_id();
            
            //Se inserta el licenciamiento b&aacute;sico
            $licencia = array(
                'Fk_Id_Entidad' => $id_entidad,
                'Tipo_Licencia' => 3
            );
            $this->licenciamiento_model->licenciamiento_basico($licencia);
            
            //Se ingresa el registro de auditor&iacute;a
            $this->auditoria_model->nueva_inscripcion_inmediata($this->input->post('nombre'), $id_entidad, $id_evento);
            
            $invitacion = array(
                'Correo' =>  $this->input->post('email'),
                'FK_IdEvento' => $id_evento
            );
            //Se inserta la invitaci&oacute;n
            $this->evento_model->insertar_invitacion($invitacion);
            
            //Se toma el id de la invitacion que se ha acabado de crear
            $id_invitacion = $this->db->insert_id();
            
            $participacion = array(
                'FK_IdEntidad' => $id_entidad,
                'FK_IdEvento' => $id_evento,
                'Estado' => 2,
                'Fk_IdInvitacion' => $id_invitacion
            );
            
            //Se inserta la participaci&oacute;n
            $this->evento_model->confirmar_asistencia($participacion);
            
            /*
             * Se preparan los datos del correo electr&oacute;nico
             */
            $usuarios = $this->input->post('email');     //Usuarios que recibir&aacute;n el correo
            $asunto = 'Bienvenido';                      //Asunto del correo
            $cabecera = 'bienvenido_cabecera.png';       //Imagen de cabecera que llevar&aacute; el correo
            $cuerpo1 =
            'Te damos la bienvenida a creaeventos.co, la manera m&aacute;s sencilla de inscribirte en un evento y obtener tu escarapela y certificado.<br>
             Al inscribirte directamente al evento al que acabas de asistir, tambi&eacute;n has activado una nueva cuenta en creaeventos.co.<br>
             Conoce la aplicaci&oacute;n y obt&eacute;n m&aacute;s informaci&oacute;n.<br><br>
             <b>Ingresa con este usuario: </b> '.$this->input->post('cedula').'<br>
             <b>Ingresa con tu contraseña: </b> '.$this->input->post('cedula');

            $cuerpo2 = 'bienvenido_cuerpo.png';         //Imagen del cuerpo del mensaje
            
            //Se muestra el mensaje de &eacute;xito
            $this->data['mensaje_exito'] = "El registro al evento se ha realizado correctamente";
            
            /*
             * Se carga la interfaz 
             */ 
            //Se obtiene el id del evento
            $this->data['id_evento'] = $id_evento;

            $this->data['inscritos'] = $this->evento_model->listar_asistentes($id_evento);
            //se establece el titulo de la p&aacute;gina
            $this->data['titulo'] = 'Administración de eventos';
            //Se establece el t&iacute;tulo del formulario
            $this->data['titulo_formulario'] = 'Administración de eventos';
            //se establece la vista que tiene el contenido principal
            $this->data['contenido_principal'] = 'confirmar/buscar_usuario_view';
            //se carga el template
            $this->load->view('includes/template', $this->data);
            
            //Se env&iacute;a el email
            $this->email_model->_enviar($usuarios, $asunto, $cabecera, $cuerpo1, $cuerpo2);
        }
    }//Fin confirmar_nuevo()
    
    /**
     * Cambia el estado del asistente a "Asistio"
     * 
     * @access	public
     */
    function confirmar(){
        //Se obtienen los id requeridos
        $id_entidad = $this->input->post('id_entidad');
        $id_evento = $this->input->post('id_evento');
        $id_invitacion = $this->input->post('id_invitacion');
        $id_estado = $this->input->post('id_estado');
        
        //Si el estado es invitado, crea un nuevo registro; de lo contrario, actualiza el existente
        if($id_estado == 0){
            $participacion = array(
                'FK_IdEntidad' => $id_entidad,
                'FK_IdEvento' => $id_evento,
                'Estado' => 2,
                'Fk_IdInvitacion' => $id_invitacion
            );

            //Se inserta la participaci&oacute;n
            $this->evento_model->confirmar_asistencia($participacion);
        }else{
            $this->evento_model->actualizar_invitacion($id_invitacion);
            
            //Se ingresa el registro de auditor&iacute;a
            $this->auditoria_model->confirmar_asistencia($id_entidad, $id_evento);
        }
        
        //Se muestra el mensaje de &eacute;xito
        $this->data['mensaje_exito'] = "El registro al evento se ha realizado correctamente";
        
        /*
         * Se carga la interfaz 
         */
        //Se obtiene el id del evento
        $this->data['id_evento'] = $id_evento;

        $this->data['inscritos'] = $this->evento_model->listar_asistentes($id_evento);
        
        //se establece el titulo de la p&aacute;gina
        $this->data['titulo'] = 'creaeventos.co - Buscar asistente';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Aqu&iacute; puedes confirmar asistencias al evento y desertar asistentes. Tambi&eacute;n puedes agregar participantes nuevos';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'confirmar/buscar_usuario_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin confirmar()
}//Fin confirmar_controller
/* End of file confirmar.php */
/* Location: ./creaeventos/application/controllers/confirmar_controller.php */