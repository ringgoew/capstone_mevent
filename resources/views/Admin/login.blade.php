<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Admin</title>


</head>


<body>


    <div class="login-container">


        <div class="logo">
            Event Management
        </div>


        <div class="subtitle">
            Login Administrator
        </div>


        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif


        @if ($errors->any())

            <div class="error">

                @foreach ($errors->all() as $error)
                    <div>
                        {{ $error }}
                    </div>
                @endforeach

            </div>

        @endif


        <form action="{{ route('admin.authenticate') }}" method="POST">

            @csrf


            <div class="form-group">

                <label>
                    Email
                </label>

                <input type="email" name="email" placeholder="Masukkan email admin" value="{{ old('email') }}"
                    required>

            </div>


            <div class="form-group">

                <label>
                    Password
                </label>

                <input type="password" name="password" placeholder="Masukkan password" required>

            </div>


            <button type="submit" class="btn-login">
                Login Admin
            </button>

        </form>


        <div class="dummy">

            <strong>
                Akun Dummy
            </strong>

            Email:
            admin@event.test

            <br>

            Password:
            admin123

        </div>


    </div>


</body>

</html>
