<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Login</title>
    <style>
        .red {
            color: red;
        }
    </style>
</head>

<body>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li class="red">{{ $error }}</li>
            @endforeach
        </ul>
    @endif


    <form action="{{ url('/form') }}" method="post">
        @csrf
        <label for="username">Username:
            <input type="text" name="username" id="username" placeholder="username" value="{{ old('username') }}">
            @error('username')
                <span class="red">{{ $message }}</span>
            @enderror
        </label>
        <br>
        <label for="password">Password:
            <input type="password" name="password" id="password" placeholder="password">
            @error('password')
                <span class="red">{{ $message }}</span>
            @enderror
        </label>
        <br>
        <button type="submit">Login</button>
    </form>
</body>

</html>
