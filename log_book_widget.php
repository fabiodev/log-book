<?php 
        //If cache exists display content
        if(isset($tt) && $tt != ""){
        }else{
                //Gets data schedule
                get_shipsData();
                $tt=apc_fetch('lbook_page');
        }


	$lpage = get_bloginfo('url');
	$vtable = "<a href=".$lpage."/escalas/><table class='table table-hover' style='overflow-x:auto'>";
	$vtable .= "<tr class='success'> <th> ID</th> <th>Navio</th> <th>Chegada</th> </tr>";

        $shipN = 0;

        foreach ($tt[0] as $ship){

                $shipN++;

                $vtable .= "<tr>";
		$vtable .= "<td>".$shipN."</td>";
		$vtable .= "<td>".$ship[navio]."</td>";
		$vtable .= "<td>".$ship[chegada]."</td>";
                $vtable .= "</tr>";
        }

    //  Prints out last update timestamp
//	$vtable .= "<tr><td colspan='8'>".$tt[1]."</td></tr>";
//	$vtable .= "</table>";
	$vtable .= "</table></a>".$tt[1]."";


        //If cache exists display content
                if(isset($tt) && $tt != ""){
                        echo "<div style='overflow-x:auto'>" .$vtable."</div>";
                }else{
        //Display error in faillure to load cache
                echo "<div class='alert alert-error' ><p>Oh Snap! Something went wrong I wonder what..</p>";
		echo "<p>Try to reload this page. If this message pressists try again later or report the problem.</p></div>";

                }

                ?>
