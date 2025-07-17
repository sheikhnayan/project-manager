<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Your Password?</h2>
    @if (session('status'))
        <div style="color: green;">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" required>
        @error('email') <div style="color: red;">{{ $message }}</div> @enderror
        <button type="submit">Send Password Reset Link</button>
    </form>
    <a href="{{ route('login') }}">Back to login</a>
</body>
</html>
