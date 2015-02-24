<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::post('/instagram/webhook', 'InstagramController@webhook');
//Route::get('/instagram/webhook', 'InstagramController@webhook');
   
Route::get('/instagram/show', 'InstagramController@show');
Route::get('/instagram/json/{tag}', 'InstagramController@json');

Route::get('/', function()
{
	
    $instagram = new Andreyco\Instagram\Client(array(
      'apiKey'      => '318c61b8173a40c6b41a46c1e16a3e9a',
      'apiSecret'   => '169cb12f86e24092881d6a7b53144f4d',
      'apiCallback' => '',
      'scope'       => array('basic'),
    ));

  	$data = $instagram->getTag("pictureline");

    //return Response::json($data);
	return "nothing to see here";
});


Route::get('/test', function()
{
    $haystack= array(
        'skiUtahs',
        'clickMag',
        'apple'
    );

    $needles = array(
        'skiUtah',
        'clickMag',
        'redrock'
    );


    foreach ($needles as $needle) {
    //$needle = "SKIUTAH";
   
        if (in_array(strtolower($needle), array_map('strtolower', $haystack))) {

        ///Write code
        }
    }
    
});
/*
Route::get('/subscribe', function()
{
    //Change Route::post('/instagram/webhook', 'InstagramController@webhook'); to get and add if (isset ($_GET['hub_challenge'])){ echo $_GET['hub_challenge'];}
    $client_id = '318c61b8173a40c6b41a46c1e16a3e9a';
    $client_secret = '169cb12f86e24092881d6a7b53144f4d';
    //$redirect_uri = 'http://requestb.in/11bg1551';
    $redirect_uri = 'http://104.236.140.80/index.php/instagram/webhook';
    $apiData = array(
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'aspect' => "media",
        'object' => "tag",
        'object_id' => "pictureline",
        'callback_url' => $redirect_uri
    );

    $apiHost = 'https://api.instagram.com/v1/subscriptions/';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiHost);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $jsonData = curl_exec($ch);
    curl_close($ch);
    //return Response::json($jsonData);
});
*/


