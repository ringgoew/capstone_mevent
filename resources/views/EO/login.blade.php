@extends('layouts.eo')
@section('title', 'Dashboard EO')
@section('content')


    <div class="container">

        <h1>Login EO</h1>

        <p class="subtitle">
            Masuk untuk mengelola event kamu.
        </p>


        @if (session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif


        <form action="{{ route('eo.login.authenticate') }}" method="POST">

            @csrf

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Masukkan email"
                    required
                >

            </div>


            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >

            </div>


            <button type="submit">
                Login
            </button>

        </form>


        <div class="register">

            Belum memiliki akun EO?

            <a href="{{ route('eo.register') }}">
                Daftar sebagai EO
            </a>

        </div>

    </div>

</body>


@endsection