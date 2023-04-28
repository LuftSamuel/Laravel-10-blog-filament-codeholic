<x-app-layout>


    <!-- Posts Section -->
    <section class="w-full md:w-2/3 px-3">
        <div class="flex flex-col">

            @foreach ($posts as $post)
                <div>
                    <a href="{{ route('view', $post) }}">
                        <h2 class="text-blue-500 font-bold text-lg sm:text-xl mb-2">
                            {!! str_replace(
                                request()->get('query'),
                                '<span class="bg-yellow-300">' . request()->get('query') . '</span>',
                                $post->titulo,
                            ) !!}
                        </h2>
                    </a>
                    <div>
                        {{ $post->shortBody() }}
                    </div>
                </div>
            @endforeach

            {{ $posts->onEachSide(1)->links() }}

        </div>

        <!-- Pagination -->
        {{-- <div class="flex items-center py-8">
                <a href="#" class="h-10 w-10 bg-blue-800 hover:bg-blue-600 font-semibold text-white text-sm flex items-center justify-center">1</a>
                    <a href="#" class="h-10 w-10 font-semibold text-gray-800 hover:bg-blue-600 hover:text-white text-sm flex items-center justify-center">2</a>
                    <a href="#" class="h-10 w-10 font-semibold text-gray-800 hover:text-gray-900 text-sm flex items-center justify-center ml-3">Next <i class="fas fa-arrow-right ml-2"></i></a>
                </div> --}}

    </section>

    <x-sidebar />



</x-app-layout>
