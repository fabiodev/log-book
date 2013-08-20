<?php get_header(); ?>
<div class="row-fluid">
<div class="span12">
<div>

<section>
<?php while (have_posts()) : the_post(); ?>
<article>
<!-- <header class="page-header"> this puts a line & vertical spacing under the title-->
  <h1 class="span8" style="margin-top:40px"><?php the_title(); ?>
	<span class="nav-header" style="float:right"><?php /*"Last updated at: ". date('F jS, Y');*/ ?></span>
  </h1>
<!-- </header> -->


<?php 

	$msg ="<div class='alert alert-info span4'><h4 style='color:black'>Attention:";
	$msg .="</h4><p>the information below may not be accurate and we don't accept any responsability for the use of such information</p></div>";

	//
	$tt=apc_fetch('lbook_page');
	if(isset($tt) && $tt != ""){
		//Prints the responsibility alert and the table
		echo $msg;
		echo $tt[0];
	}else{

	/* gets the source */
	$loaded =get_source();
	 /*loads DOM for scrapping*/
		$html = str_get_html( $loaded);

		//checks if the source is correct
		 if(isset($html) && $html != "" && $html->find( '.Table1inner' ) ){

		//Starts table construction
		$vtable = "<table class='table table-bordered table-hover'>";
		$vtable .= "<tr class='success'> <th>ID</> <th>Navio</th> <th>Chegada</th> <th>Partida</th> <th>Origem</th> <th>Escala</th> <th>Destino</th> <th>Detalhes</th></tr>";

		//Detects the start of the important table
		foreach( $html->find( '.Table1inner' ) as $maintable ){ 
			//Crops the main table
                        $croppedSource= $maintable->find('table', 0);
			$tableLine=1;
			//$TRs contains all TR's on the table
                        foreach( $croppedSource->find('tr') as $TRs ){
                                $ii=1;
				//$TDs contains all TD's of the TR on the table
                                foreach($TRs->find('td') as $TDs){
                                        $prtescala = " ";
					$prtescala .= $TDs->plaintext;
					if($ii==5 && stripos($prtescala, "Funchal")){
                                        	$vtable .="<tr class='info'>";
						$vtable .="<td>".$tableLine."</td>";
						$ri=1;
                               			 foreach($TRs->find('td') as $TDs){
                                        		if($ri==5){
								$vtable .= "<td id='escalasfunchal'><a href='http://dev.shipsoncamera.com/porto-turistico-do-funchal/' data-toggle='tooltip' title='Descubra o Porto do Funchal'>";
							}else{
								$vtable .= "<td>";
							}
                                        		$vtable .= $TDs->plaintext;
                                        		$vtable .="</a></td>";
							$ri++;
                                 		}
						$vtable .= "</tr>";
						$tableLine++;

                                        }elseif($ii==5 && stripos($prtescala, "Caniçal")) {
                                        	$vtable .= "<tr class='error'>";
                                                $vtable .="<td>".$tableLine."</td>";

                                                $ri=1;
                                                 foreach($TRs->find('td') as $TDs){
                                                        if($ri==5){
                                                                $vtable .= "<td id='escalascanical'><a href='http://www.youtube.com/user/ShipsOnCamera' data-toggle='tooltip' title='Descubra o Porto do Caniçal'>";
                                                        }else{
                                                                $vtable .= "<td>";
                                                        }
                                                        $vtable .= $TDs->plaintext;
                                                        $vtable .="</a></td>";
                                                        $ri++;
                                                }
                                                $vtable .= "</tr>";
                                                $tableLine++;

                                        }elseif($ii==5 && stripos($prtescala, "socorridos")) {
                                                $vtable .= "<tr class='warning'>";
                                                $vtable .="<td>".$tableLine."</td>";

                                                $ri=1;
                                                 foreach($TRs->find('td') as $TDs){
                                                        if($ri==5){
                                                                $vtable .= "<td id='escalascanical'><a href='http://www.youtube.com/user/ShipsOnCamera' data-toggle='tooltip' title='Descubra os Terminais dos Socorridos'>";
                                                        }else{
                                                                $vtable .= "<td>";
                                                        }
                                                        $vtable .= $TDs->plaintext;
                                                        $vtable .="</a></td>";
                                                        $ri++;
                                                }
                                                $vtable .= "</tr>";
                                                $tableLine++;

                                        }elseif($ii==5 && stripos($prtescala, "porto santo")) {
                                                $vtable .= "<tr class='warning'>";
                                                $vtable .="<td>".$tableLine."</td>";

                                                $ri=1;
                                                 foreach($TRs->find('td') as $TDs){
                                                        if($ri==5){
                                                                $vtable .= "<td id='escalascanical'><a href='http://www.youtube.com/user/ShipsOnCamera' data-toggle='tooltip' title='Descubra o porto do Porto Santo'>";
                                                        }else{
                                                                $vtable .= "<td>";
                                                        }
                                                        $vtable .= $TDs->plaintext;
                                                        $vtable .="</a></td>";
                                                        $ri++;
                                                }
                                                $vtable .= "</tr>";
                                                $tableLine++;


                                        //}elseif($ii==5 && $TDs->plaintext!="Caniçal" && $TDs->plaintext!="Funchal"){
					}elseif($ii==5){
                                                $vtable .= "<tr class='warning'>";
                                                $vtable .="<td>".$tableLine."</td>";

                                                foreach($TRs->find('td') as $TDs){
                                                        $vtable .= "<td>";
                                                        $vtable .= $TDs->plaintext;
                                                        $vtable .="</td>";
						//$tableLine++;
                                                }
                                                $vtable .= "</tr>";
						$tableLine++;
                                        }

                                        $ii++;
                                 }

                        }
                  }
		$vtable .= "<tr><td colspan='8'><b>Last updated:</b> ".date('F jS, Y')." at <span class='label label-info'>".date('H')."H".date('i')."</span></td></tr>";
		$vtable .= "</table>";
		
		$escalas_EmCache[0] = $vtable;
		$escalas_EmCache[1] = "Last updated at: ". date('F jS, Y');

		//stores into cache and defines array
                apc_store('lbook_page', $escalas_EmCache, 5);
                $tt=apc_fetch('lbook_page');

		$html->clear(); 
		unset($html);

		}

	//If cache exists display content
		if(isset($tt) && $tt != ""){
			//Prints the responsibility alert and the table
			echo $msg;
			echo $tt[0];
		}else{
	//Display error in faillure to load cache
		echo "<div class='alert alert-error'><p>Oh Snap! The Black Bierd Pirates are back ...</p>";
		echo "<p>Try to reload this pag. If this message presists try again later or report the problem in the comments or by mail.</p></div><br>";

		}
	}


                ?>
<div class="alert">
<?php the_content(); ?></div>
</article>
<?php endwhile; ?>
</section>

</div>

<section id="comments">
<h3>Comments</h3>
<?php foreach (get_comments('post_id=' . get_the_ID()) as $comment):
	if($comment->comment_approved == 1): ?>
<blockquote>

<p><?php //print_r( $comment ); ?>
<?php $avat =$comment->comment_author_email;
if(function_exists('get_avatar')) { echo get_avatar($comment, '32'); } ?>

<?=$comment->comment_content?></p>
<small><strong>
<cite title="<?=$comment->comment_author?>">
<?=$comment->comment_author?></strong>
</cite>
at <?=$comment->comment_date?>
</small>
</blockquote>
<?php endif;
	endforeach; ?>
<?php include(get_template_directory().'/comments.php'); ?>
</section>
</div>

</div>
<?php get_footer(); ?>
