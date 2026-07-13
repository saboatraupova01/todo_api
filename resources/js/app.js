import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;

Alpine.start();

/*
|--------------------------------------------------------------------------
| Presence Channel (онлайн пользователи)
|--------------------------------------------------------------------------
*/

window.Echo.join('chat')

    .here((users) => {

        const online = document.getElementById('online-users');

        if (!online) return;

        online.innerHTML = users
            .map(user => `
                <div id="user-${user.id}">
                      ${user.name}
                </div>
            `)
            .join('');

    })

    .joining((user) => {

        console.log('USER JOINED:', user);

        const online = document.getElementById('online-users');

        if (!online) return;

        if (document.getElementById(`user-${user.id}`)) return;

        online.insertAdjacentHTML(
            'beforeend',
            `
            <div id="user-${user.id}">
                  ${user.name}
            </div>
            `
        );

    })

    .leaving((user) => {

        console.log('USER LEFT:', user);

        document
            .getElementById(`user-${user.id}`)
            ?.remove();

    });

/*
|--------------------------------------------------------------------------
| Сообщения
|--------------------------------------------------------------------------
*/

window.Echo.channel('chat')

    .listen('.App\\Events\\MessageSent', (event) => {

        console.log('MESSAGE RECEIVED', event);

        const messages = document.getElementById('messages');

        if (!messages) return;

        const html = `
            <div
                id="message-${event.id}"
                class="bg-white rounded-lg shadow p-3 mt-3"
            >

                <div class="text-sm font-semibold text-gray-600">
                    ${event.user}
                </div>

                <div class="message-text text-gray-800">
                    ${event.message}
                </div>

                <div class="text-xs text-gray-400 mt-1">
                    ${new Date(event.created_at).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        })}
                </div>

            </div>
        `;

        messages.insertAdjacentHTML('beforeend', html);

        messages.scrollTop = messages.scrollHeight;

    })

    .listen('.App\\Events\\MessageDeleted', (event) => {

        console.log('MESSAGE DELETED', event);

        document
            .getElementById(`message-${event.id}`)
            ?.remove();

    })

    .listen('.App\\Events\\MessageUpdated', (event) => {

        console.log('MESSAGE UPDATED', event);

        const message = document.getElementById(
            `message-${event.id}`
        );

        if (!message) return;

        const text = message.querySelector('.message-text');

        if (text) {
            text.textContent = event.message;
        }

    });
