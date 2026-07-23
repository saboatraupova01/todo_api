@extends('layouts.app')

@section('title', 'Public Tasks')


@section('content')

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Public Tasks
            </h1>

            <p class="text-slate-500 mt-2">
                Tasks created by managers
            </p>

        </div>

    </div>



    <div id="publicTasks"
         class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">


        @foreach($tasks as $task)


            <div
                data-task-id="{{ $task->id }}"
                class="bg-white rounded-3xl shadow-lg p-6">


                <div class="flex justify-between items-start mb-4">


                    <h2 class="task-title text-xl font-bold text-slate-800">

                        {{ $task->title }}

                    </h2>


                    <span class="task-status px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-700">

                    {{ $task->status }}

                </span>


                </div>

                <p class="task-description text-slate-600 mb-5">

                    {{ $task->description ?? 'No description' }}

                </p>

                <div class="space-y-2 text-sm text-slate-500">

                   <p>
                        👤 {{ $task->user->name }}
                    </p>


                    <p>
                        📂 {{ $task->category->name ?? 'No category' }}
                    </p>


                    <p class="text-blue-600 font-semibold">

                        Public task

                    </p>


                </div>
                <div class="manager-actions hidden mt-6 flex gap-3">


                    <a href="/public-tasks/{{ $task->id }}/edit"

                       class="flex-1 text-center bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700">

                        Edit

                    </a>



                    <button
                        onclick="deleteTask({{ $task->id }})"

                        class="flex-1 bg-red-500 text-white px-4 py-2 rounded-xl hover:bg-red-600">

                        Delete

                    </button>


                </div>


            </div>


        @endforeach


    </div>


@endsection




@section('scripts')


    <script>


        const user = JSON.parse(localStorage.getItem('user'));



        const canManagePublicTasks = user?.roles?.some(role =>

            role.permissions?.some(permission =>

                permission.code === 'create-public-tasks'

            )

        );



        if(canManagePublicTasks){


            document
                .querySelectorAll('.manager-actions')
                .forEach(element => {


                    element.classList.remove('hidden');


                });


        }


        /*
        |--------------------------------------------------------------------------
        | REALTIME UPDATE PUBLIC TASK
        |--------------------------------------------------------------------------
        */


        if(window.Echo){


            window.Echo
                .channel('public-tasks')


                .listen('.public.task.updated', (event)=>{


                    const task = event.task;



                    const card = document.querySelector(
                        `[data-task-id="${task.id}"]`
                    );



                    if(card){


                        card.querySelector('.task-title')
                            .innerText = task.title;



                        card.querySelector('.task-description')
                            .innerText =
                            task.description ?? 'No description';



                        card.querySelector('.task-status')
                            .innerText =
                            task.status;
                    }
                });
        }


        /*
        | DELETE PUBLIC TASK
        */

        async function deleteTask(id){


            const token = localStorage.getItem('token');



            const response = await fetch(

                `/api/public-tasks/${id}`,

                {

                    method: 'DELETE',

                    headers: {

                        'Authorization':
                            'Bearer ' + token,
                        'Accept':
                            'application/json'


                    }
                }
            );

            const data = await response.json();
            if(response.ok){
                location.reload();

            } else {
                alert(data.message);
            }
        }
    </script>


@endsection
