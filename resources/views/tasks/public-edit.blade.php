@extends('layouts.app')

@section('title', 'Edit Public Task')


@section('content')


    <div class="max-w-2xl mx-auto">


        <div class="bg-white rounded-3xl shadow-lg p-8">


            <h1 class="text-3xl font-bold text-slate-800 mb-2">
                Edit Public Task
            </h1>


            <p class="text-slate-500 mb-8">
                Update your public task
            </p>



            <form id="taskForm"
                  class="space-y-6">


                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Title
                    </label>


                    <input
                        type="text"
                        id="title"
                        value="{{ $task->title }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">


                </div>




                <div>

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Description
                    </label>


                    <textarea
                        id="description"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $task->description }}</textarea>


                </div>




                <div class="grid md:grid-cols-2 gap-5">


                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Status
                        </label>


                        <select
                            id="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                            <option value="new"
                                @selected($task->status == 'new')>
                                New
                            </option>


                            <option value="in_progress"
                                @selected($task->status == 'in_progress')>
                                In Progress
                            </option>


                            <option value="done"
                                @selected($task->status == 'done')>
                                Done
                            </option>


                        </select>


                    </div>




                    <div>

                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Category
                        </label>


                        <select
                            id="category"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3">


                            @foreach($categories as $category)

                                <option value="{{ $category->id }}"
                                    @selected($task->category_id == $category->id)>

                                    {{ $category->name }}

                                </option>

                            @endforeach


                        </select>


                    </div>


                </div>





                <div class="mt-5">

                    <label class="flex items-center gap-3 text-sm font-medium text-slate-700">


                        <input
                            type="checkbox"
                            id="is_public"
                            checked
                            disabled
                            class="rounded border-slate-300">


                        Public task


                    </label>


                </div>





                <button
                    type="submit"
                    class="w-full py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">


                    Save Changes


                </button>



            </form>



            <p id="message"
               class="mt-5 text-center text-sm">
            </p>


        </div>


    </div>


@endsection



@section('scripts')


    <script>


        document
            .getElementById('taskForm')
            .addEventListener('submit', async function(e){


                e.preventDefault();


                const token = localStorage.getItem('token');



                const response = await fetch(
                    '/api/public-tasks/{{ $task->id }}',
                    {

                        method:'PATCH',

                        headers:{

                            'Content-Type':'application/json',

                            'Accept':'application/json',

                            'Authorization':'Bearer ' + token

                        },


                        body:JSON.stringify({

                            title:
                            document.getElementById('title').value,


                            description:
                            document.getElementById('description').value,


                            status:
                            document.getElementById('status').value,


                            category_id:
                            document.getElementById('category').value

                        })


                    }
                );



                const data = await response.json();



                if(response.ok){


                    document.getElementById('message').innerText =
                        'Task updated successfully';



                    setTimeout(()=>{

                        window.location.href='/public-tasks';

                    },1000);



                } else {


                    document.getElementById('message').innerText =
                        data.message ?? 'Error';


                }



            });


    </script>


@endsection
