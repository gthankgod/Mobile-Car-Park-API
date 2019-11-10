const submit = document.querySelector('#submit');

submit.addEventListener('click', () => {

    const name = document.querySelector('#name').value;
    const firstname = name.split(' ')[0];
    const lastname = name.split(' ')[1];
    const email = document.querySelector('#email').value;
    const password = document.querySelector('#password').value;
    const password_confirmation = document.querySelector('#password2').value;

  if(!name || !email || !password || password !== password_confirmation) {
    return ;
  }

const partner = {
    firstname,
    lastname,
    email,
    password,
    password_confirmation
} ;
let settings = {
    "async": true,
  "crossDomain": true,
  "method": "POST",
  "headers": {
    "Content-Type": "application/json",
    "Accept": "application/json",
  },
  "data": JSON.stringify(partner)
  }

  fetch('https://hng-car-park-api.herokuapp.com/api/v1/auth/register/partner', settings )
    .then(response => response.json())
    .then(data => {
        if(data.message === "Account has been created") { window.location.replace('../../admin/login.html'); return}
        return
   }).catch(err => console.log(err)) ;
});
