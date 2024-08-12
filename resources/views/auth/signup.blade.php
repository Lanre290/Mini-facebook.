<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <title>Sign-up</title>
</head>
<body class="flex items-center justify-center w-screen h-screen">
    <form action="{{ route('auth/sign-up') }}" method="POST" id="sign-up-form" class="w-full sm:w-full md:w-4/6 lg:w-5/12 h-screen sm:h-screen md:h-auto lg:h-auto flex flex-col items-center justify-center mx-auto shadow-lg rounded-lg my-auto p-5">
        @csrf
        <div class="text-4xl text-gray-900 mx-auto mb-4">Sign up</div>
        <input type="text" name="name" id="name" placeholder="Name E.g John Doe" class="p-4 text-gray-800 bg-gray-900 bg-opacity-5 rounded-md w-5/6 mx-auto mb-4"/>
        <input type="text" name="email" id="email" placeholder="Email..." class="p-4 text-gray-800 bg-gray-900 bg-opacity-5 rounded-md w-5/6 mx-auto mb-4"/>
        <input type="password" name="pwd" id="pwd" placeholder="Password..." class="p-4 text-gray-800 bg-gray-900 bg-opacity-5 rounded-md w-5/6 mx-auto mb-4"/>
        <input type="password" id="pwd_rpt" placeholder="Password repeat..." class="p-4 text-gray-800 bg-gray-900 bg-opacity-5 rounded-md w-5/6 mx-auto mb-4"/>

        <button class="py-2 px-6 bg-blue-500 text-gray-50 shadow-md rounded-md cursor-pointer  hover:bg-blue-600 disabled:bg-blue-400 w-5/6 sm:w-4/6 md:w-4/6 lg:w-3/6 mx-auto flex flex-row  items-center justify-center" id="signup-btn">Sign up</button>
        <div class="m-auto my-6">
            <span class="">Have an account?</span>
            <a class="text-blue-500" href="{{ route('login') }}">Login</a>
        </div>
    </form>
    <script src="{{ asset('js/signup.js') }}"></script>
</body>
</html>