import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;

Alpine.start();

window.Echo.join('chat')

    .here((users) => {

        const online = document.getElementById('online-users');

        online.innerHTML = users
            .map(user => `<div>${user.name}</div>`)
            .join('');

    })

    .joining((user) => {
        console.log('USER JOINED:', user);
    })

    .leaving((user) => {
        console.log('USER LEFT:', user);
    })

    .listen('.App\\Events\\MessageSent', (event) => {

        const messages = document.getElementById('messages');

        const html = `
            <div class="bg-white rounded-lg shadow p-3">
                <div class="font-semibold">
                    ${event.user}
                </div>

                <div>
                    ${event.message}
                </div>
            </div>
        `;

        messages.insertAdjacentHTML('beforeend', html);

        messages.scrollTop = messages.scrollHeight;
    });
window.Echo.channel('chat')
    .listen('.App\\Events\\MessageDeleted', (event) => {

        console.log('MESSAGE DELETED', event);

        const message = document.getElementById(
            `message-${event.id}`
        );

        if (message) {
            message.remove();
        }

    });

window.Echo.channel('chat')
    .listen('.App\\Events\\MessageUpdated', (event) => {

        console.log('MESSAGE UPDATED', event);

        const message = document.getElementById(`message-${event.id}`);

        if (!message) return;

        const text = message.querySelector('.message-text');

        if (text) {
            text.textContent = event.message;
        }

    });
