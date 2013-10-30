<?php
//require_once('fpdf.php');

/**
* Controlador para administrar impresion certificado
* @author 		Oscar Humberto Morales y John Arley Cano Salinas 
* @copyright	&copy;  Oscar Humberto Morales y John Arley Cano
* Basicamente estamos haciendo uso de la plataforma cruzada llamada Adobe Javascript, 
* a través de la cual adobe nos facilita la inserción de códigos javascript 
* dentro de un documento u objeto adobe.
*  
*/
class ImprimirPDF extends FPDF
{
    /**
    * funciones javascrip para imprimir
    */
    var $javascript;
    var $n_js;
    
    /**
    * recibe un script que almacenada en el atributo Javascript.
    */
    function IncludeJS($script) {
        $this->javascript=$script;
    }//fin IncludeJS($script)

    /**
    * es el que se encarga de escribir el codigo almacenado dentro del atributo javascript, 
    * usando el método out para escribir el código a través de la declaracion de objeto embebido, 
    * propia de adobe javascript.
    * 
    */
    function _putjavascript() {
        $this->_newobj();
        $this->n_js=$this->n;
        $this->_out('<<');
        $this->_out('/Names [(EmbeddedJS) '.($this->n+1).' 0 R]');
        $this->_out('>>');
        $this->_out('endobj');
        $this->_newobj();
        $this->_out('<<');
        $this->_out('/S /JavaScript');
        $this->_out('/JS '.$this->_textstring($this->javascript));
        $this->_out('>>');
        $this->_out('endobj');
    }//fin _putjavascript()
    
    /**
    * el método putrecourses se encarga de verificar la existencia de código javascript en el pdf, 
    * asl no encontrarlo llamara a putjavascript para que inserte el código.
    * 
    */
    function _putresources() {
        parent::_putresources();
        if (!empty($this->javascript)) {
            $this->_putjavascript();
        }
    }//fin _putresources()
    
    /**
    * verifica si el atributo javascript esta vacío y en caso de estarlo 
    * agrega la linea inicial del código javascript dentro del pdf.
    * 
    */
    function _putcatalog() {
        parent::_putcatalog();
        if (!empty($this->javascript)) {
            $this->_out('/Names <</JavaScript '.($this->n_js).' 0 R>>');
        }
    }//fin _putcatalog()
    
    /**
    * para abrir el dialogo de la impresora  
    * @param type $dialog 
    */
    function imprime($dialog=false)
    {
        //Abre el dialogo de impresion
        $param=($dialog ? 'true' : 'false');
        $script="print($param);";
        $this->IncludeJS($script);
    }//fin imprime($dialog=false)

    /**
     * Imprime en impresora compartida
     * @param type $server
     * @param type $printer
     * @param type $dialog 
     */
    function AutoPrintToPrinter($server, $printer, $dialog=false)
    {
        $script = "var pp = getPrintParams();";
        if($dialog){
            $script .= "pp.interactive = pp.constants.interactionLevel.full;";
        }else{
            $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
            $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
            $script .= "print(pp);";
            $this->IncludeJS($script);
        }    
    }//fin AutoPrintToPrinter($server, $printer, $dialog=false)
    
    //funciones para imprimir texto html
    var $B;
    var $I;
    var $U;
    var $HREF;

    function PDF($orientation='P', $unit='mm', $size='A4')
    {
        // Llama al constructor de la clase padre
        $this->FPDF($orientation,$unit,$size);
        // Iniciación de variables
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->HREF = '';
    }

    function WriteHTML($html)
    {
        // Intérprete de HTML
        $html = str_replace("\n",' ',$html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else
            {
                // Etiqueta
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    // Extraer atributos
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        // Etiqueta de apertura
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF = $attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Etiqueta de cierre
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modificar estilo y escoger la fuente correspondiente
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
        {
            if($this->$s>0)
                $style .= $s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt)
    {
        // Escribir un hiper-enlace
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}
?>
