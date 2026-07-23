@extends('layouts.app')

@section('title', 'Create Task')


@section('content')


    <div class="max-w-2xl mx-auto">


        <div class="bg-white rounded-3xl shadow-lg p-8">


            <h1 class="text-3xl font-bold text-slate-800 mb-2">
                Create Task
            </h1>


            <p class="text-slate-500 mb-8">
                Add a new task to your workspace
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
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Enter task title">


                </div>



                <div>


                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Description
                    </label>


                    <textarea
                        id="description"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Describe your task"></textarea>


                </div>



                <div class="grid md:grid-cols-2 gap-5">


                    <div>


                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Status
                        </label>


                        <select
                            id="status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">


                            <option value="new">
                                New
                            </option>


                            <option value="in_progress">
                                In Progress
                            </option>


                            <option value="done">
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
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">


                        </select>


                    </div>

                    <div class="mt-5">

                        <label class="flex items-center gap-3 text-sm font-medium text-slate-700">

                            <input
                                type="checkbox"
                                id="is_public"
                                class="rounded border-slate-300">

                            Public task

                        </label>

                    </div>


                </div>




                <button
                    type="submit"
                    class="w-full py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">


                    Create Task


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

        const token = localStorage.getItem('token');

        async function loadCategories(){

            const response = await fetch('/api/categories', {

                headers:{
                    'Authorization': 'Bearer ' + token,
                    'Accept':'application/json'
                }

            });


            const data = await response.json();


            const select = document.getElementById('category');


            data.data.forEach(category => {

                select.innerHTML += `

            <option value="${category.id}">
                ${category.name}
            </option>

        `;

            });

        }


        loadCategories();


         // Создание задачи
         document
             .getElementById('taskForm')
             .addEventListener('submit', async function(e) {

                 e.preventDefault();


                 const taskData = {

                     title: document.getElementById('title').value,

                     description: document.getElementById('description').value,

                     status: document.getElementById('status').value,

                     category_id: document.getElementById('category').value,

                     is_public: document.getElementById('is_public').checked

                 };


                 const response = await fetch('/api/tasks', {

                     method: 'POST',

                     headers: {

                         'Content-Type': 'application/json',

                         'Accept': 'application/json',

                         'Authorization': 'Bearer ' + token

                     },

                     body: JSON.stringify(taskData)

                 });


                 const data = await response.json();


                 if(response.ok) {

                     document.getElementById('message').innerText =
                         'Task created successfully';


                     setTimeout(() => {

                         window.location.href = '/tasks';

                     }, 1000);


                 } else {

                     console.log(data);


                     if(data.errors) {

                         document.getElementById('titleError').innerText =
                             data.errors.title?.[0] ?? '';

                         document.getElementById('descriptionError').innerText =
                             data.errors.description?.[0] ?? '';

                         document.getElementById('statusError').innerText =
                             data.errors.status?.[0] ?? '';

                         document.getElementById('categoryError').innerText =
                             data.errors.category_id?.[0] ?? '';

                     } else {

                         document.getElementById('message').innerText =
                             data.message ?? 'Something went wrong';

                     }

                 }


             });


     </script>
 @endsection
