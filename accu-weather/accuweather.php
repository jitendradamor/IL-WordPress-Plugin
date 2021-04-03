<?php
/**
 * Plugin Name: Accu Weather
 * Plugin URI:  
 * Description: AccuWeather plugin for demo purpose.
 * Version: 1.0
 * Author: Jitendra Damor
 * Author URI: 
 */   


/* Start of Registering the setting for weather API */
function accuweather_register_settings() {
    register_setting( 'accuweather_options_group', 'accuweather_api', 'accuweather_callback' );
}
add_action( 'admin_init', 'accuweather_register_settings' );
/* End of Registering the setting for weather API */


/* Start of Registering the Page Title for weather API */
function accuweather_register_options_page() {
    add_options_page('Page Title', 'Accu Weather', 'manage_options', 'accuweather', 'accuweather_options_page');
}
add_action('admin_menu', 'accuweather_register_options_page');
/* End of Registering the Page Title for weather API */
  


/* Start of Admin Page for Weather */
function accuweather_options_page() { ?>
    <div>
        <?php screen_icon(); ?>
        <h2>Accu Weather API Key Settings</h2>
        <?php 
        $api_key =  get_option('accuweather_api');
        ?>
        <form method="post" action="options.php">
            <?php settings_fields( 'accuweather_options_group' ); ?>  
            <table class="form-table" role="presentation">
                <tr valign="top">
                    <th scope="row"><label for="accuweather_api">API Key</label></th>
                    <td><input type="text" id="accuweather_api" name="accuweather_api" class="regular-text" value="<?php echo $api_key; ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
   </div>
 <?php
} 
/* End of Admin Page for Weather */

// get api data
$api_key =  get_option('accuweather_api');
$handle = curl_init();	
$url = "http://dataservice.accuweather.com/forecasts/v1/daily/5day/227342?apikey=".$api_key."&metric=true";

// Set the url
curl_setopt($handle, CURLOPT_URL, $url);
// Set the result output to be a string.
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($handle);

$output = json_decode($output,true);

curl_close($handle); 

global $cities_db_version;
$cities_db_version = '1.0';

/*
<div class="dailyforecasts">
    <?php 
        $dailydata = $output["DailyForecasts"];
        $threedaysdata = array_slice($dailydata, 0, 3);
        foreach($threedaysdata as $key => $value) { 
            $img_icon = $value['Day']['Icon']; 
            $phrase = $value['Day']['IconPhrase']; 
            $precipitationtype = $value['Day']['PrecipitationType']; 
            $precipitationintensity = $value['Day']['PrecipitationIntensity']; 

            $min_temp = $value['Temperature']['Minimum']['Value']; 
            $max_temp = $value['Temperature']['Maximum']['Value']; 
            $tempunit = $value['Temperature']['Maximum']['Unit'];
            ?>
            <div class="daily-weather">
                <div class="temperature">								
                    <img alt="<?php echo $phrase; ?>" src="https://www.accuweather.com/images/weathericons/<?php echo $img_icon;  ?>.svg"> 
                    <span class="temp"><?php  echo $min_temp .' &#8451; - '. $max_temp .' &#8451; '; ?></span>
                </div>
                <div class="temp-detail"><span class="phrase"><?php echo $phrase; ?></span><div class="date"> <?php echo $date = date('jS F Y', strtotime($value['Date'])); ?> </div></div>  
            </div>   
    <?php } ?>
</div> 
*/


function cities_install() {
    global $wpdb;
    global $cities_db_version;

    $table_name = $wpdb->prefix . 'cities';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        entry_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        id mediumint(9) NOT NULL,
        status TEXT NOT NULL,
        published TEXT NOT NULL,
        last_updated DATE NOT NULL, 
        cities_id INT(6) NOT NULL,
        cities_name TINYTEXT NOT NULL,
        reg_date TIMESTAMP
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'cities_db_version', $cities_db_version );
}

 function cities_install_data() {

     global $wpdb;

    $table_name = $wpdb->prefix . 'cities';
    $citieshandle = curl_init();	
    $cities_url = 'http://dataservice.accuweather.com/locations/v1/cities/autocomplete?apikey='.$api_key.'&q=   '; 

    /* Start of Making the City Dynamic for weather API */
   // $citiesjson = file_get_contents($cities_url);
    //$citiescontent = json_decode($citiesjson, true);

    // Set the url
    // curl_setopt($citieshandle, CURLOPT_URL, $cities_url);
    // // Set the result output to be a string.
    // curl_setopt($citieshandle, CURLOPT_RETURNTRANSFER, true);

    // $citiesoutput = curl_exec($citieshandle);

    // $citiesoutput = json_decode($citiesoutput,true);
    // echo '<pre>'; 
    // print_r($citiesoutput);
    // echo '</pre>';
    // curl_close($citieshandle);



    // foreach ($citiescontent->matches as $match) {
    //         $wpdb->insert( $table_name, $match);
    // }
    /* End of Making the City Dynamic for weather API */
 }

 function cities_uninstall() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'cities';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
    delete_option("cities_db_version");
}

register_activation_hook( __FILE__, 'cities_install' );
register_activation_hook( __FILE__, 'cities_install_data' );
register_uninstall_hook( __FILE__, 'cities_uninstall' );
?>