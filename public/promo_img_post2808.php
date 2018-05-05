<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Upload Promotional Images</title>
        <link rel="stylesheet" href="http://yegor256.github.io/tacit/tacit.min.css">
    </head>
    <body>
        <h2>Upload Promotional Images</h2> 
        <form method="POST" action="http://35.167.52.234/api/uploadPromotionImages" enctype="multipart/form-data">
            <label>Event Id:</label>
            <input type="text" name="event_id"><br />
            <label>Select file to upload:</label><br />
            <input type="file" name="promotionaimages_1" ><br />
            <input type="file" name="promotionaimages_2" ><br />
            <input type="file" name="promotionaimages_3" ><br />
            <button type="submit">Upload</button>
        </form>
    </body>
</html>