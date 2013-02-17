
                <?php 

	//Check if source is available & then loads to object "portos"
		if (file_exists(dirname(__FILE__)."/temp/portos.html")){
                $portos = dirname(__FILE__)."/temp/portos.html";
                //$portos = get_template_directory().'/temp/portos.html';

		$html = file_get_html( $portos );

		if($html!=''){

		//Starts table display
		$lpage = get_bloginfo('url');
		echo "<a href=".$lpage."/index.php/escalas/><table class='table table-bordered table-hover'>";
		echo "<tr class='success'> <th> ID</th> <th>Navio</th> <th>Chegada</th> </tr>";
		foreach( $html->find( '.Table1inner' ) as $el ){ 
                        $strr= $el->find('table', 0);
			$i=1;
                        foreach( $strr->find('tr') as $TTD ){
                                echo "<tr>";
				
				echo "<td>".$i."</td>";

				$ii=1;
                                //foreach($TTD->find('td') as $outra){
					echo "<td>";
                                        //echo $outra->plaintext;
					echo $TTD->find('td',0)->plaintext;
                                        echo "</td>";

                                        echo "<td>";
                                        //echo $outra->plaintext;
                                        echo $TTD->find('td',1)->plaintext;
                                        echo "</td>";
				// }
				$i++;
                                echo "</tr>";
                        }
                  }
		echo "</table></a>";
	}
	}else{
	$ttable = file_get_contents('escalas.txt');
	echo $ttable;
	}

	//Updates "escalas.txt" if scrap is succefull
		/*if($html->find( '.Table1inner' )){
		$file="/var/www/wdev/wp-content/themes/booty/temp/escalas.txt";
		file_put_contents($file, $vtable, LOCK_EX);
		}

		$html->clear(); 
		unset($html);

		}*/

	//If "escalas.txt" exists include and display
		/*if(file_exists("/var/www/wdev/wp-content/themes/booty/temp/escalas.txt")){
			echo "<div class='alert alert-info'><h4>Last updated at: ";
			echo date ("d F Y - H:i:s.", filemtime("/var/www/wdev/wp-content/themes/booty/temp/escalas.txt"));
			echo "</h4></div>";
			require("temp/escalas.txt");
		}else{*/
	//Display error in faillure to load source
		/*echo "<div class='alert alert-error'>Oh Snap! Something went wrong I wonder what..</div>";

		}*/
                ?>
