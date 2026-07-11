import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;

Alpine.start();

window.Echo.channel('chat')
    .listen('.App\\Events\\MessageSent', (event) => {

        const html = `
<div id="message-${event.id}" class="bg-white rounded-lg shadow p-3 mt-3">

    <div class="text-sm font-semibold text-gray-600">
        ${event.user}
    </div>

    <div class="text-gray-800 message-text">
        ${event.message}
    </div>

    <div class="text-xs text-gray-400 mt-1">
        ${new Date(event.created_at).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        })}
    </div>

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
