<!DOCTYPE html>
<html>
<head>
    <title>Expired Link</title>
</head>
<body>
<p>Link Expired : <strong>{{ route('generate-link') . '/' . Auth::user()->uniqueLinks->first()->link }}</strong> end!
    In: <strong>{{ $expirationDate }}</strong></p>
<a href="{{ route('process-registration') }}">Please register again </a>
</body>
</html>
