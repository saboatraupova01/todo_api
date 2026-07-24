@extends('layouts.app')

@section('title', 'Login')


@section('content')


    <div class="min-h-[70vh] flex items-center justify-center">


        <div class="bg-white rounded-3xl shadow-xl p-8 w-full max-w-md">


            <h1 class="text-3xl font-bold text-center text-slate-800">
                Welcome back
            </h1>


            <p class="text-center text-slate-500 mt-2 mb-8">
                Login to manage your tasks
            </p>



            <form id="loginForm" class="space-y-5">


                <div>

                    <label class="block text-sm mb-2 text-slate-700">
                        Username
                    </label>


                    <input
                        id="username"
                        type="text"
                        class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-blue-500"
                        placeholder="Username">

                </div>




                <div>

                    <label class="block text-sm mb-2 text-slate-700">
                        Password
                    </label>


                    <input
                        id="password"
                        type="password"
                        class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-blue-500"
                        placeholder="Password">

                </div>




                <button
                    class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition">

                    Login

                </button>



            </form>



            <p id="message"
               class="text-center mt-5 text-sm">
            </p>



            <p class="text-center mt-6 text-slate-500">

                Don't have an account?

                <a href="/register"
                   class="text-blue-600 hover:underline">

                    Register

                </a>

            </p>


        </div>


    </div>


@endsection



@section('scripts')


    <script>


        document
            .getElementById('loginForm')
            .addEventListener('submit', async function(e){


                e.preventDefault();



                const response = await fetch('/api/login', {


                    method:'POST',


                    headers:{


                        'Content-Type':'application/json',

                        'Accept':'application/json'


                    },


                    body:JSON.stringify({


                        username:
                        document.getElementById('username').value,


                        password:
                        document.getElementById('password').value


                    })


                });



                const data = await response.json();



                if(response.ok){

                        localStorage.setItem(
                            'token',
                            data.data.token
                        );


                        localStorage.setItem(
                            'user',
                            JSON.stringify(data.data.user)
                        );


                        window.location.href='/tasks';



                }else{


                    document.getElementById('message').innerText =
                        data.message ?? 'Login failed';


                }


            });


    </script>


@endsection
