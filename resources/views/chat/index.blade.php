<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded shadow">

                <h2 class="text-xl font-semibold mb-4">
                    Chat
                </h2>


                <form method="POST" action="/messages">
                    @csrf

                    <textarea
                        name="message"
                        rows="3"
                        class="w-full border rounded-lg p-3"
                        placeholder="Введите сообщение..."
                    ></textarea>

                    <button
                        type="submit"
                        class="mt-3 bg-blue-600 text-white px-5 py-2 rounded-lg"
                    >
                        Отправить
                    </button>

                </form>


                <div
                    id="messages"
                    class="mt-6 h-96 overflow-y-auto space-y-3 border rounded-lg p-4 bg-gray-50"
                >

                    @foreach($messages as $message)

                        <div
                            id="message-{{ $message->id }}"
                            class="bg-white rounded-lg shadow p-3"
                        >

                            <div class="text-sm font-semibold text-gray-600">
                                {{ $message->user->name }}
                            </div>


                            <div class="text-gray-800 message-text">
                                {{ $message->message }}
                            </div>


                            <div class="text-xs text-gray-400 mt-2">
                                {{ $message->created_at->format('H:i') }}
                            </div>


                            @if($message->user_id === auth()->id())

                                <div class="mt-3 flex gap-3">

                                    <a
                                        href="{{ route('messages.edit', $message->id) }}"
                                        class="text-blue-500"
                                    >
                                        Изменить
                                    </a>


                                    <form method="POST" action="{{ route('messages.destroy', $message->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button class="text-red-500">
                                            Удалить
                                        </button>

                                    </form>

                                </div>

                            @endif


                        </div>

                    @endforeach


                </div>


            </div>

        </div>
    </div>


</x-app-layout>
