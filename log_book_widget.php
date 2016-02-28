<?php 
	//Try to load and display from cache if fail gets source
	$tt=apc_fetch('lbook_widget');
        if(isset($tt) && $tt != ""){
		echo $tt;
	}else{

        //Scraping of the anual schedule needs refinement
        $loaded = get_source();
	//Gets source
	$html =str_get_html($loaded);

	//checks if the source is correct
	if(isset($html) && $html != "" && $html->find( '.container' ) ){
		//Starts table display
		$lpage = get_bloginfo('url');
		$short_table = "<a href=".$lpage."/escalas/><table class='table table-hover' style='overflow-x:auto'>";
		$short_table .= "<tr class='success'> <th> ID</th> <th>Navio</th> <th>Chegada</th> </tr>";

		//START new SCRAPING
		foreach( $html->find( '#ele2' ) as $maintable ){
			//Crops the main table and counts lines
  			$tableLine=1;

			$shipList="";
			foreach( $maintable->find( '.coluna-texto' ) as $ship ){
				//raw printout of DIV by ship
				$shipList .= $ship;

				//raw data
				$navio = "";
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

				$indexDetalhe++;
				}//END new SCRAPING

				//Creating each Ship Row
				$short_table .= "<tr>";
				$short_table .= "<td>".$tableLine."</td>";
				$short_table .= "<td>".$navio."</td><td>".$chegada."</td><td>";
				$short_table .= "</tr>";
				$tableLine++;
			}
		}

		} //END check source IF statement


		$short_table .= "</table></a>";
		$short_table .= "<a class='btn btn-mini btn-success' style='margin-left:34%' href=/index.php/escalas/>Mais detalhes >></a>";

                //stores into cache and defines array
                apc_store('lbook_widget', $short_table, 10);
                $tt=apc_fetch('lbook_widget');

                $html->clear(); 
                unset($html);


        //If cache exists display content
                if(isset($tt) && $tt != ""){
                        echo "<div style='overflow-x:auto'>" .$tt."</div>";
                }else{
        //Display error in faillure to load cache
                echo "<div class='alert alert-error' ><p>Oh Snap! Something went wrong I wonder what..</p>";
		echo "<p>Try to reload this page. If this message pressists try again later or report the problem.</p></div>";

                }
}
                ?>
