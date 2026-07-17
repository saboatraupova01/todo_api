@extends('layouts.app')

@section('title', 'Edit Task')


@section('content')


    <div class="max-w-2xl mx-auto">


        <div class="bg-white rounded-3xl shadow-lg p-8">


            <h1 class="text-3xl font-bold text-slate-800 mb-2">
                Edit Task
            </h1>


            <p class="text-slate-500 mb-8">
                Update your task information
            </p>



            <form id="taskForm"
                  class="space-y-6">


                <input type="hidden" id="taskId">



                <div>


                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Title
                    </label>


                    <input
                        type="text"
                        id="title"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">


                </div>




                <div>


                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Description
                    </label>


                    <textarea
                        id="description"
                        rows="4"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>


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



                </div>





                <div class="flex gap-4">


                    <a href="/tasks"
                       class="flex-1 text-center py-3 rounded-xl bg-slate-200 text-slate-700 hover:bg-slate-300 transition">

                        Cancel

                    </a>




                    <button
                        type="submit"
                        class="flex-1 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">


                        Save Changes


                    </button>


                </div>



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

        const taskId = window.location.pathname.split('/')[2];


        // Загружаем категории
        async function loadCategories(selectedCategoryId) {

            const response = await fetch('/api/categories', {

                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }

            });


            const data = await response.json();


            const select = document.getElementById('category');

            select.innerHTML = "";

            data.data.forEach(category => {

                select.innerHTML += `

            <option value="${category.id}"
                ${category.id === selectedCategoryId ? 'selected' : ''}>
                ${category.name}
            </option>

        `;

            });

        }



        // Загружаем задачу
        async function loadTask() {

            const response = await fetch(`/api/tasks/${taskId}`, {

                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }

            });


            const data = await response.json();


            const task = data.data;

            document.getElementById('title').value = task.title;

            document.getElementById('description').value =
                task.description ?? '';

            document.getElementById('status').value =
                task.status;

            document.getElementById('category').value =
                task.category;

            await loadCategories(task.category?.id);
        }


        loadTask();



        // Обновление задачи
        document
            .getElementById('taskForm')
            .addEventListener('submit', async function(e) {

                e.preventDefault();


                const taskData = {

                    title: document.getElementById('title').value,

                    description: document.getElementById('description').value,

                    status: document.getElementById('status').value,

                    category_id: document.getElementById('category').value

                };


                const response = await fetch(`/api/tasks/${taskId}`, {

                    method: 'PATCH',

                    headers: {

                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        'Authorization': 'Bearer ' + token

                    },


                    body: JSON.stringify(taskData)

                });


                const data = await response.json();


                console.log(data);


                if(response.ok) {
                    const button = document.querySelector('button[type="submit"]');

                    button.innerText = "Saving...";
                    button.disabled = true;


                    button.innerText = "Saved ✓";

                    document.getElementById('message').innerText =
                        'Task updated successfully';


                    setTimeout(() => {

                        window.location.href = '/tasks';

                    }, 1000);


                } else {

                    document.getElementById('message').innerText =
                        data.message ?? 'Something went wrong';

                }

            });


    </script>

@endsection
