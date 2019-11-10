function login() {
    toastr.options.timeOut = 0;
    const loginBtn = $(`#login-btn`);
    $(loginBtn).text(`Processing...`).attr(`dabbled`, true);
    const target_form = document.querySelector(`form#login_form`);
    const form = new FormData(target_form);

    if (form.get('email') == '' || form.get('password') == '') {
        toastr.error('The email and password fields are required');
        $(loginBtn).text(`Login`).attr(`dabbled`, false);
        return false;
    }


    axios.defaults.headers.post['Accept'] = 'application/json';
    axios.post(routes.loginUser(), form)
        .then(response => {
            localStorage.setItem('token', `Bearer ${response.data.data.access_token}`);
            localStorage.setItem('user', JSON.stringify(response.data.data.user));

            let role = response.data.data.user.role;
            if (role === 'user') {
                window.location.replace('/users/car-park-search.html');
            }
        })
        .catch(error => {
            let response = error.response.data;
            let msg = '';

            if (error.response.status == '422' || response.hasOwnProperty('errors')) {
                $.each(error.response.data.errors, function(index, item) {
                    msg += `<li> ${item[0]} </li>`;
                });
            } else {
                msg = error.response.data.message || error.toString();
            }


            toastr.error(`<p style="font-size:17px;">${msg}</p>`);

            $(loginBtn).text(`Login`).attr(`dabbled`, false);

        });
}