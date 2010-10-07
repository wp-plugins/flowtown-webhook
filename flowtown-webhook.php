<?php
/**
 * Flowtown Webhook
 * 
 * Automatically send a user to a Flowtown group when they sign up
 * 
 * @package dtLabs
 * 
 * @author digital-telepathy
 * @version 1.1
 */
/*
Plugin Name: Flowtown Webhook
Plugin URI: http://www.dtelepathy.com/
Description: Automatically send a user to a Flowtown group when they sign up
Version: 1.1
Author: digital-telepathy
Author URI: http://www.dtelepathy.com
License: GPL2

Copyright 2010 digital-telepathy  (email : support@digital-telepathy.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class FlowtownWebhook {
    var $version = 1.0;
    var $namespace = 'flowtown';
    var $longname = 'Flowtown Webhook';
    
    function __construct() {
        $this->url_option_name = $this->namespace . '_url';
        $this->handshake_option_name = $this->namespace . '_handshake';
        $this->send_commenters_option_name = $this->namespace . '_send_commenters';
        
        $this->url = get_option( $this->url_option_name, false );
        $this->handshake = get_option( $this->handshake_option_name, false );
        $this->send_commenters = get_option( $this->send_commenters_option_name, false );
        
        add_action( 'user_register', array( &$this, 'user_register' ), 10, 1 );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'plugin_action_links_' . basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array( &$this, 'plugin_settings' ), 10, 4 );
        add_action( 'wp_insert_comment', array( &$this, 'wp_insert_comment' ), 10, 2 );
    }
    
    function admin_menu() {
        add_options_page( $this->longname, $this->longname, 'administrator', $this->namespace, array( &$this, 'admin_options_page' ) );
    }
    
    function admin_options_page() {
        if( !current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to access this page' );
        }
        
        $nonce_action = $this->namespace . '_update';
        $nonce_field = $this->namespace . '_nonce';
        
        if( isset( $_POST ) && !empty( $_POST ) ) {
            if( wp_verify_nonce( $_REQUEST[$nonce_field], $nonce_action ) ) {
                update_option( $this->url_option_name, $_POST[$this->url_option_name] );
                update_option( $this->handshake_option_name, $_POST[$this->handshake_option_name] );
                update_option( $this->send_commenters_option_name, $_POST[$this->send_commenters_option_name] );
                
                $this->url = get_option( $this->url_option_name, false );
                $this->handshake = get_option( $this->handshake_option_name, false );
                $this->send_commenters = get_option( $this->send_commenters_option_name, false );
                
                $show_message = true;
            }
        }
        
        ?>
        <div class="wrap">
            <h2><?php echo $this->longname; ?> Options</h2>
            <p>Please set the following options to begin automatically sending user signups to your Flowtown account. You can find these options by logging into your Flowtown account, selecting a group and going to <em>Group Settings</em>:</p>
            
            <?php if( $show_message === true ): ?>
            
                <div class="updated fade">
                    <p><strong><?php echo $this->longname; ?> options succesfully updated!</strong> New users will automatically be added to the group associated with the Webhook URL you have provided.</p>
                </div>
            
            <?php endif; ?>
            
            <form action="" method="post">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">Webhook URL</th>
                            <td><input type="text" name="<?php echo $this->url_option_name; ?>" value="<?php echo $this->url; ?>" size="60" maxlength="255" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Webhook Handshake Key</th>
                            <td><input type="text" name="<?php echo $this->handshake_option_name; ?>" value="<?php echo $this->handshake; ?>" size="60" maxlength="40" /></td>
                        </tr>
                        <tr>
                            <th scope="row">Blog Comments</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="<?php echo $this->send_commenters_option_name; ?>" value="1"<?php echo $this->send_commenters == '1' ? ' checked="checked"' : ''; ?> />
                                    Send blog comment authors to this Flowtown group
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <?php wp_nonce_field( $nonce_action, $nonce_field ); ?>
                    <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
                </p>
            </form>
        </div>
        <?php
    }
    
    function plugin_settings( $links ) {
		$settings_link = '<a href="' . admin_url( 'options-general.php' ) . '?page=' . $this->namespace . '">' . __( 'Settings', $this->namespace ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
    }
    
    function user_register( $user_ID ) {
        if( $this->url === false || $this->handshake === false ) {
            return false;
        }
        
        $user = get_userdata( $user_ID );
        
        wp_remote_fopen( $this->url . '?api_key=' . $this->handshake . '&emails=' . $user->user_email );
    }
    
    function wp_insert_comment( $id, $comment ) {
        if( $this->send_commenters == '1' ) {
            if( $comment->comment_approved != 'spam' ) {
                wp_remote_fopen( $this->url . '?api_key=' . $this->handshake . '&emails=' . $comment->comment_author_email );
            }
        }
    }
}

add_action( 'init', 'FlowtownWebhook' );
function FlowtownWebhook() {
    global $FlowtownWebhook;

    $FlowtownWebhook = new FlowtownWebhook();
}