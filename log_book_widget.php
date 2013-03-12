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
	if(isset($html) && $html != "" && $html->find( '.Table1inner' ) ){

		//Starts table display
		$lpage = get_bloginfo('url');
		$short_table = "<a href=".$lpage."/index.php/escalas/><table class='table table-hover'>";
		$short_table .= "<tr class='success'> <th> ID</th> <th>Navio</th> <th>Chegada</th> </tr>";
		foreach( $html->find( '.Table1inner' ) as $el ){ 
                        $strr= $el->find('table', 0);
			$i=1;
                        foreach( $strr->find('tr') as $TTD ){
                                $short_table .= "<tr>";
				
				$short_table .= "<td>".$i."</td>";

				$ii=1;
                                //foreach($TTD->find('td') as $outra){
					$short_table .= "<td>";
                                        //echo $outra->plaintext;
					$short_table .= $TTD->find('td',0)->plaintext;
                                        $short_table .= "</td>";

                                        $short_table .= "<td>";
                                        //echo $outra->plaintext;
                                        $short_table .= $TTD->find('td',1)->plaintext;
                                        $short_table .= "</td>";
				// }
				$i++;
                                $short_table .= "</tr>";
                        }
                  }
		$short_table .= "</table></a>";
		$short_table .= "<a class='btn btn-mini btn-success' style='margin-left:34%' href=/index.php/escalas/>Mais detalhes >></a>";

                //stores into cache and defines array
                apc_store('lbook_widget', $short_table, 420);
                $tt=apc_fetch('lbook_widget');

                $html->clear(); 
                unset($html);

                }

        //If cache exists display content
                if(isset($tt) && $tt != ""){
                        echo $tt;
                }else{
        //Display error in faillure to load cache
                echo "<div class='alert alert-error'><p>Oh Snap! Something went wrong I wonder what..</p>";
		echo "<p>Try to reload this page. If this message pressists try again later or report the problem.</p></div>";

                }
	}
                ?>
