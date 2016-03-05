<?php

/* 
Plugin Name: Log Book of Ships 
Plugin URI: https://github.com/fabiodev/log-book 
Description: Sidebar widget that shows upcoming ship arrivals 
Version: 0.1 
Author: Fábio Silva 
Author URI: https://github.com/fabiodev/ 
License: A "Slug" license name e.g. GPL2 
. 
Any other notes about the plugin go here 
. 
*/  

function widget_logBook($args=array(), $params=array()) {
  extract($args);
  $widgettitle = get_option('logBook_widget_title');

 echo $before_widget;

  //Affix trial lacks div closure
  //echo '<div class="alert sidebar-widget sidebar-nav" data-spy="affix" data-offset="200">';

  echo $before_title.$widgettitle.$after_title;

        require('log_book_widget.php');


 echo $after_widget;
}

function logBook_init(){

wp_register_sidebar_widget(
    'log_book_1',        // your unique widget id
    'Log Book',          // widget name
    'widget_logBook',  // callback function
    array(                  // options
        'description' => 'Description of what your widget does'
    )
);

wp_register_widget_control(
	'log_book_1',		// id
	'Log Book',		// name
	'widget_logBook_control'	// callback function
);

}

function widget_logBook_control($args=array(), $params=array()) {
	//the form is submitted, save into database
	if (isset($_POST['submitted'])) {
		update_option('logBook_widget_title', $_POST['widgettitle']);
		update_option('logBook_widget_cookieValue', $_POST['cookievalue']);
		update_option('logBook_widget_description', $_POST['description']);
	}

	//load options
	$widgettitle = get_option('logBook_widget_title');
	$cookievalue = get_option('logBook_widget_cookieValue');
	$description = get_option('about_us_widget_description');
	?>

	Widget Title:<br />
	<input type="text" style="width:100%" name="widgettitle" value="<?php echo stripslashes($widgettitle); ?>" />
	<br /><br />

	Description about you:<br />
	<textarea style="width:100%" rows="5" name="description"><?php echo stripslashes($description); ?></textarea>
	<br /><br />

	Cookie Value:<br />
	<input type="text" style="width:100%" name="cookievalue" value="<?php echo stripslashes($cookievalue); ?>" />
	<br /><br />

	<input type="hidden" name="submitted" value="1" />
	<?php
}

function get_source(){

	$cookies = WP_PLUGIN_DIR.'/log-book/cookies.txt';
	//geting cookie
	$cr = curl_init('http://www.portosdamadeira.com/index2.php?t=1&l=pt');
	curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cr, CURLOPT_COOKIEFILE, $cookies);
	curl_setopt($cr, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($cr, CURLOPT_TIMEOUT, 2);
	curl_exec($cr);
	curl_close($cr);

	// Envia os cookies e faz request
	/*$cr = curl_init('http://www.portosdamadeira.com/mpcore.php?name=Escalas&file=diarias');
	curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cr, CURLOPT_COOKIEFILE, $cookies);
	curl_setopt($cr, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($cr, CURLOPT_TIMEOUT, 2);
	$buf2 = curl_exec($cr);*/

	//New source
	$cr = curl_init('http://www.apram.pt/site/index.php/pt/portos/movimento-de-navios');
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($cr, CURLOPT_COOKIEFILE, $cookies);
        curl_setopt($cr, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($cr, CURLOPT_TIMEOUT, 2);
        $buf2 = curl_exec($cr);

        if(curl_exec($cr) === false)
        {
        //echo 'Curl error: ' . curl_error($cr);
        }
        else
        {
        $buf2 = curl_exec($cr);
        }

        curl_close($cr);
        unset($cr);
        return $buf2;
}

function get_shipsData(){

	$tt=apc_fetch('lbook_page');

	if(isset($tt) && $tt != ""){
		//Cache valida
	}else{
	/* gets the source */
	$loaded =get_source();

	 /*loads DOM for scrapping*/
	$html = str_get_html( $loaded);

		//checks if the source is correct
		if(isset($html) && $html != "" && $html->find( '.container' ) ){

		//Detects the start of the important table
		foreach( $html->find( '#ele2' ) as $maintable ){
			//Crops the main table and counts lines
			$tableLine=1;

			foreach( $maintable->find( '.coluna-texto' ) as $ship ){
				//raw data
				//$navio = $ship->find( '.col-dir' );
				$chegada = "";
				$partida = "";
				$origem = "";
				$escala = "";
				$destino = "";
				$agente = "";
				//getting Ship Name
				foreach( $ship->find('h2') as $detalhe){
					$navio = $detalhe->plaintext;
				}
				//getting DATA
				$indexDetalhe = 1;
                                foreach( $ship->find('.col-dir') as $detalhe){
                                //getting data de chegada
					if($indexDetalhe==2){
						$chegada = $detalhe->plaintext;
				}
				//getting data de partida
                                        elseif($indexDetalhe==3){
                                                $partida = $detalhe->plaintext;
                                }
				//getting porto de origem
                                        elseif($indexDetalhe==4){
                                                $origem = $detalhe->plaintext;
                                }
				//getting porto de escala
                                        elseif($indexDetalhe==6){
                                                $escala = $detalhe->plaintext;
                                }
				//getting porto de destino
                                        elseif($indexDetalhe==5){
                                                $destino = $detalhe->plaintext;
                                }
				//getting agente
                                        elseif($indexDetalhe==1){
                                                $agente = $detalhe->plaintext;
                                }
				$indexDetalhe++;
				}
				//Creating each Ship Row
				$tempShip[$tableLine] = array('navio'=>$navio, 'chegada'=>$chegada, 'partida'=>$partida, 'origem'=>$origem, 'escala'=>$escala, 'destino'=>$destino, 'agente'=>$agente);
				//$naviosLista[$tableLine] = $tempShip;
				$tableLine++;
			}
		}
		} //END check source IF statement
		$escalas_EmCache[0] = $tempShip;
		//$escalas_EmCache[1] = "Last updated at: ". date('F jS, Y');
		$escalas_EmCache[1] = "<b>Last updated:</b> ".date('F jS, Y')." at <span class='label label-info'>".date('H')."H".date('i')."</span>";
		//stores into cache and defines array
                apc_store('lbook_page', $escalas_EmCache, 10);
		$html->clear(); 
		unset($html);
	}
}


add_filter( 'page_template', 'escalas_page_template' );
function escalas_page_template( $page_template )
{
    if ( is_page( 'escalas' ) ) {
        $page_template = dirname( __FILE__ ) . '/log_book_page.php';
    }
    return $page_template;
}

add_action("plugins_loaded", "logBook_init");

?>
