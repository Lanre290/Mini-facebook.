<div class="absolute top-0 right-0 left-0 h-20 shadow-lg flex items-center z-20 flex-row justify-between">
    <a class="font-extrabold text-blue-600 ml-4 text-2xl sm:text-3xl md:text-4xl lg:text-5xl" style="font-family: ;" href='/'>Connect</a>
    <div class="flex flex-row items-center justify-end">
        <form action="{{ route('search', ['term' => '']) }}" method="get" class="bg-gray-300 bg-opacity-50 w-64 h-fit rounded-lg m-3 flex flex-row p-1 px-3">
            <input type="text" name="term" id="" class="flex flex-grow h-11 bg-transparent focus:outline-none" placeholder="Search...">
            <button type="submit" class="p-1 text-2xl text-gray-600"><i class="fa fa-search"></i></button>
        </form>
        <a href="{{ route('profile', ['id' => session('user')->id]) }}" title="{{ session('user')->name }}" class="h-12 w-12 m-2 mr-5 shadow-lg shadow-blue-600 rounded-full cursor-pointer bg-gray-400 bg-center bg-cover bg-no-repeat" style="background-image: url('{{ session('user')->image_path }}')"></a>
    </div>
</div>