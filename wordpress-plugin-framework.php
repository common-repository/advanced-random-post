<?php
/**
 *     ---------------       DO NOT DELETE!!!     ---------------
 * 
 *    This is the required license information for a Wordpress plugin.
 *
 *    Copyright 2007  Keith Huster  (email : husterk@doubleblackdesign.com)
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
 */


/**
 * WordpressPluginFramework - Base class for Wordpress plugins.
 *
 * NOTE: This class must be prefixed with the name of your plugin in order to prevent class duplication errors
 *       since PHP does not have a concept of namespaces.
 *
 * This class forms a base class for other Wordpress plugins to derive from in order to provide
 * a more standard way of developing plugins.
 * 
 * @package wordpress-plugin-framework
 * @since {WP 2.3} 
 * @author Keith Huster
 */
class ARP_WordpressPluginFramework
{
   // ---------------------------------------------------------------------------
   // Class constants required by the Wordpress Plugin Framework.
   // ---------------------------------------------------------------------------
   
   // Current version of the WordpressPluginFramework class.
   var $PLUGIN_FRAMEWORK_VERSION = "0.04";
   
   // Top level administration menus.
   var $PARENT_MENU_DASHBOARD = "index.php";
   var $PARENT_MENU_WRITE = "post-new.php";
   var $PARENT_MENU_MANAGE = "edit.php";
   var $PARENT_MENU_COMMENTS = "edit-comments.php";
   var $PARENT_MENU_BLOGROLL = "link-manager.php";
   var $PARENT_MENU_PRESENTATION = "themes.php";
   var $PARENT_MENU_PLUGINS = "plugins.php";
   var $PARENT_MENU_USERS = "users.php";
   var $PARENT_MENU_OPTIONS = "options-general.php";
   
   // Required access rights levels.
   var $ACCESS_LEVEL_ADMINISTRATOR = 8;
   var $ACCESS_LEVEL_EDITOR = 3;
   var $ACCESS_LEVEL_AUTHOR = 2;
   var $ACCESS_LEVEL_CONTRIBUTOR = 1;
   var $ACCESS_LEVEL_SUBSCRIBER = 0;
   
   // Types of administration page content blocks.
   var $CONTENT_BLOCK_TYPE_MAIN = "content-block-type-main";
   var $CONTENT_BLOCK_TYPE_SIDEBAR = "content-block-type-sidebar";
   
   // Indices for the parameters associated with content blocks.
   var $CONTENT_BLOCK_INDEX_TITLE = 0;
   var $CONTENT_BLOCK_INDEX_TYPE = 1;
   var $CONTENT_BLOCK_INDEX_FUNCTION = 2;
   var $CONTENT_BLOCK_INDEX_FUNCTION_CLASS = 0;
   var $CONTENT_BLOCK_INDEX_FUNCTION_NAME = 1;
   
   // General option definitions.
   var $OPTION_PARAMETER_NOT_FOUND = "Not found...";
   
   // Indices for the parameters associated with options.
   var $OPTION_INDEX_VALUE = 0;
   var $OPTION_INDEX_DESCRIPTION = 1;
   var $OPTION_INDEX_TYPE = 2;
   var $OPTION_INDEX_VALUES_ARRAY = 3;
   
   // Types of options to be displayed on the administration page.
   var $OPTION_TYPE_TEXTBOX = "text";
   var $OPTION_TYPE_TEXTAREA = "textarea";
   var $OPTION_TYPE_CHECKBOX = "checkbox";
   var $CHECKBOX_UNCHECKED = "";
   var $CHECKBOX_CHECKED = "on";
   var $OPTION_TYPE_RADIOBUTTONS = "radio";
   var $OPTION_TYPE_PASSWORDBOX = "password";
   var $OPTION_TYPE_COMBOBOX = "combobox";
   var $OPTION_TYPE_FILEBROWSER = "file";
   var $OPTION_TYPE_HIDDEN = "hidden";
   
   
   
   // ---------------------------------------------------------------------------
   // Class write-once variables to be updated only by the Initialize() and
   // RegisterAdministrationPage() functions only.
   // ---------------------------------------------------------------------------
   
   /**
	 * @var string   Title of the plugin utilizing the WPF.
	 *   Note: Accessible via the Initialize() function only.
	 *   Note: You may input any string you wish for the title of your plugin.
	 *   Example: "Test plugin for the WPF".
	 */
   var $_pluginTitle = "";
   
   /**
	 * @var string   Version of the plugin utilizing the WPF.
	 *   Note: Accessible via the Initialize() function only.
	 *   Note: You may input a string in the following format "#.##".
	 *   Example: "1.01".
	 */
   var $_pluginVersion = "";
   
   /**
	 * @var string   Name of the folder containing this plugin.
	 *   Note: Accessible via the Initialize() function only.
	 *   Note: You may input any valid folder name string (please seperate words with "-" characters).
	 *   Example: "wpf-test-plugin".
	 */
   var $_pluginSubfolderName = "";
   
