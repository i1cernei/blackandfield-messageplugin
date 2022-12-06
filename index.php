<?php
/*
Plugin Name: Visitor Messages
Plugin URI: http://www.blackandfield.com
Description: Declares a plugin that will allow storage and retrival based on minimal API of values of an input field.
Version: 1.0
Author: Ioan O. Cernei
Author URI: http://ioan.cernei.ro
License: C
*/

// Find or create our table and other resources

function create_resources() {
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();

  $table_name = $wpdb->prefix.'bf_messages';
  $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

  if($wpdb->get_var( "show tables like '$table_name'" ) != $table_name )  {
      // go go
      $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL auto_increment,
        message varchar(1000) NOT NULL,
        UNIQUE KEY id (id)
      ) $charset_collate;";
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      // var_dump(ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );
      add_option( 'message_db_version', $message_db_version );
  
      $welcome_text = 'Congratulations, you just completed the installation!';

      $wpdb->insert( 
        $table_name, 
        array( 
          'message' => $welcome_text, 
        ) 
      );
    }

}

// register resources activation hook
register_activation_hook( __FILE__, 'create_resources' );


// create basic router for our API endpoints
function router() {

   // Declare GET INDEX endpoint for retrieving all messages
  register_rest_route( 'messages/v1', '/all', array(
      'methods' => 'GET',
      'callback' => 'index',
  ) );

  // Declare SINGLE MESSAGE GET endpoint for retrieving single messages by ID

    register_rest_route( 'messages/v1', '/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'find',
  ) );

      register_rest_route( 'messages/v1', '/create', array(
        'methods' => 'POST',
        'callback' => 'create',
    ) );

    register_rest_route( 'messages/v1', '/delete', array(
      'methods' => 'PUT',
      'callback' => 'delete',
  ) );

}


// CRUD

//Function to be used in api for retrieving all messages
function index() {

  global $wpdb;

  $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bf_messages", OBJECT );

  if (empty($results)) {
    return null;
  }

  return $results;

}

function delete($req) {

  global $wpdb;
  $table_name = $wpdb->prefix.'bf_messages';
  $msgid = $req['msg_id'];

  // $results = $wpdb->get_results( "DELETE * FROM {$wpdb->prefix}bf_messages WHERE id={$req['id']}", OBJECT );

  try {
    $wpdb->delete($table_name, array('id' => $msgid));
    $response['message'] = 'Removed message with id: ' . $msgid;
    $resp = new WP_REST_Response($response);
    $resp->set_status(200);
    
    return ['req' => $resp];

  } catch (Exception $e) {
    $response['error'] = 'Failed to delete message with id: ' . $msgid;
    $resp = new WP_REST_Response($response);
    $resp->set_status(500);

    return ['req' => $resp];
  }


  

}

//Function to be used in api for retrieving single message
function find( $req ) {
    global $wpdb;

    $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bf_messages WHERE id={$req['id']}", OBJECT );

    if (empty($results)) {
      return null;
    }
  
    return $results;
}


//Function to be used in api for creating single message
function create( $req ) {
    global $wpdb;

    $params = $req->get_params();
    $mes = $params['message'] || $req['message'];

    $table_name = $wpdb->prefix.'bf_messages';

    try {
      $wpdb->insert( 
        $table_name, 
        array( 
          'message' => $req['message'], 
        ) 
      );

      $response['message'] = $params['message'];
      $response['status'] = 'Success ! Added message to db.';

      $res = new WP_REST_Response($response);
      $res->set_status(200);

      return ['req' => $res];

    } catch (Exception $e) {
      // echo 'Caught exception: ',  $e->getMessage(), "\n";
      $response['error'] = $e->getMessage();
      $res = new WP_REST_Response($response);
      $res->set_status(500);

      return ['req' => $res];
    } 
}



add_action( 'rest_api_init', 'router' );

// add_action( 'rest_api_init', function () {
//     register_rest_route( 'visitor_messages/v1', '/create', array(
//         'methods' => 'POST',
//         'callback' => 'create',
//     ) );
//     } );

