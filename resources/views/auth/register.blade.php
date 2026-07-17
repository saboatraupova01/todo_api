@extends('layouts.app')

@section('title', 'Register')


@section('content')


    <div class="min-h-[70vh] flex items-center justify-center">


        <div class="bg-white rounded-3xl shadow-xl p-8 w-full max-w-md">


            <h1 class="text-3xl font-bold text-center text-slate-800">
                Create account
            </h1>


            <p class="text-center text-slate-500 mt-2 mb-8">
                We will send your login details by email
            </p>



            <form id="registerForm" class="space-y-5">



                <div>

                    <label class="block mb-2 text-sm text-slate-700">
                        Name
                    </label>


                    <input
                        id="name"
                        type="text"
                        class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-blue-500"
                        placeholder="Your name">

                </div>




                <div>

                    <label class="block mb-2 text-sm text-slate-700">
                        Email
                    </label>


                    <input
                        id="email"
                        type="email"
                        class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-blue-500"
                        placeholder="example@mail.com">

                </div>




                <button
                    class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition">

                    Create account

                </button>


            </form>



            <p id="message"
               class="text-center mt-5 text-sm">
            </p>




            <p class="text-center mt-6 text-slate-500">


                Already have account?


                <a href="/login"
                   class="text-blue-600 hover:underline">

                    Login

                </a>


            </p>



        </div>

    </div>


@endsection



@section('scripts')


    <script>


        document
            .getElementById('registerForm')
            .addEventListener('submit', async function(e){


                e.preventDefault();



                const response = await fetch('/api/register', {


                    method:'POST',


                    headers:{


                        'Content-Type':'application/json',

                        'Accept':'application/json'


                    },


                    body:JSON.stringify({


                        name:
                        document.getElementById('name').value,


                        email:
                        document.getElementById('email').value


                    })


                });



                const data = await response.json();



                const message =
                    document.getElementById('message');



                if(response.ok){


                    message.className =
                        "text-center mt-5 text-sm text-green-600";


                    message.innerText =
                        "Account created! Check your email for login details.";



                }else{


                    message.className =
                        "text-center mt-5 text-sm text-red-600";


                    message.innerText =
                        data.message ?? "Registration failed";


                }



            });


    </script>


@endsection
