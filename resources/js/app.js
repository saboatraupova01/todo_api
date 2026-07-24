import Echo from 'laravel-echo';
import Pusher from 'pusher-js';


window.Pusher = Pusher;

window.Echo = new Echo({

    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: false,
    enabledTransports: ['ws'],

});

console.log('Echo initialized');

/*
| Helpers
*/

function getCurrentUser()
{
    return JSON.parse(
        localStorage.getItem('user')
    );
}

function showNotification(message)
{
    const notification =
        document.createElement('div');

    notification.innerHTML = message;

    notification.className = `
        fixed
        top-5
        right-5
        bg-slate-800
        text-white
        px-6
        py-4
        rounded-xl
        shadow-lg
        z-50
        max-w-sm
    `;

    document.body.appendChild(notification);

    setTimeout(()=>{
        notification.remove();
    },8000);

}

/*
| PUBLIC TASK CREATED
*/

window.Echo.channel('public-tasks')
    .listen('.public.task.created', (event)=>{

        console.log(
            'Public task:',
            event
        );

        const task = event.task;

/*
Add card
*/
        const container =
            document.getElementById('publicTasks');

        if(container){
            const card =
                document.createElement('div');

            card.dataset.taskId =
                task.id;

            card.className = `
            bg-white
            rounded-3xl
            shadow-lg
            p-6
        `;

            card.innerHTML = `

        <div class="flex justify-between items-start mb-4">

            <h2 class="task-title text-xl font-bold text-slate-800">
                ${task.title}
            </h2>

            <span class="task-status px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-700">
                ${task.status}
            </span>

        </div>

        <p class="task-description text-slate-600 mb-5">
            ${task.description ?? 'No description'}
        </p>

        <div class="space-y-2 text-sm text-slate-500">

            <p>
                👤 ${task.user}
            </p>

            <p>
                📂 ${task.category ?? 'No category'}
            </p>

            <p class="text-blue-600 font-semibold">
                Public task
            </p>
        </div>

        `;
            container.prepend(card);
        }
/*
Notification
*/
        const currentUser =
            getCurrentUser();

        if(
            currentUser &&
            currentUser.name === task.user
        ){
            return;
        }

        showNotification(`

        <div class="font-bold text-lg">
            🔔 Новая публичная задача
        </div>

        <div class="mt-2">
            ${task.user}
            создал(а) задачу
        </div>

        <div class="mt-2">
            📝 ${task.title}
        </div>

    `);

    });

/*
| PUBLIC TASK UPDATED
*/
window.Echo.channel('public-tasks')
    .listen('.public.task.updated',(event)=>{


        console.log(
            'UPDATED EVENT:',
            event
        );

        const task = event.task;
        const card = document.querySelector(
            `[data-task-id="${task.id}"]`
        );


        if(card){

            const title =
                card.querySelector('.task-title');

            const description =
                card.querySelector('.task-description');

            const status =
                card.querySelector('.task-status');

            if(title){
                title.innerText =
                    task.title;
            }
            if(description){
                description.innerText =
                    task.description ?? 'No description';
            }
            if(status){
                status.innerText =
                    task.status;
            }
        }
       showNotification(`

        <div class="font-bold text-lg">
            ✏️ Задача изменена
        </div>

        <div class="mt-2">
            ${task.title}
        </div>

        <div class="text-sm mt-2">
            Изменил(а):
            ${task.user}
        </div>
    `);
    });
/*
| PUBLIC TASK DELETED
*/

window.Echo.channel('public-tasks')
    .listen('.public.task.deleted', (event)=>{

        console.log(
            'Public task deleted:',
            event
        );
        const task = event.task;

        const card =
            document.querySelector(
                `[data-task-id="${task.id}"]`
            );

        if(card){
            card.remove();
        }


        showNotification(`
        <div class="font-bold text-lg">
            🗑️ Задача удалена
        </div>

        <div class="mt-2">
            "${task.title}"
        </div>

        <div class="mt-2 text-sm">
            Удалил(а):
            ${task.user}
        </div>


    `);
    });
