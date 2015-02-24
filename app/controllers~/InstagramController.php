<?php

class InstagramController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showId($order_id)
	{
		//$photos = Photo::all();
		//$photos = Photo::orderBy('id', 'DESC')->get();
		//$photos = DB::table('photos')->where('subscription_id', $order_id)->orderBy('id', 'DESC')->paginate(2);
		return $order_id;
	}
	public function show()
	{
		//$photos = Photo::all();
		//$photos = Photo::orderBy('id', 'DESC')->get();
		$photos = DB::table('photos')->where('subscription_id', '15922931')->orderBy('id', 'DESC')->paginate(2);
		return View::make('instagram.index', ['photos' =>$photos]);
	}
	public function json()
	{
		//$photos = Photo::all();
		$photos = Photo::where('subscription_id', '15922931')->orderBy('id', 'DESC')->get();
		return Response::json($photos)->setCallback(Input::get('callback'));
	}
	public function webhook()
	{
		////////////////Check Source of POST//////////////
		$secret = '169cb12f86e24092881d6a7b53144f4d';
		$tags = array('pictureline', 'skiutah');
		$logData = '';
		$count = '0';
 
		if ( isset( $_SERVER['HTTP_X_HUB_SIGNATURE'] ) ) {
			$igdata = file_get_contents( "php://input" );
			$igarray = json_decode( $igdata );
			$object_id = $igarray[0]->object_id;
			$subscription_id = $igarray[0]->subscription_id;
			if ( hash_hmac( 'sha1', $igdata, $secret) == $_SERVER['HTTP_X_HUB_SIGNATURE'] ) {

				///////////////////Get Contest ID from webhook///////////////////////
				//$content = file_get_contents('php://input');
				//$obj = json_decode($content);
				//$subId = $obj->subscription_id;

				///////////////////get last min from DB//////////////////////////
				//////////////////TODO ASSOCIATE subscription_id////////////////
    			$recentID = DB::table('temp')->where('id', '1')->pluck('min_tag_id');

				

    			////////////////////TODO foreach subscription_id ////////////////////
    			/////GET DATA and Save to DB/////////////	

				///////////////////query instagram for newest photos////////////////
    			$instagram = new Andreyco\Instagram\Client(array(
      				'apiKey'      => '318c61b8173a40c6b41a46c1e16a3e9a',
      				'apiSecret'   => '169cb12f86e24092881d6a7b53144f4d',
      				'apiCallback' => '',
      				'scope'       => array('basic'),
    			));



    			$result = $instagram->getTagMedia("pictureline", $recentID);


				/////////////////////save photo to DB/////////////////////////////
				
    			foreach ($result->data as $media) {
    				

        			if(count(array_intersect($tags, $media->tags)) == count($tags))
        			{

            		//print_r($media->tags);


            		// output media
            			if ($media->type != 'video') {
							++$count;
                			$image = $media->images->low_resolution->url;
                			$username = $media->user->username;
                			$link = $media->link;
                			$id = $media->id;
                			
                			//Check if record exists
                			$photoCheck = Photo::where('url', '=', $image)->first();
							if ($photoCheck == null){
    							$photo = new photo;
								$photo->username = $username;
								$photo->medialink = $link;
								$photo->url = $image;
								$photo->subscription_id	= '15922931';
								$photo->save();
								
							} 
							else {
    							break;
							}
            			}
            			else {
        					break;
        				}
        			}
        			else {
        				break;
        			}
    			}

				/////////////////save new min to DB/////////////////
				DB::table('temp')->where('id', 1)->update(array('min_tag_id' => $result->pagination->min_tag_id));

				//////////////////////logging//////////////////////
				$myString = file_get_contents('php://input');
				$all = $count." Saved Items from WEBHOOK".date("F j, Y, g:i a")." ".$myString."\r\n";
				Log::info($all);
				return "ok";

			}
		}
		else {

		//return "no good";
			$myString = file_get_contents('php://input');
			$all = date("F j, Y, g:i a")." ".$myString."\r\n";
			Log::error($all);
		}	
	}
}
