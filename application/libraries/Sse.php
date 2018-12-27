<?php
    // require_once('/path/to/vendor/autoload.php'); //Load with ClassLoader
    
	use Sse\Event;
    use Sse\SSE;
    
        
	//create the event handler
	class emergency_message implements Event {
		public function update(){
            //Here's the place to send data
            $array = array('name'=>'rafay','contact'=>'03147938798');
            // if($array){
            //     return true;
            // }else{
            //     return false;
            // }
			 return json_encode($array);
		}
		
		public function check(){
			//Here's the place to check when the data needs update
			return true;
		}
	}
    $sse = new SSE(); //create a libSSE instance
    // $sse->allow_cors = true;
	$sse->addEventListener('event_name', new emergency_message());//register your event handler
    $sse->start();//start the event loop

	?>