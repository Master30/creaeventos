<?php
/**
 *
 * @author   Horacio Romero Mendez (angelos)
 * @License   Copyleft 2011
 * @since   Sep 4, 2011 5:20:20 PM
 * @Internal  GNU/Linux Arch 2010.05 Notebook
 * 
 */


//OBTENER EL ARCHIVO QUE SE SUBIÓ
foreach($_FILES as $campo => $texto)
 eval("\$".$campo."='".$texto."';");


//COMO EL INPUT FILE FUE LLAMADO archivo ENTONCES ACCESAMOS A TRAVÉS DE $_FILES["archivo"]
?>
<table align="center">
 <tr>
  <td>
   <b>Nombre:</b>: <?php echo $_FILES["archivo"]["name"]?>
       
   <b>Tipo:</b>: <?php echo $_FILES["archivo"]["type"]?>
       
   <b>Subida:</b>: <?php echo ($_FILES["archivo"]["error"]) ? "Incorrecta" : "Correcta"?>
       
   <b>Tamaño:</b>: <?php echo $_FILES["archivo"]["size"]?> bytes
  </td>
 </tr>
</table>


<?php
//SI EL ARCHIVO SE ENVIÓ Y ADEMÁS SE SUBIO CORRECTAMENTE
if (isset($_FILES["archivo"]) && is_uploaded_file($_FILES['archivo']['tmp_name'])) {
 
 //SE ABRE EL ARCHIVO EN MODO LECTURA
 $fp = fopen($_FILES['archivo']['tmp_name'], "r");
 //SE RECORRE
 while (!feof($fp)){ //LEE EL ARCHIVO A DATA, LO VECTORIZA A DATA
  
  //SI SE QUIERE LEER SEPARADO POR TABULADORES
  //$data  = explode(" ", fgets($fp));
  //SI SE LEE SEPARADO POR COMAS
  $data  = explode(", ", fgets($fp));
  
  //AHORA DATA ES UN VECTOR Y EN CADA POSICIÓN CONTIENE UN VALOR QUE ESTA SEPARADO POR COMA.
  //EJEMPLO    A, B, C, D
  //$data[0] CONTIENE EL VALOR "A", $data[1] -> B, $data[2] -> C.


  //SI QUEREMOS VER TODO EL CONTENIDO EN BRUTO:
  var_dump($data);


  //SI QUEREMOS IMPRIMIR UN SOLO DATO
  echo "<br/>Imprimir el primer dato solo: {$data[0]}<br/>";


  //NOTA CADA VUELTA EQUIVALE A UNA LINEA COMPLETA DEL ARCHIVO CSV
 } 
   
 echo "Archivo recorrido";
   
} else 
 echo "Error de subida";