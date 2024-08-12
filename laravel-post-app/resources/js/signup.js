const form = document.getElementById('sign-up-form');
const signupButton = document.getElementById('signup-btn');

async function validateSignUp(event){
    event.preventDefault();

    let name_ = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let pwd = document.getElementById('pwd');
    let pwdRepeat = document.getElementById('pwd_rpt');

    try {
        if(name_.length < 1 || email.length < 1 ||pwd.length < 1 || pwdRepeat.length < 1){
            throw new Error('Fill in all fields.');
        }
        if(pwd.value != pwdRepeat.value){
            throw new Error('passwords do not match.');
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
                const data = await response;
                // window.location.href = '/home'
                console.log(data);
            } else {
                // Handle errors (e.g., show an error message)
                const errorData = await response;
                // console.log(errorData);
                throw new Error(errorData);
            }
        } catch (error) {
            // Handle network errors
            console.error('Network Error:', error);
        }
    } catch (error) {
        // toastr.error(error)
        console.error(error)
    }

}

form.addEventListener('submit', validateSignUp);
signupButton.addEventListener('click', validateSignUp);