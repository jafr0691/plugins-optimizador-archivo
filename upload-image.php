<?php
/*
Plugin Name: reducir peso img
Plugin URI:
description: reduccion de subida de imagenes
Version: 1.0
Author: Jesus Farias
Author URI:
License: GPL2
*/
defined('ABSPATH') or die('No script please!');
define('DocReducc', plugin_dir_path(__FILE__));
define('ArcReducc', plugin_dir_url(__FILE__));
define('Basedir', wp_get_upload_dir()['basedir']);
define('Baseurl', wp_get_upload_dir()['baseurl']);

require_once  DocReducc.'plugin-update-checker/plugin-update-checker.php';

$myUpdateChecker = Puc_v4_Factory :: buildUpdateChecker (
	 'http://jesusf.qalanet.com/details.json' ,
	__FILE__,'reducir_peso_img' 
);

// function sqlinforeduc()
// {
//     require_once DocReducc . 'app/infobtnreduc.php';
// }
// function control_jquery_reduccion()
// {
//     wp_enqueue_script( 'script_reducc', plugin_dir_url( __FILE__ ).'/js/reduccion.js', array( 'media-editor', 'media-views' ));
//     wp_enqueue_script('script_reducc');
//     wp_localize_script('script_reducc', 'sqlinforeduc', ['reducajax' => admin_url('admin-ajax.php')]);
// }
// add_action('admin_enqueue_scripts', 'control_jquery_reduccion');
// add_action('wp_ajax_sqlinforeduc', 'sqlinforeduc');
// add_action('wp_ajax_nopriv_sqlinforeduc', 'sqlinforeduc');

// add_action( 'restrict_manage_posts', 'add_reducc');

// function add_reducc() {
// 	$scr = get_current_screen();

// 	if ( 'upload' !== $scr->base ) {
// 		return;
// 	}

// 	echo '<button id="reduclist" style="padding: 0; margin-right: 0.4em;" class="button media-button"><img src="'.ArcReducc.'img/carga.gif" style="margin: 0;vertical-align:middle;" width="40" height="20"></button>';
// }










add_action('add_attachment', 'comprimir_img'); 
function comprimir_img($id)
{
    
    comprimir_img1($id);
    
    function comprimir_img1($id)
    {
        define('WEBSERVICE', 'http://api.resmush.it/ws.php?img=');
        if(wp_attachment_is_image($id)){
            $sizes = wp_get_attachment_metadata($id);;
            $url = array();
            $i=0;
            if(!empty($sizes)){
                foreach ($sizes as $nombre => $xy) {
                    $url[$i] = wp_get_attachment_image_url( $id,$nombre, false );
                    $i++;
                }
            }else{
                comprimir_img2($id);
            }
            $urlorigi = wp_get_attachment_image_url( $id, null, false );
            $url[$i] = $urlorigi;
            
            foreach ($url as $compri) {
                $path = str_replace(Baseurl,Basedir, $compri);
                $o = json_decode(file_get_contents(WEBSERVICE .$compri.'&qlty=80'));
                if (!isset($o->error)) {
                    copy($o->dest, $path);
                }
            }
        }
    }
    function comprimir_img2($id)
    {
        define('WEBSERVICE', 'http://api.resmush.it/ws.php?img=');
        if(wp_attachment_is_image($id)){
            $sizes = wp_get_attachment_metadata($id);;
            $url = array();
            $i=0;
            if(!empty($sizes)){
                foreach ($sizes as $nombre => $xy) {
                    $url[$i] = wp_get_attachment_image_url( $id,$nombre, false );
                    $i++;
                }
            }else{
                comprimir_img1($id);
            }
            $urlorigi = wp_get_attachment_image_url( $id, null, false );
            $url[$i] = $urlorigi;
            
            foreach ($url as $compri) {
                $path = str_replace(Baseurl,Basedir, $compri);
                $o = json_decode(file_get_contents(WEBSERVICE .$compri.'&qlty=80'));
                if (!isset($o->error)) {
                    copy($o->dest, $path);
                }
            }
        }
    }
}

add_image_size( 'custom-size-jesus', 220, 180, true );






// add_action('wp_footer', 'ver'); 
// function ver()
// {
    // $id= 64;
    
    // echo var_dump(get_post_meta( $id, '_wp_attachment_metadata', true ));
    // $sizes = wp_get_attachment_metadata($id);
    // echo var_dump($sizes);
    // echo wp_get_attachment_image_url( $id, null, false );
    //     $i=0;
        // if(!empty($sizes)){
        //     $sizes['']
        //     echo wp_get_attachment_image_url( $id,$nombre, false ).'<br>';
        //     foreach ($sizes['sizes'] as $nombre => $xy) {
        //         echo '<br>';
        //         echo $nombre.'<br>';
        //         echo wp_get_attachment_image_url( $id,$nombre, false ).'<br>';
        //         $i++;
        //     }
        // }
        
        // $sizes = wp_get_attachment_metadata(54564);
    
        
       
        
        
    
    // define('WEBSERVICE', 'http://api.resmush.it/ws.php?img=');
    // if(wp_attachment_is_image($id)){
    //     $sizes = wp_get_registered_image_subsizes();
    //     $url = array();
    //     $i=0;
    //     if(!empty($sizes)){
    //         foreach ($sizes as $nombre => $xy) {
    //             $url[$i] = wp_get_attachment_image_url( $id,$nombre, false );
    //             $i++;
    //         }
    //     }
        
    //     $urlfull = wp_get_attachment_image_url( $id,'full', false );
    //     $url[$i] = $urlfull;
        
        
    //     foreach ($url as $compri) {
    //         $path = str_replace(Baseurl,Basedir, $compri);
    //         $o = json_decode(file_get_contents(WEBSERVICE .$compri.'&qlty=80'));
    //         if (!isset($o->error)) {
    //             echo $o->dest.'<br>';
    //             // copy($o->dest, $path);
    //         }
    //     }
    // }

// }

// add_action('wp_update_attachment_metadata', 'att');

// add_action('wp_get_attachment_metadata', 'att');

// function att($id){
//     define('WEBSERVICE', 'http://api.resmush.it/ws.php?img=');
//     if(wp_attachment_is_image($id)){
//         $sizes = wp_get_registered_image_subsizes();
//         $url = array();
//         $i=0;
//         if(!empty($sizes)){
//             foreach ($sizes as $nombre => $xy) {
//                 $url[$i] = wp_get_attachment_image_url( $id,$nombre, false );
//                 $i++;
//             }
//         }
        
//         $urlfull = wp_get_attachment_image_url( $id,'full', false );
//         $url[$i] = $urlfull;
        
        
//         foreach ($url as $compri) {
//             $path = str_replace(Baseurl,Basedir, $compri);
//             $o = json_decode(file_get_contents(WEBSERVICE .$compri.'&qlty=80'));
//             if (!isset($o->error)) {
//                 copy($o->dest, $path);
//             }
//         }
//     }
// }












