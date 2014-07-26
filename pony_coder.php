<style>
	body{font-family: helvetica; width: 90%; margin: 2em auto;}
</style>

<?php  

$singular = $_POST['singular'];
$plural = $_POST['plural'];
$campos = $_POST['campo'];
$tipos = $_POST['campo_tipo'];
$longitudes = $_POST['longitud'];

echo $divisor = "<br /><br />";

foreach($campos as $field){
	$field = trim($field);
	#echo '['.$field.']';
	if($field!="id" AND $field != 'created_at' AND $field != 'updated_at'){
		
		$fields_clean[] = $field;
	}
}

//SCHEMA MIGRATION
echo "<h4>Up</h4>

Schema::create('$plural', function($"."table){<br />
$"."table->increments('id');<br />";

foreach ($campos as $key => $field) {
	echo "$"."table->".$tipos[$key]."('".$field."');<br />";

	$campos_save .= "$"."$singular"."->".$field."       = Input::get('".$field."'); <br />";
}

echo "$"."table->timestamps();<br />
		});<br />
	}<br />";

echo "<h4>Down</h4>Schema::drop('".$plural."');<br />";


echo $divisor."<h4>Routes</h4>";
//ROUTES
echo "Route::resource('control/".$plural."', '".ucfirst($plural)."Controller');";


//FORM
echo $divisor."<h4>Form</h4>";
echo "
@extends('layout') <br />

<br />	@section('content') <br />
		&lt;h4>Nuevo tipo de ".$singular."&lt;/h4> <br />
<br />
<br />	{{ HTML::ul($"."errors->all(), array('class'=>'error')) }} <br />
	

<br />	@if(isset($".$singular."))<br />
    	<br />&emsp; {{ Form::model($".$singular.", array('route' => array('control.".$plural.".update', $".$singular."->id), 'method' => 'PUT','id' => 'simpleform')) }}
<br />	@else
    	<br />&emsp; {{ Form::open(array('url'=> 'control/".$plural."', 'id' => 'simpleform')) }}
<br />	@endif";

foreach ($tipos as $field) {
	if($field == "text"){$tipos_form[] = "textarea";}
	elseif($field == "string"){$tipos_form[] = "text";}
	elseif($field == "integer"){$tipos_form[] = "text";}
	else{$tipos_form[] = $field;}
	
}

echo $divisor;
$c =0 ;
foreach ($campos as $key => $field) {
	echo "&emsp;{{ Form::label('".ucfirst($field)."') }} <br />";
	echo "&emsp;{{ Form::".$tipos_form[$c]."('".$field."', Input::old('".$field.")) }} <br /><br />";
	$c++;
}
		
	
echo "	
<br />			{{ Form::button('Aceptar', array('class'=> 'form_button', 'type'=>'submit')) }}
			
<br />			{{ link_to('control/".$plural."', 'Cancelar', array('class'=> 'form_button')) }}
		
<br />	{{ Form::close() }} 
<br />	@stop
";



//INDEX

