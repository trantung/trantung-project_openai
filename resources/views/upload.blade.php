<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and Convert Video</title>
</head>
<body>
    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
            <br>
            <a href="{{ session('hlsUrl') }}" target="_blank">View Converted Video</a>
        </div>
    @endif

    @if (session('error'))
        <div style="color: red;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('video.convert12345') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="video">Choose MP4 file:</label>
            <input type="file" name="video" id="video" accept="video/mp4" required>
        </div>
        <div>
            <button type="submit">Upload and Convert</button>
        </div>
    </form>
</body>
</html>
