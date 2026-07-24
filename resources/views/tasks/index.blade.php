@extends('layouts.app')

@section('title', 'Tasks')


@section('content')

    <h1>My Tasks</h1>
    <div id="notification"
         class="hidden mt-4 px-5 py-3 rounded-xl bg-green-100 text-green-700">

    </div>
    <div id="stats" class="grid md:grid-cols-3 gap-6 mt-6 mb-8">


        <div class="bg-white rounded-2xl shadow p-6">

            <p class="text-gray-500">
                Total Tasks
            </p>

            <h3 id="totalTasks"
                class="text-3xl font-bold mt-2">
                0
            </h3>

        </div>



        <div class="bg-white rounded-2xl shadow p-6">

            <p class="text-gray-500">
                Done
            </p>

            <h3 id="completedTasks"
                class="text-3xl font-bold text-green-600 mt-2">
                0
            </h3>

        </div>



        <div class="bg-white rounded-2xl shadow p-6">

            <p class="text-gray-500">
                In Progress
            </p>

            <h3 id="progressTasks"
                class="text-3xl font-bold text-yellow-600 mt-2">
                0
            </h3>

        </div>

        <div class="bg-white rounded-2xl shadow p-6">

            <p class="text-gray-500">
                New
            </p>

            <h3 id="newTasks"
                class="text-3xl font-bold text-blue-600 mt-2">
                0
            </h3>

        </div>


    </div>

    <hr>


    <div id="tasks" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        Loading...
    </div>

@endsection



@section('scripts')

    <script>

        const token = localStorage.getItem('token');

        function showNotification(message, type = 'success') {


            const notification = document.getElementById('notification');


            notification.innerText = message;


            notification.className =
                type === 'success'
                    ? 'mt-4 px-5 py-3 rounded-xl bg-green-100 text-green-700'
                    : 'mt-4 px-5 py-3 rounded-xl bg-red-100 text-red-700';



            notification.classList.remove('hidden');



            setTimeout(() => {

                notification.classList.add('hidden');

            }, 3000);


        }
        // Получение задач
        async function loadTasks() {

            const response = await fetch('/api/tasks', {

                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }

            });

            function updateStats(tasks) {


                const total = tasks.length;


                const completed = tasks.filter(task =>
                    task.status === 'done'
                ).length;


                const progress = tasks.filter(task =>
                    task.status === 'in_progress'
                ).length;





                document.getElementById('totalTasks').innerText =
                    total;


                document.getElementById('completedTasks').innerText =
                    completed;


                document.getElementById('progressTasks').innerText =
                    progress;

                document.getElementById('newTasks').innerText =
                    tasks.filter(task => task.status === 'new').length;

            }
            const data = await response.json();
            updateStats(data.data);

            const container = document.getElementById('tasks');


            container.innerHTML = "";


            data.data.forEach(task => {


                let statusColor = '';

                if(task.status === 'done') {

                    statusColor = 'bg-green-100 text-green-700';

                } else if(task.status === 'in_progress') {

                    statusColor = 'bg-yellow-100 text-yellow-700';

                } else {

                    statusColor = 'bg-gray-100 text-gray-700';

                }

                window.changeStatus = async function(id, status) {
                    const response = await fetch(`/api/tasks/${id}`, {

                        method: 'PATCH',

                        headers: {

                            'Content-Type': 'application/json',

                            'Authorization': 'Bearer ' + token,

                            'Accept': 'application/json'

                        },


                        body: JSON.stringify({

                            status: status

                        })

                    });


                    if(response.ok){


                        showNotification('Status updated successfully');
                        await loadTasks();


                    }else {

                        alert('Cannot update status');
                    }

                }



                container.innerHTML += `


    <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">


        <div class="flex justify-between items-start">


            <h3 class="text-xl font-bold text-slate-800">

                ${task.title}

            </h3>


        <select
            onchange="changeStatus(${task.id}, this.value)"
            class="rounded-lg border px-3 py-1">

            <option value="new"
                ${task.status === 'new' ? 'selected' : ''}>
                New
            </option>

            <option value="in_progress"
                ${task.status === 'in_progress' ? 'selected' : ''}>
                In Progress
            </option>

            <option value="done"
                ${task.status === 'done' ? 'selected' : ''}>
                Done
            </option>

        </select>


        </div>



        <p class="text-slate-600 mt-4">

            ${task.description ?? 'No description'}

        </p>




        <div class="mt-4 flex gap-3 text-sm">


            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">

                📁 ${task.category?.name ?? 'No category'}

            </span>


        </div>




        <div class="mt-6 flex gap-3">


            <button
                onclick="editTask(${task.id})"
                class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">

                Edit

            </button>



            <button
                onclick="deleteTask(${task.id})"
                class="px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition">

                Delete

            </button>


        </div>


    </div>


    `;


            });


        }



        // Переход на редактирование
        window.editTask = function(id){
            window.location.href = `/tasks/${id}/edit`;

        }



        // Удаление задачи

        window.deleteTask = async function(id){
            if(!confirm('Delete this task?')){
                return;
            }
            const response = await fetch(`/api/tasks/${id}`, {

                method: 'DELETE',

                headers: {

                    'Authorization': 'Bearer ' + token,

                    'Accept': 'application/json'

                }


            });


            const data = await response.json();


            console.log(data);


            if(response.ok) {

                window.location.reload();

            } else {

                alert(data.message ?? 'Cannot delete task');

            }


        }



        loadTasks();


    </script>

@endsection
