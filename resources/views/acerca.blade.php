<x-app-layout meta-title="Acerca de Blog de programacion web">

    <section class="w-full md:w-2/3 flex flex-col items-center px-3">

        <article class="flex flex-col shadow my-4">
            <!-- Article Image -->
            <a href="#" class="hover:opacity-75">
                <img src="/storage/{{ $widget->imagen }}">
            </a>
            <div class="bg-white flex flex-col justify-start p-6">
                
                <h1 class="text-3xl font-bold hover:text-gray-700 pb-4">{{ $widget ->titulo }}</h1>
                

                <div>
                    {!! $widget->contenido !!}
                </div>

            </div>
        </article>
        
    </section>

</x-app-layout>