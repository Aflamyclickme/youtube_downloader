<!DOCTYPE html>
<html>
<head>
    <title>Youtube Downloader</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container">
    <h1>Download YouTube Video</h1>
    <form action="{{ route('download') }}" method="POST">
        @csrf
        <label for="url">Video URL:</label>
        <input type="text" id="url" name="url" required><br><br>

        <label for="quality">Select Quality:</label>
        <select name="quality" id="quality">
            <option value="best">Best</option>
            <option value="worst">Worst</option>
            <!-- Add more quality options here if needed -->
        </select><br><br>

        <button type="submit">Download Video</button>
    </form>
</div>
</body>
</html>
