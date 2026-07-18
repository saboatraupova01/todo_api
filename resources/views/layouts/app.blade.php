<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Todo API')
    </title>


    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body class="bg-slate-100 min-h-screen">


<header class="bg-white shadow-sm">


    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">


        <a href="/tasks"
           class="text-2xl font-bold text-blue-600">

            Todo API

        </a>



        <nav class="flex items-center gap-4">


            <div id="guestLinks"
                 class="flex gap-4">


                <a href="/login"
                   class="text-slate-600 hover:text-blue-600">

                    Login

                </a>


                <a href="/register"
                   class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">

                    Register

                </a>


            </div>




            <div id="userLinks"
                 class="hidden items-center gap-4">


                <a href="/tasks"
                   class="text-slate-600 hover:text-blue-600">

                    Tasks

                </a>



                <a href="/tasks/create"
                   class="text-slate-600 hover:text-blue-600">

                    Create Task

                </a>

                <span id="navUsername"
                      class="font-medium text-slate-700">
                </span>


                <button id="logout"
                        class="bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600">

                    Logout

                </button>


            </div>


        </nav>


    </div>


</header>




<main class="max-w-7xl mx-auto px-6 py-8">


    @yield('content')


</main>




<footer class="text-center text-slate-500 py-6">

    Todo API  {{ date('Y') }}

</footer>



<script>


    const authToken = localStorage.getItem('token');
    const guestLinks = document.getElementById('guestLinks');

    const userLinks = document.getElementById('userLinks');



    if( authToken){


        guestLinks.classList.add('hidden');

        userLinks.classList.remove('hidden');

        userLinks.classList.add('flex');



        const user =
            JSON.parse(localStorage.getItem('user'));



        if(user){

            document.getElementById('navUsername')
                .innerText =
                user.name;

        }


    }





    document
        .getElementById('logout')
        ?.addEventListener('click', function(){


            localStorage.removeItem('token');

            localStorage.removeItem('user');


            window.location.href='/login';


        });



</script>



@yield('scripts')


</body>

</html>