   /**
	 * @var string   Name of the file containing this plugin.
	 *   Note: Accessible via the Initialize() function only.
	 *   Note: You may input any valid file name string (please seperate words with "-" characters).
	 *   Example: "test-plugin".
	 */
   var $_pluginFileName = "";
   
   /**
	 * @var string   Name of the plugin's administration menu.
	 *   Note: Accessible via the RegisterAdministrationPage() function only.
	 *   Note: You may input any valid string (please try to stay less than 30 characters for readability).
	 *   Example: "Test Plugin".
	 */
   var $_pluginAdminMenuTitle = "";
   
   /**
	 * @var string   Browser title displayed for the plugin's administration webpage.
	 *   Note: Accessible via the RegisterAdministrationPage() function only.
	 *   Note: You may input any valid string (please try to stay less than 30 characters for readability).
	 *   Example: "Test Plugin Options".
	 */
   var $_pluginAdminMenuPageTitle = "";
   
   /**
	 * @var string   URI slug displayed for the plugin's administration webpage.
	 *   Note: Accessible via the RegisterAdministrationPage() function only.
	 *   Note: You may input any valid URI string (please seperate words with "-" characters).
	 *   Example: "test-plugin-options".
	 */
   var $_pluginAdminMenuPageSlug = "";
   
   
   
   // ---------------------------------------------------------------------------
   // Class variables required by the Wordpress Plugin Framework.
   // ---------------------------------------------------------------------------
  
	/**
	 * @var string   The parent menu of the plugin's administration submenu.
	 *   Note: Valid values are defined by "PARENT_MENU_xxx" at the top of this file.
	 */
	var $_pluginAdminMenuParentMenu = "";
	
	/**
	 * @var string   Minimum access level required to access the plugin's administration submenu.
	 *   Note: Valid values are defined by "RIGHTS_REQUIRED_xxx" at the top of this file.
	 */
	var $_pluginAdminMenuMinimumAccessLevel = "";
		
	/**
	 * @var array    Array of options for this plugin.
	 *   Note: Valid values are defined by "OPTION_TYPE_xxx" at the top of this file.
	 */
	var $_pluginOptionsArray = array();
	
	/**
	 * @var array    Array of content blocks for this plugin's administration page.
	 *   Note: Valid values are defined by "CONTENT_BLOCK_TYPE_xxx" at the top of this file.
	 */
	var $_pluginAdminMenuBlockArray = array();
	
	
	
	// ---------------------------------------------------------------------------
   // Public properties of the WordpressPluginFramework class.
   // ---------------------------------------------------------------------------
   
