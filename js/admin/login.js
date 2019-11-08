var evalidated = false;
var pvalidated = false;
function validateMail() {
  var em = email.value;
  var check = em.search("@");
  if (check < 0) {
    emError.innerHTML = "You have not entered a valid email";
    evalidated = false;
  } else {
    emError.innerHTML = "";
    evalidated = true;
  }
}

function validatePasword() {
  var alphanum = /^[0-9a-zA-Z]+$/;

  if (
    // password.value.match(alphanum) &&
    password.value.length > 5 &&
    password.value.length < 15
  ) {
    passError.innerHTML = "";
    pvalidated = true;
  } else {
    passError.innerHTML =
      "Password must incude numbers or characters only";
    pvalidated = false;
  }
}



function login()
{
    const target_form  = document.querySelector(`form#login_form`);
    const form = new FormData(target_form);

    if (form.get('email') == '' || form.get('password') == '') {
      return false;
    }


    axios.defaults.headers.post['Accept'] = 'application/json';
    axios.post(routes.login(), form)
    .then(response => {
       localStorage.setItem('token',  `Bearer ${response.data.data.access_token}`);
        localStorage.setItem('user', JSON.stringify(response.data.data.user));

       if (response.data.data.user.role == 'admin') {
           window.location.replace('/dashboard_overview.html')
       } else {
           window.location.replace('/admin/overview.html');
       }
    })
    .catch(error => {
        let response = error.response.data;
        let msg = '';

        if (error.response.status == '422' || response.hasOwnProperty('errors')) {
            $.each(error.response.data.errors, function (index, item) {
                msg += `<li> ${item[0]} </li>`;
            });
        } else {
            msg = error.response.data.message || error.toString();
        }


        Swal.fire({
            title: `Error`,
            html:  `<p style="color:tomato; font-size:17px;">${msg}</p>`,
            confirmButtonText: 'Close'
        })      

    
    });
}