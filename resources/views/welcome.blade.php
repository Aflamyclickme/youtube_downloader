<!DOCTYPE html>
<html>
<head>
    <title>Youtube Downloader</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container">
    <h1>Download YouTube Video</h1>
    <form action="/download" method="POST">
        @csrf
        <div class="form-group">
            <label for="url">Video URL:</label>
            <input type="text" class="form-control" id="url" name="url" required>
        </div>
        <button type="submit" class="btn btn-primary">Download</button>
    </form>
</div>
</body>
</html>
