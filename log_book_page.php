<?php get_header(); ?>
<div class="row-fluid">
<div class="span12">
<div>

<section>
<?php while (have_posts()) : the_post(); ?>
<article>
  <h1 class="span8" style="margin-top:40px"><?php the_title(); //Page Title ?>
	<span class="nav-header" style="float:right"><?php /*"Last updated at: ". date('F jS, Y');*/ ?></span>
  </h1>

<?php

        $args = array(
    'smallest'                  => 12, 
    'largest'                   => 12,
    'unit'                      => 'pt', 
    'number'                    => 0,  
    'format'                    => 'array',
    'orderby'                   => 'name', 
    'order'                     => 'ASC',
    'exclude'                   => null, 
    'include'                   => null, 
    'link'                      => 'view', 
    'taxonomy'                  => 'post_tag',
    'echo'                      => true,
);
 $terms = wp_tag_cloud( $args );


 $count = count($terms);
 if ( $count > 0 ){
        $ship_tag=0;
     foreach ( $terms as $term ) {
        if(stripos($term, " imo ")){
		 $shipList[] = $term ;
                $ship_tag++;
        }
     }
}

?>


<?php 

	date_default_timezone_set('Atlantic/Madeira');
	$permalink = get_permalink( $id );

	$msg ="<div class='pull-right'><div class='fb-like pull-right' data-href='".$permalink."' data-width='350' data-layout='button_count' data-show-faces='true' data-send='true'></div></div>";
	$msg .="<div class='alert alert-info span4'><h4 style='color:black'>Attention:";
	$msg .="</h4><p>the information below may not be accurate and we don't accept any responsability for the use of such information</p></div>";

	$tt=apc_fetch('lbook_page');

	//If cache exists display content
	if(isset($tt) && $tt != ""){
	}else{
		//Gets data schedule
	        get_shipsData();
		$tt=apc_fetch('lbook_page');
	}


	$vtable = "<table class='table table-bordered table-hover'>";
	$vtable .= "<tr class='success'> <th>ID</th> <th>Navio</th> <th>Chegada</th> <th>Partida</th> <th>Origem</th> <th>Escala</th> <th>Destino</th> <th>Detalhes</th></tr>";

	$shipN = 0;

	foreach ($tt[0] as $ship){

		$shipN++;

		$vtable .= "<tr><td>".$shipN."</td>";

		foreach($ship as $detalhe){
			$vtable .= "<td>".$detalhe."</td>";
		}

		$vtable .= "</tr>";
	}

//	$vtable .= "<tr><td colspan='8'><b>Last updated:</b> ".date('F jS, Y')." at <span class='label label-info'>".date('H')."H".date('i')."</span></td></tr>";
	$vtable .= "<tr><td colspan='8'>".$tt[1]."</td></tr>";
	$vtable .= "</table>";



	//If cache exists display content
		if(isset($tt) && $tt != ""){
			//Prints the responsibility alert and the table
			echo $msg;
			//print_r($temShips);
			echo $vtable;
		}else{
	//Display error in faillure to load cache
		echo "<div class='alert alert-error span4'><p>Oh Snap! The Black Bierd Pirates are back ...</p>";
		echo "<p>Try to reload this pag. If this message presists try again later or report the problem in the comments or by mail.</p></div>";

		}
//}

?>
</div>
</article></section>
<article><section>
<div class="alert span12">
<?php the_content(); ?></div>
</article>
<?php endwhile; ?>
</section>

<!-- </div> -->

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

<?php
        //Custom Page Widgets
        if (!dynamic_sidebar('custom_page_sidebar')) {
                //include('lib/sidebar-static.php');
        }
?>

</div>

</div>
<?php get_footer(); ?>
