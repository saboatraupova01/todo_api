<x-app-layout>

    <div class="p-6">

        <form method="POST" action="/messages/{{ $message->id }}">

            @csrf
            @method('PUT')


            <textarea
                name="message"
                class="border rounded w-full"
            >{{ $message->message }}</textarea>


            <button class="mt-3 bg-blue-500 text-white px-4 py-2 rounded">
                Сохранить
            </button>


        </form>

    </div>

</x-app-layout>
