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


<?php 
	//
	$tt=apc_fetch('lbook_page');
	if(isset($tt) && $tt != ""){
		echo "<div class='alert alert-info'><h4>Attention: ";
		echo "</h4><p>the information below may not be accurate and we don't accept any responsability for the use of such information</p></div>";
		echo $tt;
	}else{

	/* gets the source */
	$loaded =get_source();
	 /*loads DOM for scrapping*/
		$html = str_get_html( $loaded);

		//checks if the source is correct
		 if(isset($html) && $html != "" && $html->find( '.Table1inner' ) ){

		//Starts table construction
		$vtable = "<table class='table table-bordered table-hover'>";
		$vtable .= "<tr class='success'> <th>Navio</th> <th>Chegada</th> <th>Partida</th> <th>Origem</th> <th>Escala</th> <th>Destino</th> <th>Detalhes</th></tr>";
		foreach( $html->find( '.Table1inner' ) as $el ){ 
                        $strr= $el->find('table', 0);
			$i=1;
                        foreach( $strr->find('tr') as $TTD ){
                                $ii=1;
                                foreach($TTD->find('td') as $outra){
                                        if($ii==5 && $outra->plaintext=="Funchal"){
                                        	$vtable .="<tr class='info'>";
                               			 foreach($TTD->find('td') as $outra){
                                        		$vtable .= "<td>";
                                        		$vtable .= $outra->plaintext;
                                        		$vtable .="</td>";
                                 		}
						$vtable .= "</tr>";
						$i++;

                                        }elseif($ii==5 && $outra->plaintext=="Caniçal"){
                                        	$vtable .= "<tr class='error'>";

                                		foreach($TTD->find('td') as $outra){
                                        		$vtable .= "<td>";
                                        		$vtable .= $outra->plaintext;
                                        		$vtable .="</td>";
						$i++;
                                 		}
						$vtable .= "</tr>";

                                        }
                                        elseif($ii==5 && $outra->plaintext!="Caniçal" && $outra->plaintext!="Funchal"){
                                                $vtable .= "<tr class='warning'>";

                                                foreach($TTD->find('td') as $outra){
                                                        $vtable .= "<td>";
                                                        $vtable .= $outra->plaintext;
                                                        $vtable .="</td>";
						$i++;
                                                }
                                                $vtable .= "</tr>";
                                        }

                                        $ii++;
                                 }

                        }
                  }
		$vtable .= "</table>";

		//stores into cache and defines array
                apc_store('lbook_page', $vtable, 420);
                $tt=apc_fetch('lbook_page');

		$html->clear(); 
		unset($html);

		}

	//If cache exists display content
		if(isset($tt) && $tt != ""){
			echo "<div class='alert alert-info'><h4>Attention: ";
			echo "</h4><p>the information below may not be accurate and we don't accept any responsability for the use of such information</p></div>";
			echo $tt;
		}else{
	//Display error in faillure to load cache
		echo "<div class='alert alert-error'><p>Oh Snap! Something went wrong I wonder what..</p>";
		echo "<p> Try to reload this pag. If this message presists try again later or report the problem in the comments or by mail.</p></div>";

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
