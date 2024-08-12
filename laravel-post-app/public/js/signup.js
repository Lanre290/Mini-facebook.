const form = document.getElementById('sign-up-form');
const signupButton = document.getElementById('signup-btn');

async function validateSignUp(event){
    signupButton.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3V4a12 12 0 100 24v-4l-3 3 3 3v-4a8 8 0 01-8-8z"></path>
                    </svg>&nbsp;Loading...`;
    signupButton.disabled = true;
    signupButton.style.backgroundColor = 'rgb(191, 219, 254)';
    signupButton.style.cursor = 'not-allowed';
    event.preventDefault();

    let name_ = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let pwd = document.getElementById('pwd').value;
    let pwdRepeat = document.getElementById('pwd_rpt').value;

    const validateEmail = (email) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    };

    try {
        if(name_.length < 1 || email.length < 1 ||pwd.length < 1 || pwdRepeat.length < 1){
            throw new Error('Fill in all fields.');
        }
        if(pwd != pwdRepeat){
            throw new Error('passwords do not match.');
        }
        if(!validateEmail(email)){
            throw new Error('Invalid Email.');
        }
        const formData = new FormData(form);

        try {
            const response = await fetch("auth/signup", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });


            if (response.ok) {
                signIn(email, pwd);
            } else {
                // Handle errors (e.g., show an error message)
                const errorData = await response.json();
                signupButton.removeAttribute('disabled');
                throw new Error(errorData.error);
            }
        } catch (error) {
            toastr.error(error);
        }
    } catch (error) {
        toastr.error(error);
        signupButton.disabled = false;
        signupButton.style.backgroundColor = 'rgb(rgb(59 130 246))';
        signupButton.style.cursor = 'pointer';
        signupButton.innerHTML = 'Sign up';
    }
    finally{
        signupButton.disabled = false;
        signupButton.style.backgroundColor = 'rgb(rgb(59 130 246))';
        signupButton.style.cursor = 'pointer';
        signupButton.innerHTML = 'Sign up';
    }
    

}


async function signIn(email, pwd){

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
                signupButton.removeAttribute('disabled');
                throw new Error(errorData.error);
            }
        } catch (error) {
            toastr.error(error);
        }
    } catch (error) {
        toastr.error(error);
        signupButton.removeAttribute('disabled');
    }

}

form.addEventListener('submit', validateSignUp);
signupButton.addEventListener('click', validateSignUp);