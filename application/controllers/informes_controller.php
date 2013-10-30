<?php  if ( ! defined('BASEPATH')) exit('Lo sentimos, usted no tiene acceso a esta ruta');
/**
 * Controlador del m&oacute;dulo de Informes.
 * @author 		John Arley Cano Salinas
 * @copyright	&copy; John Arley Cano Salinas - Oscar Humberto Morales.
 */
class Informes_controller extends CI_Controller{
    function __construct() {
        parent::__construct();
        if($this->session->userdata('PK_IdEntidad') != TRUE)
        {            
            //redirecciono al controlador de sesion
            redirect('sesion_controller');                        
        }
        $this->load->library('fpdf');
        $this->load->helper('html');
        
    }//Fin construct
    
    function index(){
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'creaeventos.co - Informes';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'informes/informes_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin index()
    
    /**
    * Imprime la escarapela de prueba al inicio
    * @author 		John Arley Cano Salinas
    * @copyright	&copy; John Arley Cano Salinas
    */
    function escarapela_prueba(){
        
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        $pdf->SetY(5);
        $pdf->SetFont('Arial','B',20);
        $pdf->MultiCell(0,7, utf8_decode('Esta es la escarapela que obtendrás al registrarte a un evento:'),0,'C');
        
        //Cargar la imagen de fondo de la escarapela
        $pdf->Image('img/plantillas/fondo_escarapela.png', 65, 20, 0, 0);
        
        $pdf->Ln();
        
        /*
        $pdf->SetFont('Arial','B',22);
        $pdf->SetY(33);
        $pdf->MultiCell(83,7, utf8_decode('Primer Encuentro de Egresados de la Institución Universitaria Escolme'),0,'C');//Nombre del evento
        */
        $pdf->Ln(50);
        $pdf->SetFont('Arial','B',24);
        $pdf->MultiCell(0,7, utf8_decode(strtoupper($this->session->userdata('nombre_usuario'))),0,'C');//Nombres del participante
        $pdf->SetFont('Arial','B',12);
        $pdf->MultiCell(0,7, $this->session->userdata('apellido_usuario'),0,'C');//Apellidos del participante
        $pdf->Image('img/logo.png', 70, 145, 0, 0);//Logo

        $pdf->SetFont('Arial','',8);
        $pdf->SetY(155);
        //$pdf->MultiCell(83,7, utf8_decode('Creado por John Arley Cano y Oscar Humberto Morales García'),0,'C');//Firma
        //Limpiar búferes de salida
        ob_end_clean ();
        
        //Imprimimos el PDF
        $pdf->Output('Escarapela de prueba', 'I');
    }//Fin escarapela_prueba()
    
    /**
    * Imprime la escarapela de cada participante.
    * @author 		John Arley Cano Salinas
    * @copyright	&copy; John Arley Cano Salinas
    */
    function escarapela_individual(){
        /*
        * Reporte que genera la escarapela para cada asistente
        * 
        */
        $id_evento = $this->uri->segment(3);
        
        //Cargamos el metodo que nos muestra todos los eventos
        $eventos = $this->data['eventos'] = $this->evento_model->listarEventos($id_evento);
        
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        foreach ($eventos as $evento):
            //Cargar la imagen de fondo de la escarapela
            if($evento->Escarapela_Evento == NULL){
                //$pdf->Image('img_escarapelas/'.$id_evento.'/'.$evento->Escarapela_Evento, 10, 10);
            }else{
                $pdf->Image('img_escarapelas/'.$id_evento.'/'.$evento->Escarapela_Evento, 65, 20, 0, 0);
            }
            
            /*
            $pdf->SetFont('Arial','B',20);
            $pdf->SetY(23);
            $pdf->MultiCell(83,7, substr(utf8_decode($evento->NombreEvento), 0, 100),0,'C');//Nombre del evento
             */

            $pdf->Ln(50);
            $pdf->SetFont('Arial','B',24);
            $pdf->MultiCell(0,7, strtoupper($this->session->userdata('nombre_usuario')),0,'C');//Nombres del participante
            $pdf->SetFont('Arial','B',12);
            $pdf->MultiCell(0,7, $this->session->userdata('apellido_usuario'),0,'C');//Apellidos del participante
            $pdf->Image('img/logo.png', 70, 145, 0, 0);//Logo
        endforeach;
		
        $pdf->SetFont('Arial','',8);
        $pdf->SetY(135);
        //$pdf->MultiCell(83,7, utf8_decode('Creado por John Arley Cano y Oscar Humberto Morales García'),0,'C');//Firma
        //Limpiar búferes de salida
        ob_end_clean ();
        
        //Imprimimos el PDF
        $pdf->Output('Escarapela '.$this->session->userdata('nombre_usuario').' '.$this->session->userdata('apellido_usuario'), 'I');
    }//Fin escarapela_individual()
    
    
    
    /**
    * Imprime la escarapela de prueba al inicio
    * @author 		John Arley Cano Salinas
    * @copyright	&copy; John Arley Cano Salinas
    */
    function certificado_prueba(){
        
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de PDF
        $pdf = new FPDF('L','mm','Letter');
        $pdf->AddPage();
		
        //Cargar la imagen de fondo del certificado
        $pdf->Image('img/plantillas/fondo_certificado.png',0,0);
        
        $pdf->SetXY(0,30);
        $pdf->SetFont('Arial','B',40);
        $pdf->MultiCell(280,15, utf8_decode('Aquí podrás ubicar el certificado según lo desees. Puedes incluir la plantilla que desees'),0,'C');
        $pdf->Ln();
        
        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(100,155);
		$pdf->Image('img/logo.png',110,125);//Logo
        $pdf->MultiCell(83,7, utf8_decode('Creado por John Arley Cano y Oscar Humberto Morales García'),0,'C');//Firma
		
        //Limpiar búferes de salida
        ob_end_clean ();
        
        //Imprimimos el PDF
        $pdf->Output('Certificado de prueba', 'I');
    }//Fin certificado_prueba()
    
    /**
    * Muestra el listado de usuarios inscritos 
    * @author 		John Arley Cano Salinas
    * @copyright	&copy; John Arley Cano Salinas
    */
    function inscritos(){
        //Se obtiene el id del evento
        $id_evento = $this->uri->segment(3);

        $this->data['inscritos'] = $this->evento_model->listar_asistentes($id_evento);
        //se establece el titulo de la pag&iacute;na
        $this->data['titulo'] = 'creaeventos.co - Inscritos';
        //se establece la vista que tiene el contenido principal
        $this->data['contenido_principal'] = 'informes/inscritos_view';
        //se carga el template
        $this->load->view('includes/template', $this->data);
    }//Fin inscritos
    
    function asistentes(){
        //Establecemos la ruta para las fuentes del reporte
        define('FPDF_FONTPATH','application/font/');
        
        //Generamos una nueva hoja de PDF
        $pdf = new FPDF('P','mm','Letter');
        $pdf->AddPage();
        
        //Limpiar búferes de salida
        ob_end_clean ();
        
        //Imprimimos el PDF
        $pdf->Output('Certificado de prueba', 'I');
    }//Fin asistentes()
}
?>
