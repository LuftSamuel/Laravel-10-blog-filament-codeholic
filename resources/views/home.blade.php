<x-app-layout meta-description="Blog de programacion web">

    <div class="container max-w-4xl mx-auto py-6">

        {{-- Header --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Latest posts --}}
            <div class="col-span-2">
                <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                    Latest posts
                </h2>
                <x-post-item :post="$latestPost" />
            </div>
            {{-- Polular 3 --}}
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                    Popular posts
                </h2>
                @foreach ($popular3Posts as $post)
                    <div class="grid grid-cols-4 gap-2 mb-3">
                        <a href="{{ route('view', $post) }}">
                            <img src="{{ $post->obtenerMiniatura() }}" alt="{{ $post->titulo }}">
                        </a>
                        <div class="col-span-3">
                            <a href="{{ route('view', $post) }}">
                                <h3 class="text-sm uppercase">{{ $post->titulo }}</h3>
                            </a>
                            <div class="flex gap-4">
                                @foreach ($post->categorias as $categoria)
                                    <a href="#" class="text-blue-700 text-sm font-bold uppercase">
                                        {{ $categoria->titulo }}
                                    </a>
                                @endforeach
                            </div>
                            <div class="text-xs">
                                {{ $post->shortBody(15) }}
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recomended posts --}}
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                Recomended posts
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach ($recommendedPosts as $post)
                    <x-post-item :post="$post" />
                @endforeach
            </div>

        </div>
        {{-- Latest categories --}}
        @foreach ($categories as $category)
            <div>

                <a href="{{ route('por-categoria', $category) }}">
                    <h2
                        class="text-lg sm:text-xl font-bold text-blue-500 uppercase pb-1 border-b-2 border-blue-500 mb-3">
                        {{ $category->titulo }}
                    </h2>
                </a>

                <div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                        @foreach ($category->postsActivos()->get() as $post)
                            <x-post-item :post="$post" />
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

    </div>

</x-app-layout>