echo '
<h4>Index.blade.php</h4>
<br />
<br />@extends(\'control.controllayout\')
<br />

<br />@section(\'content\')
<br />
&lt;script> <br />
$(document).on(\'submit\', \'.delete-form\', function(){ <br />
    return confirm(\'Confirma eliminar?\'); <br />
}); <br />
$(function(){ <br />
 $(\'[data-method]\').append(function(){ <br />
  return "\n"+ <br />
  "&lt;form action=\'"+$(this).attr(\'href\')+"\' method=\'POST\' style=\'display:none\' class=\'delete-form\'>\n"+ <br />
  "&lt;input type=\'hidden\' name=\'_method\' value=\'"+$(this).attr(\'data-method\'\)+"\'>\n"+ <br />
  "&lt;/form>\n" <br />
 }) <br />
 .removeAttr(\'href\') <br />
 .attr(\'style\',\'cursor:pointer;\') <br />
 .attr(\'onclick\',\'$(this).find("form").submit();\'); <br />
}); <br />

<br />
	
&lt;/script> <br />


	&lt;h4 class="no-margin">Monedas&lt;/h4>


	
	@foreach($monedas as $moneda)

		<div class="full item">
			<h5>{{ $moneda->nombre }}</h5>
			<p class="opciones">
				
				

			{{ link_to_route(\'control.monedas.destroy\', \'Eliminar\', $moneda->id, array(\'data-method\'=>\'delete\', \'data-confirm\' =>"Are you sure you want to delete?" )) }}
				{{ link_to( \'control/monedas/\'.$moneda->id.\'/edit\', \'Editar\' ) }}
			</p>

		</div>
		
	@endforeach

	
	
@stop

';

//CONTROLLER

echo $divisor."<h4>".ucfirst($plural)." Controller</h4>";


   
echo "
&lt;?php

<br/ > class ".ucfirst($plural)."Controller extends \BaseController {

	
<br/ >	public function index()
<br/ >	{	
<br/ >		$"."data['".$plural."'] = ".ucfirst($singular)."::All();
<br/ >		return View::make('control.".$plural.".index', $"."data);
<br/ >	}



<br/ >	public function create()
<br/ >	{
<br/ >		return View::make('control.".$plural.".form');
<br/ >	}


<br/ >	public function store()
<br/ >	{	//'field'       => 'required|unique:".$plural."'
<br/ >
<br/ >		$"."rules = array(
<br/ >			//reglas EJ: 'nombre'       => 'required|unique:".$plural."',
<br/ >		);
<br/ >		$"."messages = array(
<br/ >            
<br/ >            //mensajes EJ: 'nombre.unique' => 'El :attribute ya existe.',
<br/ >       );
<br/ >		$"."validator = Validator::make(Input::all(), $"."rules, $"."messages);

<br/ >		if ($"."validator->fails()) {
<br/ >			return Redirect::to('control/".$plural."/create')
<br/ >				->withErrors($"."validator)
<br/ >				->withInput(); 
<br/ >				//withInput() envia contenido de los campos para correccion. Puede pasarse una excepcion asi: ->withInput(Input::except('password'));
<br/ >		} else {
<br/ >			// store
<br/ >			$"."$singular = new ".ucfirst($singular).";
<br/ >			".$campos_save."
<br/ >			$"."$singular"."->save();
<br/ >
<br/ >			// redirect
<br/ >			Session::flash('success_msg', '".ucfirst($singular)." creada!');
<br/ >			return Redirect::to('control/".$plural."');
<br/ >		}
<br/ >	}


<br/ >
<br/ >
<br/ >	public function show($"."id)
<br/ >	{
<br/ >		//
<br/ >	}

<br/ >
<br/ >

<br/ >	public function edit($"."id)
<br/ >	{
<br/ >		$"."$singular = ".ucfirst($singular)."::find($"."id);
<br/ >		return View::make('control.".$plural.".form')->with(compact('moneda'));
<br/ >	}


<br/ >
<br/ >

<br/ >	public function update($"."id)
<br/ >	{
<br/ >		$"."rules = array(
<br/ >			//reglas EJ: 'nombre'       => 'required|unique:".$plural."',
<br/ >		);
<br/ >		$"."messages = array(
<br/ >            
<br/ >            //mensajes EJ: 'nombre.unique' => 'El :attribute ya existe.',
<br/ >       );
<br/ >		$"."validator = Validator::make(Input::all(), $"."rules, $"."messages);

<br/ >		if ($"."validator->fails()) {
<br/ >			$"."$singular = ".ucfirst($singular)."::find($"."id);
<br/ >			return Redirect::to('control/".$plural."/'.$"."$singular"."->id.'/edit')->with(compact('moneda'))
<br/ >				->withErrors($validator)
<br/ >				->withInput(); 
<br/ >				//withInput() envia contenido de los campos para correccion. Puede pasarse una excepcion asi: ->withInput(Input::except('password'));
<br/ >		} else {
<br/ >			// store
<br/ >			$"."$singular = ".ucfirst($singular)."::find($"."id);
<br/ >			".$campos_save."
<br/ >			$"."$singular"."->save();
<br/ >
<br/ >			// redirect
<br/ >			Session::flash('success_msg', '".ucfirst($singular)." actualizada!');
<br/ >			return Redirect::to('control/".$plural."');
<br/ >		}
<br/ >	}


<br/ >
<br/ >

<br/ >	public function destroy($"."id)
<br/ >	{
<br/ >		if(!empty($"."id)){
<br/ >			$"."$singular = ".ucfirst($singular)."::Find($"."id);
<br/ >			$"."$singular"."->delete();
<br/ >			Session::flash('success_msg', '".ucfirst($singular)." eliminada!');
<br/ >			return Redirect::to('control/".$plural."');
<br/ >		}else{
<br/ >			Redirect::to('control/".$plural."');
<br/ >		}
<br/ >	}


<br/ >}
";//end controller


//MODEL
echo $divisor."<h4>Modelo $plural </h4>";

echo "
<br/ >	&lt;?php 
<br/ > 	class ".ucfirst($plural)." extends Eloquent{
<br/ >
<br/ >	protected $table = '".$plural."';
<br/ >
<br/ >/* relationship
<br/ >	public function algun_otro_modelo()
<br/ >    {
<br/ >        return $"."this->belongsTo('nombre_otro_modelo');
<br/ >    }
<br/ > */
<br/ >
<br/ >}
<br/ >?>
";

?>