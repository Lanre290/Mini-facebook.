@include('includes.header-script', ['url' => 'Settings - '.session('user')->name])



<body class="flex  items-center justify-center w-screen h-screen relative">
    @include('page-sections.header')
    @include('includes.session')

</body>