   /**
	 * GetOptionsArray() - Retrieves the options array for the plugin.
	 *
	 * This function retrieves the options array for this plugin from the internal WordpressPluginFramework
    * base class.
	 *
	 * @param void      None.
	 * 
    * @return array   $optionsArray       The plugin's options array.
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function GetOptionsArray()
   {
      return $this->_pluginOptionsArray;
   }
	
	
	
	// ---------------------------------------------------------------------------
   // Methods used to handle initialization of this plugin.
   // ---------------------------------------------------------------------------
  
   /**
	 * Initialize() - Initializes the standard parameter set associated with this plugin.
	 *
	 * This function initializes the standard parameter set associated with this plugin so that the plugin
	 * may be safely integrated into the Wordpress core.
	 *
	 * @param string    $title                The title of the plugin.
	 * @param string    $version              The version of the plugin.
	 * @param string    $subfolderName        The name of the plugin subfolder installed under the root plugins directory.
	 * @param string    $fileName             The name of the plugin's main file.
	 * 
    * @return void     None.  	 
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function Initialize( $title, $version, $subfolderName, $fileName )
	{
	   // Store the specified plugin parameters.
      $this->_pluginTitle = $title;
      $this->_pluginVersion = $version;
      $this->_pluginSubfolderName = $subfolderName;
      $this->_pluginFileName = $fileName;
   }
	
	
	
	// ---------------------------------------------------------------------------
   // Methods used to handle the administration page for this plugin.
   // ---------------------------------------------------------------------------
  
   /**
	 * RegisterAdministrationPage() - Registers the plugin's administration page.
	 *
	 * This function registers the plugin's administration page with the Wordpress core via the add_action()
    * hook. This hook allows the plugin's administration paege to be processed as any standard Wordpress
    * administration page (such as the dashboard).
	 *
	 * @param string    $parentMenu          Parent menu of the plugin's administration menu.
	 * @param string    $minimumAccessLevel  Minimum user access rights required to access the plugin's administration page.
	 * @param string    $adminMenuTitle      Name of the plugin's administration menu.
	 * @param string    $adminMenuPageTitle  Browser title of the plugin's administration page.
	 * @param string    $adminMenuPageSlug   URI slug displayed for the plugin's administration webpage.
	 * 
    * @return void     None.  	 
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
	function RegisterAdministrationPage( $parentMenu, $minimumAccessLevel, $adminMenuTitle, $adminMenuPageTitle, $adminMenuPageSlug )
   {
      // Store the specified administration page parameters.
      $this->_pluginAdminMenuParentMenu = $parentMenu;
      $this->_pluginAdminMenuMinimumAccessLevel = $minimumAccessLevel;
      $this->_pluginAdminMenuTitle = $adminMenuTitle;
      $this->_pluginAdminMenuPageTitle = $adminMenuPageTitle;
      $this->_pluginAdminMenuPageSlug = $adminMenuPageSlug;
      
      // Wordpress hook for adding plugin admininistration menus.
      add_action( 'admin_menu', array( $this, '_AddAdministrationPage' ) );
	}
	
	/**
	 * _AddAdministrationPage() - Adds the plugin's administration page to the Wordpress core.
	 *
	 * This function adds the plugin's administration page to the Wordpress core by acting as a callback
    * function that was registered to the "admin_menu" function in the Wordpress core.
	 *
	 * @param void      None.	 
	 * 
    * @return void     None.  	 
	 * 
	 * @access private  Access via admin_menu() callback only.
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
	function _AddAdministrationPage()
	{
      add_submenu_page( $this->_pluginAdminMenuParentMenu,
                        $this->_pluginAdminMenuPageTitle,
                        $this->_pluginAdminMenuTitle,
                        $this->_pluginAdminMenuMinimumAccessLevel,
                        $this->_pluginAdminMenuPageSlug,
                        array( $this, '_DisplayPluginAdministrationPage' ) );
   }
   
   /**
	 * AddAdministrationPageBlock() - Adds a block of content to be displayed in the plugin's administration page.
	 *
	 * This function adds a block of content (i.e. an instance of a dbx-box class) to the plugin's administration
    * page. The placement and size of the block is controlled by the $blockType parameter.
	 *
	 * @param string    $blockId             ID of the content block used in HTML formatting (no spaces allowed).
	 * @param string    $blockTitle          Title of the content block.
	 * @param string    $blockType           Type of content block (one of CONTENT_BLOCK_TYPE_xxx).
	 * @param string    $blockFunctionPtr    Function containing the content to be displayed.
	 * 
    * @return void     None.  	 
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function AddAdministrationPageBlock( $blockId, $blockTitle, $blockType, $blockFunctionPtr )
   {
      // Add a new page block to the array of available page blocks.
      $this->_pluginAdminMenuBlockArray[$blockId] = array( $blockTitle, $blockType, $blockFunctionPtr );
   }
   
   /**
	 * _DisplayAdministrationPageBlocks() - Displays the plugin's administration page blocks.
	 *
	 * This function displays each of the content blocks, of the specified type, that have been added to the
    * _pluginAdminMenuBlockArray via calls to the AddAdministrationPageBlock() function. The content blocks
    * are displayed from top to bottom in the order that they were added to the array.
	 *
	 * @param string    $blockType           Type of content block (one of CONTENT_BLOCK_TYPE_xxx).
	 * 
    * @return void     None.  	 
	 * 
	 * @access private
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function _DisplayAdministrationPageBlocks( $blockType )
   {
      if( is_array( $this->_pluginAdminMenuBlockArray ) )
      {
         foreach( $this->_pluginAdminMenuBlockArray AS $blockKey=>$blockValue )
         {
            if( $blockValue[$this->CONTENT_BLOCK_INDEX_TYPE] == $blockType )
            {
               switch( $blockType )
               {
                  case $this->CONTENT_BLOCK_TYPE_SIDEBAR:
                     // Create the markup necessary to display a SIDEBAR area content block.
                     ?>
                     <fieldset id="<?php echo( $blockKey ); ?>" class="dbx-box">
                        <h3 class="dbx-handle"><?php echo( $blockValue[$this->CONTENT_BLOCK_INDEX_TITLE] ); ?></h3>
                        <div class="dbx-content">
                           <?php
                           // Display the actual content contained within the block.
                           $blockClass = $blockValue[$this->CONTENT_BLOCK_INDEX_FUNCTION][$this->CONTENT_BLOCK_INDEX_FUNCTION_CLASS];
                           $blockFunction = $blockValue[$this->CONTENT_BLOCK_INDEX_FUNCTION][$this->CONTENT_BLOCK_INDEX_FUNCTION_NAME];
                           $blockClass->$blockFunction();
				               ?>
      				      </div>
				         </fieldset>
				         <?php
                     break;
                  case $this->CONTENT_BLOCK_TYPE_MAIN:
                     // Create the markup necessary to display a MAIN area content block.
                     ?>
                     <fieldset id="<?php echo( $blockKey ); ?>" class="dbx-box">
                        <div class="dbx-h-andle-wrapper">
                           <h3 class="dbx-handle"><?php echo( $blockValue[$this->CONTENT_BLOCK_INDEX_TITLE] ); ?></h3>
                        </div>
                        <div class="dbx-c-ontent-wrapper">
                           <div class="dbx-content">
                              <?php
                              // Display the actual content contained within the block.
                              $blockClass = $blockValue[$this->CONTENT_BLOCK_INDEX_FUNCTION][$this->CONTENT_BLOCK_INDEX_FUNCTION_CLASS];
                              $blockFunction = $blockValue[$this->CONTENT_BLOCK_INDEX_FUNCTION][$this->CONTENT_BLOCK_INDEX_FUNCTION_NAME];
                              $blockClass->$blockFunction();
      				            ?>
      				         </div>
      				      </div>
      				   </fieldset>
      				   <?php
                     break;
                  default:
                     // Do not display an unknown block type.
                     break;
               }
            }
         }
      }
   }
  
   /**
	 * _InitializeDbxManagementSystem() - Loads and initializes the DBX Management system.
	 *
	 * This function loads and initializes the DBX Management system used to create the dynamic content blocks
    * for the plugin's administration page. This system handles all aspects of the dynamic content blocks including
    * the title bar, drag and drop capability, as well as collapse and expand capabilities.
	 *
	 * @param void      None.
	 * 
    * @return void     None.  	 
	 * 
	 * @access private
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function _InitializeDbxManagementSystem()
   {
      ?>
      <script type="text/javascript" src="../wp-includes/js/dbx.js"></script>
		<script type="text/javascript">
		//<![CDATA[
		   addLoadEvent( function() {
            // Create a new DBX Manager to handle the dynamic AJAX based content blocks.
		  	   var dbxManagerId = "<?php echo( preg_replace( '[-]', '_', $this->_pluginAdminMenuPageSlug ) ); ?>";
		  	   var wpf_dbxManager = new dbxManager( dbxManagerId );
					
				// Create a new docking boxes group for the SIDEBAR content blocks.
				var sidebarGroup = new dbxGroup(
			      'sidebarBlocks',        // container ID [/-_a-zA-Z0-9/]
					'vertical', 	         // orientation ['vertical'|'horizontal']
					'10', 			         // drag threshold ['n' pixels]
					'yes',			         // restrict drag movement to container axis ['yes'|'no']
					'10', 			         // animate re-ordering [frames per transition, or '0' for no effect]
					'yes', 			         // include open/close toggle buttons ['yes'|'no']
					'open', 		            // default state ['open'|'closed']
					<?php echo "'" . js_escape( __( 'open' ) ) . "'" ?>,      // word for "open", as in "open this box"
					<?php echo "'" . js_escape( __( 'close' ) ) . "'" ?>,     // word for "close", as in "close this box"
					<?php echo "'" . js_escape( __( 'click-down and drag to move this box' ) ) . "'" ?>,    // sentence for "move this box" by mouse
					<?php echo "'" . js_escape( __( 'click to %toggle% this box' ) ) . "'" ?>,              // pattern-match sentence for "(open|close) this box" by mouse
					<?php echo "'" . js_escape( __( 'use the arrow keys to move this box' ) ) . "'" ?>,     // sentence for "move this box" by keyboard
					<?php echo "'" . js_escape( __( ', or press the enter key to %toggle% it' ) ) . "'" ?>, // pattern-match sentence-fragment for "(open|close) this box" by keyboard
					'%mytitle%  [%dbxtitle%]'   // pattern-match syntax for title-attribute conflicts
					);

            // Create a new docking boxes group for the MAIN content blocks.
				var mainGroup = new dbxGroup(
					'mainBlocks',           // container ID [/-_a-zA-Z0-9/]
					'vertical', 		      // orientation ['vertical'|'horizontal']
					'10', 			         // drag threshold ['n' pixels]
					'yes',			         // restrict drag movement to container axis ['yes'|'no']
					'10', 			         // animate re-ordering [frames per transition, or '0' for no effect]
					'yes', 			         // include open/close toggle buttons ['yes'|'no']
					'open', 		            // default state ['open'|'closed']
					<?php echo "'" . js_escape( __( 'open' ) ) . "'" ?>,      // word for "open", as in "open this box"
					<?php echo "'" . js_escape( __( 'close' ) ) . "'" ?>,     // word for "close", as in "close this box"
					<?php echo "'" . js_escape( __( 'click-down and drag to move this box' ) ) . "'" ?>,    // sentence for "move this box" by mouse
					<?php echo "'" . js_escape( __( 'click to %toggle% this box' ) ) . "'" ?>,              // pattern-match sentence for "(open|close) this box" by mouse
					<?php echo "'" . js_escape( __( 'use the arrow keys to move this box' ) ) . "'" ?>,     // sentence for "move this box" by keyboard
					<?php echo "'" . js_escape( __( ', or press the enter key to %toggle% it' ) ) . "'" ?>, // pattern-match sentence-fragment for "(open|close) this box" by keyboard
					'%mytitle%  [%dbxtitle%]'   // pattern-match syntax for title-attribute conflicts
					);
			} );
      //]]>
		</script>
      <?php
   }
   
   /**
	 * _DisplayFadingMessageBox() - Displays a fading message box at the top of the plugin's administration screen.
	 *
	 * This function displays a fading message box at the top of the plugin's administration screen. This is typically
	 * used to show updates when a form is submitted or the plugin is being uninstalled.
	 *
	 * @param string    $htmlMessage               A formatted HTML message to be displayed.
	 * 
    * @return void     None.  	 
	 * 
	 * @access private
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function _DisplayFadingMessageBox( $htmlMessage )
   {
      // Create the markup necessary to display the fading message box.
      echo( '<div id="message" class="updated fade">' );
      echo( $htmlMessage );
      echo( '</div>' );
   }

   /**
	 * _DisplayPluginAdministrationPage() - Displays the plugin's administration page.
	 *
	 * This function displays the plugin's administration page that previously registered by a call
	 * to the AddAdministrationPage() function. This function utilizes the DBX Management system created
	 * by the _InitializeDbxManagementSystem() function to properly parse and display the page. This function
	 * acts as a callback for the add_submenu_page() Wordpress core function.
	 *
	 * @param void      None.
	 * 
    * @return void     None.  	 
	 * 
	 * @access private  Access via add_submenu_page() callback only.
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function _DisplayPluginAdministrationPage()
   {
      ?>
      <div class="wrap" id="<?php echo( $this->_pluginAdminMenuPageSlug ) ?>-div">
         <form method="post">
            <?php
            if( $_REQUEST['plugin_options_update'] )
            {
               // Update the plugin's options.
               $this->_UpdatePluginOptions( &$_REQUEST );
            }
            else if( $_REQUEST['plugin_options_reset'] )
            {
               // Reset the plugin's options.
               $this->_ResetPluginOptions();
               
            }
            else if( $_REQUEST['plugin_options_uninstall'] )
            {
               // Uninstall the plugin by removing the plugin options from the Wordpress database.
               $this->_UnregisterPluginOptions();
            }
            
            if( $this->_IsPluginInstalled() )
            {
               ?>
               <h2><?php echo( $this->_pluginTitle . ' (v' . $this->_pluginVersion . ')' ); ?></h2>
            
               <?php $this->_InitializeDbxManagementSystem(); ?>
               
               <div id="poststuff">
                  <div id="moremeta"> <!-- Used to locate blocks in the SIDEBAR content area. -->
                     <div id="sidebarBlocks" class="dbx-group">
                        <?php
                        // Load the Sidebar blocks first...
                        $this->_DisplayAdministrationPageBlocks( $this->CONTENT_BLOCK_TYPE_SIDEBAR );
                        ?>
                     </div>
                  </div>
                  
                  <div id="advancedstuff"> <!-- Used to locate blocks in the main area. -->
                     <div id="mainBlocks" class="dbx-group" >
                        <div class="dbx-b-ox-wrapper">
                           <?php
                           // Then load the main content blocks...
                           $this->_DisplayAdministrationPageBlocks( $this->CONTENT_BLOCK_TYPE_MAIN );
                           ?>
                        </div>
                     </div>
                  </div>
                  
                  <div>
   				     <p class="submit">
   				        <input type="submit" name="plugin_options_update" value="Update options" />
   					     <input type="submit" name="plugin_options_reset" value="Reset options" onclick='return( confirm( "Do you really want to reset these options to their default values?" ) );' />
   					     <br /><br />
   					     <input type="submit" name="plugin_options_uninstall" value="Uninstall plugin" onclick='return( confirm( "Do you really want to uninstall this plugin and delete all stored information from the database?\n\nNote: You will be asked to deactivate the plugin to complete the uninstallation process." ) );' />
   				     </p>
   			      </div>
               </div>
               <?php
            }
            else
            {
               // Update the URL to perform deactivation of the specified plugin.
               $deactivateUrl = 'plugins.php?action=deactivate&amp;plugin=' . $this->_pluginSubfolderName . '/' . $this->_pluginFileName . '.php';
			      if( function_exists( 'wp_nonce_url' ) )
               {
                  $actionName = 'deactivate-plugin_' . $this->_pluginSubfolderName . '/' . $this->_pluginFileName . '.php';
                  $deactivateUrl = wp_nonce_url( $deactivateUrl, $actionName );
               }
               
               // Remind the user to deactivate the plugin.
               $uninstalledMessage = '<p>All of the "' . $this->_pluginTitle . '" plugin options have been deleted from the database.</p>';
               $uninstalledMessage .= '<p><strong><a href="' . $deactivateUrl . '">Click here</a></strong> to finish the uninstallation and deactivate the "' . $this->_pluginTitle . '" plugin.</p>';
               $this->_DisplayFadingMessageBox( $uninstalledMessage );
            }
            ?>
          
         </form>
      </div>
      <?php
   }
  
  
  
   // ---------------------------------------------------------------------------
   // Methods used to handle the plugin options.
   // ---------------------------------------------------------------------------
  
   /**
	 * _AddOption() - Adds an option to the plugin's options array.
	 *
	 * This function adds the specified option to the plugin's options array. This array can then be used to
	 * manage the interface between the plugin options and the Wordpress options database.
	 *
	 * @param string    $optionType          Type of the option to add to the array.
	 * @param string    $optionName          Name of the option to add to the array.
	 * @param mixed     $optionValue         Value of the option to add to the array.
	 * @param string    $optionDescription   Description of the option to add to the array.
	 * @param string    $optionValuesArray   Array of selectable values for the option.
	 * 
    * @return void     None.  	 
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function AddOption( $optionType, $optionName, $optionValue, $optionDescription, $optionValuesArray = '' )
   {
      $this->_pluginOptionsArray[$optionName] = array( $optionValue, $optionDescription, $optionType, $optionValuesArray );
   }
   
   /**
	 * RegisterOptions() - Registers the plugin options with the Wordpress core.
	 *
	 * This function registers the Wordpress core activation hook required to store the plugin options
    * in the Wordpress options database.
	 *
	 * @param string    $pluginFile          Full path to the plugin's file.
	 * 
    * @return void     None.  	 
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function RegisterOptions( $pluginFile )
	{
      // Register the hooks required to properly handle activation of this plugin.
      register_activation_hook( $pluginFile, array( $this, '_RegisterPluginOptions' ) );
   }
     
   /**
	 * _RegisterPluginOptions() - Adds the plugin's options to the Wordpress options database.
	 *
	 * This function utilizes the Wordpress core update_option() function to add each of the options
    * specified in the plugin's option array to the Wordpress options database. This function verifies
    * that the specified options have not been previously added to the database to prevent overwriting
    * stored configuration values.
	 *
	 * @param void      None.
	 * 
    * @return void     None.  	 
	 * 
	 * @access private  Access via register_activation_hook() callback only.
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function _RegisterPluginOptions()
   {
      if( is_array( $this->_pluginOptionsArray ) )
      {
         global $wpdb;
         
         $registeredOptions = $wpdb->get_results( "SELECT * FROM $wpdb->options ORDER BY option_name" );
         
         foreach( $this->_pluginOptionsArray AS $optionKey => $optionValue )
         {
            // Only update the option value if the option has not been previously added to the database.
            $optionFound = false;
            foreach( (array) $registeredOptions AS $registeredOption )
            {
               $registeredOption->option_name = attribute_escape( $registeredOption->option_name );
               if( $optionKey == $registeredOption->option_name )
               {
                  $optionFound = true;
               }
            }
            
            if( $optionFound == false )
            {
               update_option( $optionKey, $optionValue[$this->OPTION_INDEX_VALUE] );
            }
			}
      }
   }

   /**
	 * _UnregisterPluginOptions() - Removes the plugin's options from the Wordpress options database.
	 *
	 * This function utilizes the Wordpress core delete_option() function to remove each of the options
    * specified in the plugin's option array from the Wordpress options database.
	 *
	 * @param void      None.
	 * 
    * @return void     None.  	 
	 * 
	 * @access private
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function _UnregisterPluginOptions()
   {
      if( is_array( $this->_pluginOptionsArray ) )
      {
         foreach( $this->_pluginOptionsArray AS $optionKey => $optionValue )
         {
			   delete_option( $optionKey );
			}
      }
   }
   
   /**
	 * _IsPluginInstalled() - Determines if the plugin is installed.
	 *
	 * This function verifies that the plugin options have been installed in the Wordpress options database and
    * returns "true" if they are and "false" if the are not.
	 *
	 * @param void      None.
	 * 
    * @return bool     $pluginInstalled      Returns "true" if installed and "false" if not.
	 * 
	 * @access private
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function _IsPluginInstalled()
   {
      $pluginInstalled = true;
      
      if( is_array( $this->_pluginOptionsArray ) )
      {
         global $wpdb;
         
         $registeredOptions = $wpdb->get_results( "SELECT * FROM $wpdb->options ORDER BY option_name" );
         
         foreach( $this->_pluginOptionsArray AS $optionKey => $optionValue )
         {
            // Only update the option value if the option has not been previously added to the database.
            $optionFound = false;
            foreach( (array) $registeredOptions AS $registeredOption )
            {
               $registeredOption->option_name = attribute_escape( $registeredOption->option_name );
               if( $optionKey == $registeredOption->option_name )
               {
                  $optionFound = true;
               }
            }
            
            if( $optionFound == false )
            {
               // The plugin is not fully installed so we need to break out of this loop and
               // update the installed flag.
               $pluginInstalled = false;
               break;
            }
			}
      }
      
      return $pluginInstalled;
   }
   
   /**
	 * _UpdatePluginOptions() - Updates the plugin options in the Wordpress database.
	 *
	 * This function retrieves the plugin's options from the _POST[] method and updates the associated
    * options stored within the Wordpress options database.
	 *
	 * @param array     &$requestArray    Reference to the _REQUEST[] array.
	 * 
    * @return void     None.
	 * 
	 * @access private
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function _UpdatePluginOptions( &$requestArray )
   {
      // Update the plugin's options using the values retrieved from the POST method.
      foreach( $this->_pluginOptionsArray AS $optionKey => $optionValueArray )
      {
         update_option( $optionKey, $requestArray[$optionKey] );
      }
      
      // Now display to the user that the plugins have been updated.
      $updatedMessage = '<p>The "' . $this->_pluginTitle . '" plugin options have been updated in the database.</p>';
      $this->_DisplayFadingMessageBox( $updatedMessage );
   }
   
   /**
	 * _ResetPluginOptions() - Resets the plugin options in the Wordpress database.
	 *
	 * This function retrieves the plugin's default options from the options array and updates the associated
    * options stored within the Wordpress options database.
	 *
	 * @param void      None.
	 * 
    * @return void     None.
	 * 
	 * @access private
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function _ResetPluginOptions()
   {
      // Update the plugin's options using the default values from the options array.
      foreach( $this->_pluginOptionsArray AS $optionKey => $optionValueArray )
      {
         update_option( $optionKey, $optionValueArray[$this->OPTION_INDEX_VALUE] );
      }
      
      // Now display to the user that the plugins have been reset to default values.
      $resetMessage = '<p>The "' . $this->_pluginTitle . '" plugin options have been reset to default values in the database.</p>';
      $this->_DisplayFadingMessageBox( $resetMessage );
   }

   /**
	 * GetOptionValue() - Retrieves the option value for the specified option ID.
	 *
	 * This function retrieves the option value for the specified option ID from the Wordpress options database.
	 *
	 * @param string    $optionName       Name of the option whose value you are attempting to retrieve.
	 * 
    * @return mixed    $optionValue      Value of the requested option or "OPTION_PARAMETER_NOT_FOUND".
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function GetOptionValue( $optionName )
   {
      $optionValue = get_option( $optionName );
    
      return $optionValue;
   }

   /**
	 * GetOptionType() - Retrieves the option type for the specified option ID.
	 *
	 * This function retrieves the option type for the specified option ID from the plugin's option array.
	 * This option parameter is not stored in the Wordpress options database so it is only accessible via the
	 * plugin's options array.
	 *
	 * @param string    $optionName       Name of the option whose value you are attempting to retrieve.
	 * 
    * @return string   $optionType       Type of the requested option or "OPTION_PARAMETER_NOT_FOUND".
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function GetOptionType( $optionName )
   {
      $optionDescription = $this->OPTION_PARAMETER_NOT_FOUND;
      
      if( array_key_exists( $optionName, $this->_pluginOptionsArray ) )
      {
         $optionDescription = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_TYPE];
      }
    
      return $optionDescription;
   }

   /**
	 * GetOptionDescription() - Retrieves the option description for the specified option ID.
	 *
	 * This function retrieves the option description for the specified option ID from the plugin's option array.
	 * This option parameter is not stored in the Wordpress options database so it is only accessible via the
	 * plugin's options array.
	 *
	 * @param string    $optionName           Name of the option whose value you are attempting to retrieve.
	 * 
    * @return string   $optionDescription    Description of the requested option or "OPTION_PARAMETER_NOT_FOUND".
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function GetOptionDescription( $optionName )
   {
      $optionDescription = $this->OPTION_PARAMETER_NOT_FOUND;
      
      if( array_key_exists( $optionName, $this->_pluginOptionsArray ) )
      {
         $optionDescription = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION];
      }
    
      return $optionDescription;
   }
   
   /**
	 * GetOptionValuesArray() - Retrieves the option values array for the specified option ID.
	 *
	 * This function retrieves the option values array for the specified option ID from the plugin's option array.
	 * This option parameter is not stored in the Wordpress options database so it is only accessible via the
	 * plugin's options array.
	 *
	 * @param string    $optionName           Name of the option whose value you are attempting to retrieve.
	 * 
    * @return string   $optionValuesList     Comma-delimited list of the option values array.
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function GetOptionValuesArray( $optionName )
   {
      $optionValuesList = $this->OPTION_PARAMETER_NOT_FOUND;
      
      if( array_key_exists( $optionName, $this->_pluginOptionsArray ) )
      {
         $optionValues = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_VALUES_ARRAY];
         if( is_array( $optionValues ) )
         {
            $optionValuesList = '';
            
            foreach( $optionValues AS $optionValue )
            {
               $optionValuesList .= ',' . $optionValue;
            }
            
            $optionValuesList = trim( $optionValuesList, ',' );
         }
         else
         {
            $optionValuesList = $optionValues;
         }
      }
    
      return $optionValuesList;
   }

   /**
	 * DisplayPluginOption() - Displays the plugin's specified option.
	 *
	 * This function generates the markup required to display the specified option and displays it on the
    * plugin's administration page via the echo() function.
	 *
	 * @param string    $optionName           Name of the option to display.
	 * 
    * @return void     None.
	 * 
	 * @access public
    * @since {WP 2.3}
	 * @author Keith Huster
	 */
   function DisplayPluginOption( $optionName )
   {
      $optionMarkup = '';
    
      if( array_key_exists( $optionName, $this->_pluginOptionsArray ) )
      {
         switch( $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_TYPE] )
         {
            case $this->OPTION_TYPE_TEXTBOX:
               // Generate the markup required to display an XHTML compliant textbox.
               $optionMarkup = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . ' ';
               $optionMarkup .= '<input type="text" name="' . $optionName . '" value="' . get_option( $optionName ) . '" /> ';
               break;
            case $this->OPTION_TYPE_TEXTAREA:
               // Generate the markup required to display an XHTML compliant textarea.
               $optionMarkup = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . '<br />';
               $optionMarkup .= '<textarea name="' . $optionName . '" cols="50" rows="10">' . get_option( $optionName ) . '</textarea> ';
               break;
            case $this->OPTION_TYPE_CHECKBOX:
               // Generate the markup required to display an XHTML compliant checkbox.
               $checkBoxValue = ( get_option( $optionName ) == true ) ? 'checked="checked"' : '';
		         $optionMarkup .= '<input type="checkbox" name="' . $optionName . '" ' . $checkBoxValue . ' /> ';
			      $optionMarkup .= $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION];
               break;
            case $this->OPTION_TYPE_RADIOBUTTONS:
               // Split the comma delimited option description and values for the radio buttons.
               $optionIdCount = 0;
               $optionMarkup = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . '<br />';
               $valuesArray = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_VALUES_ARRAY];
               if( is_array( $valuesArray ) )
               {
                  // Loop through each of the comma delimited values to process the radiobuttons.
                  foreach( $valuesArray AS $valueName )
                  {
                     // The rest of the parameters are the values for each of the radio buttons so we can
                     // generate the markup required to display an XHTML compliant set of radio buttons.
                     $selectedValue = ( get_option( $optionName ) == $valueName ) ? 'checked="checked"' : '';
                     $optionMarkup .= '<input type="radio" name="' . $optionName . '" id="' . $optionName . $optionIdCount . '" value="' . $valueName . '" ' . $selectedValue . ' /> ';
                     $optionMarkup .= $valueName;
                     $optionMarkup .= '<br />';
                     
                     // Finally increment the option ID value so that the next radiobutton will have
                     // and ID field of 1 greater than the previous radiobutton.
                     $optionIdCount++;
                  }
               }
               break;
            case $this->OPTION_TYPE_PASSWORDBOX:
               // Generate the markup required to display an XHTML compliant passwordbox.
               $optionMarkup = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . ' ';
               $optionMarkup .= '<input type="password" name="' . $optionName . '" value="' . get_option( $optionName ) . '" /> ';
               break;
            case $this->OPTION_TYPE_COMBOBOX:
               // Split the comma delimited option description and values for the combobox.
               $optionIdCount = 0;
               $optionMarkup = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . ' <select name="' . $optionName . '" >';
               $valuesArray = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_VALUES_ARRAY];
               if( is_array( $valuesArray ) )
               {
                  // Loop through each of the comma delimited values to process the combobox values.
                  foreach( $valuesArray AS $valueName )
                  {
                     // The rest of the parameters are the values for each of the combobox options so we can
                     // generate the markup required to display an XHTML compliant combobox.
                     $selectedValue = ( get_option( $optionName ) == $valueName ) ? 'selected="selected"' : '';
                     $optionMarkup .= '<option label="' . $valueName . '" ' . $selectedValue . ' >' . $valueName . '</option>';
                     $optionMarkup .= '<br />';
                     
                     // Finally increment the option ID value so that the next radiobutton will have
                     // and ID field of 1 greater than the previous radiobutton.
                     $optionIdCount++;
                  }
                  
                  $optionMarkup .= '</select>';
               }
               break;
            case $this->OPTION_TYPE_FILEBROWSER:
               // Generate the markup required to display an XHTML compliant file input box.
               $optionMarkup = $this->_pluginOptionsArray[$optionName][$this->OPTION_INDEX_DESCRIPTION] . ' ';
               $optionMarkup .= '<input type="file" name="' . $optionName . '" /> ';
               break;
            case $this->OPTION_TYPE_HIDDEN:
               // Generate the markup required to display an XHTML compliant hidden input box.
               $optionMarkup .= '<input type="hidden" name="' . $optionName . '" value="' . get_option( $optionName ) . '" /> ';
               break;
            default:
               // Simply return nothing.
               $optionMarkup = '';
               break;
         }
      }
    
      echo( $optionMarkup );
   }
}

?>
