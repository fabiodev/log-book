<?php get_header(); ?>
<div class="row-fluid">
<div class="span12">
<div>

<section>
<?php while (have_posts()) : the_post(); ?>
<article>
<!-- <header class="page-header"> this puts a line & vertical spacing under the title-->
  <h1><?php the_title(); ?></h1>
<!-- </header> -->


<?php /* gets the source */
function get_source($url){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_COOKIE,'EBBSID=HGT2uMonFoMfBUzFciE4TA2J');
        //curl_setopt($ch,CURLOPT_COOKIE,$cookiev);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        if(curl_exec($ch) === false)
        {
        echo 'Curl error: ' . curl_error($ch);
        }
        else
        {
        $buf2 = curl_exec($ch);
        }

        curl_close($ch);
        unset($ch);
        return $buf2;
}

        $loaded = get_source('http://www.portosdamadeira.com/mpcore.php?name=Escalas&file=diarias');

	//Scraping of the anual schedule needs refinement
        //$loaded = get_source('http://www.portosdamadeira.com/mpcore.php?name=Escalas&file=anual');

	if(isset($loaded) && $loaded!=""){
	        //$my_file = get_template_directory().'/temp/portos.html';
		$my_file = dirname( __FILE__ ).'/temp/portos.html';
        	$handle = fopen($my_file, 'wr') or die('Cannot open file:  '.$my_file);
        	fwrite($handle, $loaded);
	}

	 /*starts the scrapping*/

	//Check if source is available & then loads to object "portos"
		//$portos = get_template_directory()."/temp/portos.html";
		$portos = dirname( __FILE__ ).'/temp/portos.html';
		if (file_exists($portos)){
                
		$html = file_get_html( $portos );

		//Starts table display
		/*echo*/ $vtable = "<table class='table table-bordered table-hover'>";
		/*echo*/ $vtable .= "<tr class='success'> <th>Navio</th> <th>Chegada</th> <th>Partida</th> <th>Origem</th> <th>Escala</th> <th>Destino</th> <th>Detalhes</th></tr>";
		foreach( $html->find( '.Table1inner' ) as $el ){ 
                        $strr= $el->find('table', 0);
			$i=1;
                        foreach( $strr->find('tr') as $TTD ){
                                /*echo*/ //$vtable .="<tr>";
				
				/*echo*/ //$vtable .= "<td>".$i."</td>";


                                $ii=1;
                                foreach($TTD->find('td') as $outra){
                                        if($ii==5 && $outra->plaintext=="Funchal"){
                                        /*echo*/ $vtable .="<tr class='info'>";

                               			 foreach($TTD->find('td') as $outra){
                                        		$vtable .= "<td>";
                                        		/*echo*/ $vtable .= $outra->plaintext;
                                        		/*echo*/ $vtable .="</td>";
                                 		}
						$vtable .= "</tr>";
						$i++;

                                        }elseif($ii==5 && $outra->plaintext=="Caniçal"){
                                        	$vtable .= "<tr class='error'>";

                                		foreach($TTD->find('td') as $outra){
                                        		$vtable .= "<td>";
                                        		/*echo*/ $vtable .= $outra->plaintext;
                                        		/*echo*/ $vtable .="</td>";
						$i++;
                                 		}
						$vtable .= "</tr>";

                                        }
                                        elseif($ii==5 && $outra->plaintext!="Caniçal" && $outra->plaintext!="Funchal"){
                                                $vtable .= "<tr class='warning'>";

                                                foreach($TTD->find('td') as $outra){
                                                        $vtable .= "<td>";
                                                        /*echo*/ $vtable .= $outra->plaintext;
                                                        /*echo*/ $vtable .="</td>";
						$i++;
                                                }
                                                $vtable .= "</tr>";

                                        }

                                        $ii++;
                                 }

				//$vtable .= "<td>".$i."</td>";
//
				/*$ii=1;
                                foreach($TTD->find('td') as $outra){
					$vtable .= "<td>";
                                        /*echo*/ //$vtable .= $outra->plaintext;
                                        /*echo*/ //$vtable .="</td>";
				 /*}
				$i++;*/
                                /*echo*/ //$vtable .= "</tr>";
                        }
                  }
		/*echo*/ $vtable .= "</table>";

	//Updates "escalas.html" if scrap is succefull
		if($html->find( '.Table1inner' )){
		//$file=get_template_directory()."/temp/escalas.html";
		$file = dirname( __FILE__ ).'/temp/escalas.html';
		file_put_contents($file, $vtable, LOCK_EX);
		}

		$html->clear(); 
		unset($html);

		}

	//If "escalas.txt" exists include and display
		//if(file_exists(get_template_directory()."/temp/escalas.html")){
		if(file_exists(dirname( __FILE__ )."/temp/portos.html")){
			echo "<div class='alert alert-info'><h4>Last updated at: ";
			//echo date ("d F Y - H:i:s.", filemtime(get_template_directory()."/temp/escalas.html"));
                        echo date ("d F Y - H:i:s.", filemtime(dirname(__FILE__)."/temp/escalas.html"));
			echo "</h4></div>";
			//require(get_template_directory()."/temp/escalas.html");
			require(dirname( __FILE__ ).'/temp/escalas.html');
		}else{
	//Display error in faillure to load source
		echo "<div class='alert alert-error'>Oh Snap! Something went wrong I wonder what..</div>";

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
