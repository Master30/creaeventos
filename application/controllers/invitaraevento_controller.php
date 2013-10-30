<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controlador del m&oacute;dulo de Registro.
 * @author 		Oscar Humberto Morales y John Arley Cano Salinas 
 * @copyright	&copy; Oscar Humberto Morales y John Arley Cano
 */
class Invitaraevento_controller extends CI_Controller {
    /**
    * Funci&oacute;n constructora de la clase Invitaraevento_controller. 
    * 
    * Se hereda el mismo constructor de la clase Controller para evitar sobreescribirlo y de esa manera 
    * conservar el funcionamiento de controlador.
    * 
    * @access	public
    */
    function __construct() {
        parent::__construct();
        $this->load->model(array('email_model', 'usuario_model'));
        $this->load->library('email');
        if($this->session->userdata('PK_IdEntidad') != TRUE)
        {            
            //Se mata la sesion para que no entren por URL
            redirect('sesion_controller');                        
        }
        $this->load->helper('html');
        $this->load->model('licenciamiento_model');
    }
    
    /**
    * Se carga la Interface.
    * 
    * @access	public
    */
    function index() {
        //Se recibe el id del evento
        $this->data['id_evento'] = $this->input->post('id_evento');
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'Enviar invitaciones';
        //Se establece el t&iacute;tulo del formulario
        $this->data['titulo_formulario'] = 'Enviar invitaciones';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'invitaraevento/invitaraevento_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()

    /**
    * Verifica y retorna true si el email no existe en el evento seleccionado.
    * 
    * @access	private
    */
    function ValidarInvitacionEmail($email, $IdEvento){     
        return $this->usuario_model->validarinvitacion_email($email, $IdEvento);
    }//fin ValidarInvitacionEmail($email, $IdEvento)
    
    /**
    * Se Envian los emails de invitacion del evento seleccionado.
    * 
    * @access	public
    */
    function enviar(){
        $validos = 0;
        
        //
        
        //Se listan los eventos
        $eventos = $this->evento_model->listarEventos($this->uri->segment(3));
        
        /*
        * Estas son las validaciones que se realizan con el Helper form_validation
        */
        $this->form_validation->set_rules('email', 'correo', 'required|trim');
		
        //Mensajes que se muestran cuando no se supera la validaci&oacute;n
        $this->form_validation->set_message('required', 'Este campo no puede estar vacio');
		
        if($this->form_validation->run() == false){
            //Se imprime el mensaje de informaci&oacute;n
            $this->data['mensaje_alerta'] = "Faltan datos obligatorios por llenar";
            $this->index();
        }else{
            //Separamos los correos electr&oacute;nicos para enviarlos a la base de datos y al email
            $emails = explode(',', $this->input->post('email'));
            
            //Se cuentan los datos
            $total = count($emails);
            
            //Se recorren los datos y se env&iacute;a cada email
            for($w = 0; $w < $total; $w++){
                
                //Se valida que ya no exista esa invitaci&oacute;n
                $Emailvalido = $this->validarInvitacionEmail(trim($emails[$w]), $this->uri->segment(3));                
                
                if($Emailvalido != false){
                    $validos++;
                    
                    //se prepara el correo para enviar a la base de datos
                    $email = array(
                            'Correo' => trim($emails[$w]),
                            'FK_IdEvento' => $this->uri->segment(3)
                    );

                    //Estos datos se insertan en la tabla contratos mediante este modelo
                    $this->evento_model->insertar_invitacion($email);

                    /*
                    * Se hace un recorrido para mostrar los datos del evento en el correo
                    */
                    foreach($eventos as $evento):
                        $nombre_evento = $evento->NombreEvento;
                    endforeach;

                    /*
                    * Se preparan los datos del correo electr&oacute;nico
                    */
                    $usuarios = $emails[$w];                        //Usuarios que recibir&aacute;n el correo   
                    $asunto = 'Invitación especial de '.$this->session->userdata('nombre_usuario').'  '.$this->session->userdata('apellido_usuario');       
                    if($evento->Banner_Evento != NULL){
                        //echo img(array('src' => 'img_banner/'.$evento->PK_IdEvento.'/'.$evento->Banner_Evento, 'width' => '900', 'height' => '105'));
                        $cabecera = 'invitacion_cabecera.png';
                    }else{
                        $cabecera = 'invitacion_cabecera.png';
                    }

                    $cuerpo1 = 'Hola! Bienvenido a creaeventos.co. '.$this->session->userdata('nombre_usuario').'  '.$this->session->userdata('apellido_usuario').'  te est&aacute; 
                        invitando a participar del evento <b>'.$nombre_evento.'</b>.<br><br>
                        Si te interesa, aqu&iacute; est&aacute; toda la informaci&oacute;n del evento:<br><br>
                        '.$evento->DescripcionEvento.'<br/><br/>
                        <b>Inicio: </b>'.$this->auditoria_model->formato_fecha($evento->FechaInicio).' - '.$evento->HoraInicio.'<br/>
                        <b>Finalizaci&oacute;n: </b>'.$this->auditoria_model->formato_fecha($evento->FechaFin).' - '.$evento->HoraFin.'<br/><br/>
                        <b>Lugar: </b>'.$evento->DescripcionUbicacion.' - '.$evento->Direccion_Completa.'<br/>
                        <b>Ciudad: </b>'.$evento->Ciudad;

                    $cuerpo2 = 'invitacion_cuerpo.png';         //Imagen del cuerpo del mensaje

                    //Se ingresa el registro de auditor&iacute;a
                    $this->auditoria_model->enviar_invitaciones($validos, $this->uri->segment(3));
                    
                    //Se env&iacute;a el email
                    $this->email_model->_enviar($usuarios, $asunto, $cabecera, $cuerpo1, $cuerpo2);
                }//fin if($Emailvalido != false)
            }//Fin for  
        }
        
        //Valida que haya salido algun correo
        if($validos == 0){
            $this->data['mensaje_alerta'] = "No se envi&oacute; ning&uacute;n correo porque ya existen las invitaciones";
        }else{
            //Se imprime el mensaje
            $this->data['mensaje_exito'] = "Los correos se enviaron correctamente";
            
            $this->data['eventos'] = $this->evento_model->listarEventos();
            //se establece el titulo de la pag&iacute;na
            $this->data['titulo'] = 'Eventos creados por m&iacute;';
            //Se establece el t&iacute;tulo del formulario
            $this->data['titulo_formulario'] = 'Enviar invitaciones';
            //se establece la vista que tiene el contenido principal
            $this->data['contenido_principal'] = 'evento/evento_view';
            //se carga el template
            $this->load->view('includes/template', $this->data);
        }
                
    }//Fin enviar()
    
    /*
     * 
     */
    function subir(){
        //Se obtiene el archivo que se subio
        foreach($_FILES as $campo => $texto)
            
        eval("\$".$campo."='".$texto."';");
        
        //Como el input file fue llamado archivo, entonces se accesa a el a traves de $_FILES['archivo']
        $nombre = $_FILES["archivo"]["name"];
        $tipo = $_FILES["archivo"]["type"];
        $estado_subida = ($_FILES["archivo"]["error"]) ? "Incorrecta" : "Correcta";
        $tamano = $_FILES["archivo"]["size"];
        
        //Se crea un arreglo para almacenar los emails 
        $correos = array();
        
        //Si el archivo se envio y se subio correctamente:
        if (isset($_FILES["archivo"]) && is_uploaded_file($_FILES['archivo']['tmp_name'])) {
            //Se abre el archivo en modo lectura
            $fp = fopen($_FILES['archivo']['tmp_name'], "r");

            //Se recorre
            while (!feof($fp)){
                //SI SE QUIERE LEER SEPARADO POR TABULADORES
                //$data  = explode(" ", fgets($fp));
                //SI SE LEE SEPARADO POR COMAS
                $data  = explode(", ", fgets($fp));

                //AHORA DATA ES UN VECTOR Y EN CADA POSICIÓN CONTIENE UN VALOR QUE ESTA SEPARADO POR COMA.
                //EJEMPLO    A, B, C, D
                //$data[0] CONTIENE EL VALOR "A", $data[1] -> B, $data[2] -> C.

                //Ver el contenido
                //print_r($data);

                foreach ($data as $d){
                    //$a = array_push($correos, $d);
                    if($d != ''){
                        //
                        $email = array(
                            'Correo' => $d,
                            'FK_IdEvento' => 3
                        );
                        
                    //Estos datos se insertan en la tabla contratos mediante este modelo
                    $this->evento_model->insertar_invitacion($email);
                  }//Fin if
                  array_push($correos, $d);
                }//Fin foreach
            }//Fin while
            print_r($correos);
            
            /*
            * Se preparan los datos del correo electr&oacute;nico
            */
            $usuarios = $correos;                        //Usuarios que recibir&aacute;n el correo   
            $asunto = 'Invitación especial de '.$this->session->userdata('nombre_usuario').'  '.$this->session->userdata('apellido_usuario');       
            /*
            if($evento->Banner_Evento != NULL){
                //echo img(array('src' => 'img_banner/'.$evento->PK_IdEvento.'/'.$evento->Banner_Evento, 'width' => '900', 'height' => '105'));
                $cabecera = 'invitacion_cabecera.png';
            }else{
                $cabecera = 'invitacion_cabecera.png';
            }
            
            $cuerpo1 = 'Hola! Bienvenido a creaeventos.co. '.$this->session->userdata('nombre_usuario').'  '.$this->session->userdata('apellido_usuario').'  te est&aacute; 
                invitando a participar del evento <b>'.$nombre_evento.'</b>.<br><br>
                Si te interesa, aqu&iacute; est&aacute; toda la informaci&oacute;n del evento:<br><br>
                '.$evento->DescripcionEvento.'<br/><br/>
                <b>Inicio: </b>'.$this->auditoria_model->formato_fecha($evento->FechaInicio).' - '.$evento->HoraInicio.'<br/>
                <b>Finalizaci&oacute;n: </b>'.$this->auditoria_model->formato_fecha($evento->FechaFin).' - '.$evento->HoraFin.'<br/><br/>
                <b>Lugar: </b>'.$evento->DescripcionUbicacion.' - '.$evento->Direccion_Completa.'<br/>
                <b>Ciudad: </b>'.$evento->Ciudad;

            $cuerpo2 = 'invitacion_cuerpo.png';         //Imagen del cuerpo del mensaje
            */
            $cabecera = 'invitacion_cabecera.png';
            $cuerpo1 = 'Hola';
            $cuerpo2 = 'Hola';
            //Se env&iacute;a el email
            $this->email_model->_enviar($usuarios, $asunto, $cabecera, $cuerpo1, $cuerpo2);
            
            //Se imprime el mensaje
            $this->data['mensaje_exito'] = "Los correos se enviaron correctamente";
            
            $this->data['eventos'] = $this->evento_model->listarEventos();
            //se establece el titulo de la pag&iacute;na
            $this->data['titulo'] = 'Eventos creados por m&iacute;';
            //Se establece el t&iacute;tulo del formulario
            $this->data['titulo_formulario'] = 'Enviar invitaciones';
            //se establece la vista que tiene el contenido principal
            $this->data['contenido_principal'] = 'evento/evento_view';
            //se carga el template
            $this->load->view('includes/template', $this->data);
            
            
            
            
            
            
            
            
        }else{
            $this->data['mensaje_error'] = "Hubo un error al subir los correos.";
            $this->index();
        } 
    }//Fin subir()
}//Fin invitaraevento_controller
/* End of file invitaraevento_controller.php */
/* Location: ./creaeventos/application/controllers/invitaraevento_controller.php */