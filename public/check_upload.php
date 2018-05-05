<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Slim 3</title>
        <link rel="stylesheet" href="http://yegor256.github.io/tacit/tacit.min.css">
    </head>
    <body>
        <h1>Upload a file</h1>
        <form method="POST" action="http://35.167.52.234/api/uploadAvatar" enctype="multipart/form-data">
            <label>User Id:</label>
            <input type="text" name="userid">
            <label>Select file to upload:</label>
            <input type="file" name="avatar">
            <button type="submit">Upload</button>
        </form>
    </body>
</html>