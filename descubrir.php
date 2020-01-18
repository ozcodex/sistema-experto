<?php 
include_once 'lenguaje_natural.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Descubrir una fruta</title>
	<link href="estilos.css" rel="stylesheet">
</head>
<body>
<p>dejame pensarlo...</p>
<p>

<?php 
$plegaria = htmlspecialchars($_GET["plegaria"]);
//esto de dividir asi es un machetazo 
//por que de lo contrario lee solo hasta una palabra invalida
//y luego de eso se sale, hay que revisar eso en el futuro
$plegarias = preg_split("/(\r\n|\n|[,.]| y )/", $plegaria);
$momo = new Interprete();
?>
<pre>
<?php 
	$counter = 0;
	$e_counter = 0;
	$invalidas = array();
	foreach ($plegarias as $plegaria):
		$resultado_lexico = $momo->lex($plegaria);
		$resultado_sintactico = $momo->sintax($resultado_lexico);
		if($resultado_sintactico == "invalido"){
			$e_counter++;
			$invalidas[]=array_slice($resultado_lexico,0);
		}
		$counter++;
	endforeach;
	$resultados = $momo->coincidencias();
	echo "he leido $counter sentencias, ";
	echo "$e_counter sentencias incorrectas.\n";
	
	if (empty($resultados)) {
		echo "y no tengo idea de que es :(";
	}else{
		reset($resultados);
		$llave = key($resultados);
		$primero = $llave;
		$percent = (1 - $resultados[$llave])*100;
		echo "parece ser: ".$llave.", estoy seguro un ".$percent."%";
		next($resultados);
		$llave = key($resultados);
		$percent = (1 - $resultados[$llave])*100;
		if ($percent > 50 and $llave) {
			echo "\ntalvez podria ser: ".$llave.", pero solo estoy seguro un ".$percent."%";
		}
	}
?>
</pre>
<button onclick="window.history.back();">Regresar!</button>
<?php 
$argos = json_encode($momo->propiedades());
 ?>
 <br>
 <br>
<form method="get" action="guardar.php">
	  <input type="text" name="nombre" value="<?php echo "$primero"; ?>">
	  <textarea style="display:none;" type="text" name="propiedades"><?php echo $argos; ?></textarea> 
      <button type="submit">Adicionar fruta</button>
    </form>
</p>
<?php $argos = json_encode($invalidas); ?>
<?php if ($e_counter>0):?>
<form method="get" action="aprender.php">
	  <textarea style="display:none;" type="text" name="palabras"><?php echo $argos; ?></textarea> 
      <button type="submit">Aprender Palabras Nuevas</button>
    </form>
</p>
<?php endif; ?>

</body>
</html>