<?php
/**
 *     ---------------       DO NOT DELETE!!!     ---------------
 * 
		Plugin Name: Advanced Random Post
		Version: 0.2
		Plugin URI: http://www.danielesalamina.it/advanced-random-post
		Description: Selects a defined number of  random posts from the archive and shows them on the front page
		Author: Daniele Salamina
		Author URI: http://www.danielesalamina.it
 *
 *     ---------------       DO NOT DELETE!!!     ---------------
 *
 *    This is the required license information for a Wordpress plugin.
 *
 *    Copyright 2007  Daniele Salamina  (email : dsalamina@gmail.com)
 *
 *    This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *     ---------------       DO NOT DELETE!!!     ---------------
 Thanks:
 
  - Martin Chlupáč(http://fredfred.net/skriker/) for the great Archivist plugin
  - Wordpress Plugin Framework - http://www.doubleblackdesign.com/categories/wordpress-plugins/wordpress-plugin-framework/
 
 Changelog:
  - 0.1		02/01/2008	First Beta Version
  - 0.1.1	30/01/2008	Fix: No Static Pages (thx Tom)
  - 0.2		27/02/2008	Random post from custom Categories and Tags
 
 
 */


/**
 * Include the WordpressPluginFramework.
 */
require_once( "wordpress-plugin-framework.php" ); 

class ARP extends ARP_WordpressPluginFramework
{
   
   function HTML_DisplayPluginOptionsDisplayedBlock()
   {
      $this->DisplayPluginOption( 'arp_number_of_random_post' );
      ?>
      <br />
      <br />
      <?php
      $this->DisplayPluginOption( 'arp_frontpage_position' );
      ?>
      <br />
      <br />
      <?php
      $this->DisplayPluginOption( 'arp_text_title' );
      ?>
      <br />
      <br />
      <?php
      $this->DisplayPluginOption( 'arp_from_categories' );    
   }

   function HTML_DisplayPluginOptionsListedBlock()
   {
      $optionsArray = $this->GetOptionsArray();
      
      if( is_array( $optionsArray ) )
      {
         ?>
         <table>
            <thead>
               <tr>
                  <th>Option Name</th>
                  <th>Option Type</th>
                  <th>Option Value</th>
                  <th>Option Description</th>
                  <th>Option Values Array</th>
               </tr>
            </thead>
            <tbody>
               <?php
               foreach( $optionsArray AS $optionKey=>$optionValueArray )
               {
               ?>
                  <tr>
                     <td><?php echo( $optionKey ); ?></td>
                     <td><?php echo( $this->GetOptionType( $optionKey ) ); ?></td>
                     <td><?php echo( $this->GetOptionValue( $optionKey ) ); ?></td>
                     <td><?php echo( $this->GetOptionDescription( $optionKey ) ); ?></td>
                     <td><?php echo( $this->GetOptionValuesArray( $optionKey ) ); ?></td>
                  </tr>
               <?php 
		      }
		      ?>
		      </tbody>
		   </table>
		   <?php
      }
   }

  
   function HTML_DisplayPluginDescriptionBlock()
   {
      ?>
      <p>Selects a defined number of random posts from the archive and shows them on the home page</p>
      <?php
   }
}

