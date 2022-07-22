<!doctype html5>
<html>
	<head>
		<script type="text/javascript">
		
		String.prototype.endsWith = function(str) {
			return (this.match(str+"$")==str)
		}				

		// Return true if there is any text in the comment field
		function comment_length () {
			if (parent.document.getElementById("comment"))
				return parent.document.getElementById("comment").value.length;
			else if (parent.document.getElementById("comment-p1"))
				return parent.document.getElementById("comment-p1").value.length;
			else
				return parent.document.forms["commentform"].comment.value.length;
		}

		// Return true if comment ends in a new newline
		function comment_ends_newline () {
			if (parent.document.getElementById("comment"))
				return (parent.document.getElementById("comment").value).endsWith("\n");
			else if (parent.document.getElementById("comment-p1"))
				return (parent.document.getElementById("comment-p1").value).endsWith("\n");
			else
				return (parent.document.forms["commentform"].comment.value).endsWith("\n");
		}

		// Write txt to comment field
		function write_comment (text) {
			// Append newline to text (for easier user comment)
			text += "\n";

			// Prepend a linebreak code if it is not the first line 
			// or preceded with \n
			if (comment_length() > 0 && !comment_ends_newline ())
				text = "\n" + text;

			// Attempt to write text to comment field (wherever it may be)
			if (parent.document.getElementById("comment"))
				parent.document.getElementById("comment").value += text;
			else if (parent.document.getElementById("comment-p1"))
				parent.document.getElementById("comment-p1").value += text;
			else
				parent.document.forms["commentform"].comment.value += text;
		}
		</script>
	</head>

	<body>
		<?php
		//JUSTIN FIXED THIS
		require_once ('../../../../wp-blog-header.php');

		// Check referer
		wp_verify_nonce ($_REQUEST ['_wpnonce'], 'ecu_upload_form')
			|| write_js ("alert ('Invalid Referer')")
			|| die ('Invalid referer');
		
		// Get needed info
		$target_dir = ecu_upload_dir_path ();
		$target_url = ecu_upload_dir_url ();
		$images_only = get_option ('ecu_images_only');
		$max_file_size = get_option ('ecu_max_file_size');

		if (!file_exists ($target_dir))
			mkdir ($target_dir);

		$target_path = find_unique_target ($target_dir 
			. basename($_FILES['file']['name']));
		$target_name = basename ($target_path);

		// Debugging message example
//		write_js ("alert ('$target_url')");

		// Default values
		$filecode = "";
		$filelink = "";

		// Detect whether the uploaded file is an image
		$is_image = preg_match ('/(jpeg|png|gif)/i', $_FILES['file']['type']);
		$type = ($is_image) ? "img" : "file";

		if (!$is_image && $images_only) {
			$alert = "Sorry, you can only upload images.";
		} else if (filetype_blacklisted ()) {
			$alert = "You are attempting to upload a file with a disallowed/unsafe filetype!";
		} else if ($max_file_size != 0 && $_FILES['file']['size']/1024 > $max_file_size) {
			$alert = "The file you've uploaded is too big (" 
				. round($_FILES['file']['size']/1024, 1) 
				. "KiB).  Please choose a smaller image and try again.";
		} else if (move_uploaded_file ($_FILES['file']['tmp_name'], $target_path)) {
			$filelink = $target_url . $target_name;
			$filecode = "[$type]$filelink" . "[/$type]";

			// Add the filecode to the comment form
			write_js ("write_comment (\"$filecode\");");

			// Post info below upload form
			write_html_form ("<div class='ecu_preview_file'><a href='$filelink'>$target_name</a><br />$filecode</div>");
			
			if ($is_image) {
				write_html_form ("<a href='$filelink' rel='lightbox[new]'><img class='ecu_preview_img' src='$filelink' /></a><br />");
			}
		} else {
			$alert = "There was an error uploading the file, please try again!";
		}

		if (isset ($alert)) {
			write_js ("alert (\"$alert\");");
		}

		// Check upload against blacklist and return safe unless it matches
		function filetype_blacklisted () {
			return preg_match ("/(\\.(.?html\\d?|php\\d?|f?cgi|htaccess|p(er)?l|py(thon)?|exe|bat|aspx?|sh|js)|^\\.|~$)/i", $_FILES['file']['name']);
		}
		
		// Write script as js to the page
		function write_js ($script) {
			echo "<script type=\"text/javascript\">$script\n</script>\n";
		}
		
		function write_html_form ($html) {
			write_js ("parent.document.getElementById('ecu_preview').innerHTML = \"$html\" + parent.document.getElementById('ecu_preview').innerHTML");
		}
		
		function find_unique_target ($prototype) {
			if (!file_exists ("$prototype")) {
				return $prototype;
			} else {
				$i = 1;
				$prototype_parts = pathinfo ($prototype);
				$ext = $prototype_parts ['extension'];
				$dir = $prototype_parts ['dirname'];
				$name = $prototype_parts ['filename'];
				while (file_exists ("$dir/$name-$i.$ext")) { ++$i; }
				return "$dir/$name-$i.$ext";
			}
		}

		?>
	</body>
</html>
