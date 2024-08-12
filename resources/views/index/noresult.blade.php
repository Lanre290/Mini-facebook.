
@include('includes.header-script', ['url' => "Search results for \"".$data."\""])
<body class="flex flex-col items-center justify-center w-screen h-screen bg-center bg-cover bg-no-repeat bg-opacity-65" style="background-image: url({{ asset('img/mindset.svg') }})">
    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl text-center mx-auto text-gray-800 mb-4">"{{ $data }}" NOT FOUND.</h1>
    <h3 class="text-2xl text-gray-800 text-center mx-auto">We couldn't find anythin for "{{ $data }}". ☹️</h3>
</body>