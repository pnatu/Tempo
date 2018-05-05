<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Slim 3</title>
        <link rel="stylesheet" href="http://yegor256.github.io/tacit/tacit.min.css">
    </head>
    <body>
        
        
         <h1>User post to</h1>
         <form method="POST" action="http://35.167.52.234/api/setUserPosts" enctype="multipart/form-data">
            
            <label>post type:</label>
            <select name="posttype" id="posttype2" onchange="myFunction()">
			  <option value="text">Text</option>
			  <option value="image">Image</option>
			  <option value="video">Video</option>
			</select><br/>
			<label>post data:</label>
            <input type="text" name="postdata" id="postdata2"><br/>
			<label>User Id:</label>
            <input type="text" name="userid"><br/>
            <label>Postedto User Id:</label>
            <input type="text" name="postedtouserid"><br/>
            <label>Event Id:</label>
            <input type="text" name="eventid"><br/>
			
            <button type="submit">Submit</button>
        </form>
         
         
         <h1>User post Comments </h1>
         <form method="POST" action="http://35.167.52.234/api/commentOnPost" enctype="multipart/form-data">
           
			<label>Post Id:</label>
            <input type="text" name="postid"><br/>
            <label>COmment type:</label>
            <select name="commenttype" id="posttype3" onchange="myFunction()">
			  <option value="text">Text</option>
			  <option value="image">Image</option>
			  <option value="video">Video</option>
			</select><br/>
			<label>Comment data:</label>
            <input type="text" name="commentdata" id="postdata3"><br/>
			<label>User Id:</label>
            <input type="text" name="userid"><br/>
			
            <button type="submit">Submit</button>
        </form>
		<script type="text/javascript">
		function myFunction(){
	
		 var y = document.getElementById("posttype2").value;	
	if(y == 'text'){
    document.getElementById('postdata2').type = 'text';
	}else{
		document.getElementById('postdata2').type = 'file';
	}
        
        var z = document.getElementById("posttype3").value;	
	if(z == 'text'){
    document.getElementById('postdata3').type = 'text';
	}else{
		document.getElementById('postdata3').type = 'file';
	}
		}
</script>
    </body>
</html>