if( !$ARP  )
{
  // Create a new instance of your plugin that utilizes the WordpressPluginFramework and initialize the instance.
  $myARP = new ARP();
  $myARP->Initialize( 'Advanced Random Post', '0.2', 'advanced-random-post', 'advanced-random-post' );
  
  // Add all of the options specific to your plugin then register the options with the Wordpress core.
  $myComboboxRP = array( '1', '2', '3', '4', '5' );
  $myARP->AddOption( $myARP->OPTION_TYPE_COMBOBOX, 'arp_number_of_random_post', $myComboboxRP[0], 'How many random post i show ?', $myComboboxRP );
  $myComboboxFP = array( '0','1', '2', '3', '4', '5' );
  $myARP->AddOption( $myARP->OPTION_TYPE_COMBOBOX, 'arp_frontpage_position', $myComboboxFP[1], 'Random post position in the front page. Indexed from 0 (zero)',$myComboboxFP );
  //$myARP->AddOption( $myARP->OPTION_TYPE_CHECKBOX, 'arp_keep_the_limit', $myARP->CHECKBOX_CHECKED, 'if unchecked then the random posts will be added to the number of posts already shown on the front page' );
  $myARP->AddOption( $myARP->OPTION_TYPE_TEXTBOX, 'arp_text_title', 'Random Post:<br /> %title', 'Text displayed in the title of random post. %title will be replace with original post title.'  );
  $myARP->AddOption( $myARP->OPTION_TYPE_TEXTBOX, 'arp_from_categories', '', 'List of IDs of categories separated by commas (Ex. 3,4,18). Empty string means that this rule is not used. <a href="categories.php"><strong>View Category ID</strong></a>.'  );
  $myARP->RegisterOptions( __FILE__ );
  
  $myARP->AddAdministrationPageBlock( 'block-description', 'Plugin Description', $myARP->CONTENT_BLOCK_TYPE_MAIN, array($myARP, 'HTML_DisplayPluginDescriptionBlock') );
  $myARP->AddAdministrationPageBlock( 'block-options-displayed', 'Plugin Options', $myARP->CONTENT_BLOCK_TYPE_MAIN, array($myARP, 'HTML_DisplayPluginOptionsDisplayedBlock') );
  $myARP->RegisterAdministrationPage( $myARP->PARENT_MENU_OPTIONS, $myARP->ACCESS_LEVEL_ADMINISTRATOR, 'Advanced Random Post', 'ARP Options Page', 'arp-plugin-options' );
}


function arp_core($posts){
	global $wpdb, $wp_query, $paged;

	if(!$wp_query->is_home || $paged > 1 || $wp_query->query_vars['paged'] > 1)//random post should be only on the front page
		{
			return $posts;
		}
		
		$my_posts = array();
		
		$j = count($posts);//trick to keep the limit of posts per page
		for($i = 0; $i <= $j; $i++){
			$post = $posts[$i];
			
			if($i == get_option('arp_frontpage_position') ){
				$oldest_post = $posts[count($posts)-1]->post_date_gmt;//to select posts that are not on the page already
				$now = current_time('mysql', 1);
				$cat_list = get_option('arp_from_categories');

				//let's start with query
				$query = "SELECT * FROM $wpdb->posts p ";
				
				if($cat_list != ""){
					$query .=",$wpdb->term_relationships t";
				}
				
				$query .= " WHERE p.post_status = 'publish' AND p.post_date_gmt <= '$oldest_post' AND p.post_type = 'post' ";
				
				$limit = get_option('arp_number_of_random_post');

				//select only from defined categories and tag
				if ($cat_list != ""){
					$query .="AND t.object_id = p.ID AND (";
					
					$tok = strtok($cat_list, ",");
					
					$first = true;
					while ($tok) {
						$foo = trim($tok);
						if(!$first){ $query .=' OR ';}
						else {$first = false;}
						$query .="t.term_taxonomy_id = '$foo' ";
						$tok = strtok(",");
					}
					
					$query .= ') ';
					
				}
				
				$orderby = 'rand()';
				$query .= "ORDER BY $orderby ";
				$query .= " LIMIT {$limit}";
				
				if($drafts = $wpdb->get_results($query)){
					foreach($drafts as $draft){
						$draft->post_title =  str_replace("%title", $draft->post_title, get_option(arp_text_title) );
						$my_posts[] = $draft;
						$random_posts[] = $draft->ID;
					}
					//we don't want to exceed the limit of posts per page
					//if(get_option('arp_keep_the_limit')){
					//			$j -= count($drafts);
					//}
				}
		
				//for debug
				//echo $query;
				
			}
			if($i < $j){
				$my_posts[] = $post;
			}
		}
		update_post_caches($my_posts) ;
		return $my_posts;	
			
}
 	// Add filter
 	add_filter('the_posts','arp_core');

?>
