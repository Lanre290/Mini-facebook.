
const form = document.getElementById('login-form');
const signInButton = document.getElementById('loginbtn');

async function validateSignIn(event){
    signInButton.innerHTML = `
    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3V4a12 12 0 100 24v-4l-3 3 3 3v-4a8 8 0 01-8-8z"></path>
    </svg>&nbsp;Signin in`;
    signInButton.disabled = true;
    signInButton.style.backgroundColor = 'rgb(191, 219, 254)';
    signInButton.style.cursor = 'not-allowed';
    event.preventDefault();

    let email = document.getElementById('email').value;
    let pwd = document.getElementById('pwd').value;

    const validateEmail = (email) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    };

    try {
        if(email.length < 1 ||pwd.length < 1){
            throw new Error('Fill in all fields.');
        }
        if(!validateEmail(email)){
            throw new Error('Invalid Email.');
        }
        const formData = new FormData(form);

        try {
            const response = await fetch("auth/login", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });


            if (response.ok) {
                window.location.href = '/home';
            } else {
                const errorData = await response.json();
                signInButton.removeAttribute('disabled');
                signInButton.classList.remove('bg-blue-200');
                throw new Error(errorData.error);
            }
        } catch (error) {
            toastr.error(error);
        }
        finally{
            signInButton.disabled = false;
            signInButton.style.backgroundColor = 'rgb(59 130 246)';
            signInButton.style.cursor = 'pointer';
            signInButton.innerHTML = 'Log in';
        }
    } catch (error) {
        toastr.error(error);
        signInButton.removeAttribute('disabled');
        signInButton.classList.remove('bg-blue-200');
    }

}

form.addEventListener('submit', validateSignIn);
signInButton.addEventListener('click', validateSignIn);