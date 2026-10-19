<!-- @vite(['resources/css/admin.css'])
   
   <nav>


        <div class="logo">
            Event Management
        </div>


        <div class="admin-area">

            <span class="admin-name">

                {{ session('admin_name') }}

            </span>

            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.kyc.index') }}">Pemeriksaan KYC</a>

            <form action="{{ route('admin.logout') }}" method="POST">

                @csrf

                <button type="submit" class="logout">
                    Logout
                </button>

            </form>

        </div>


    </nav> -->
