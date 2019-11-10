const submit = document.querySelector('#submit');

submit.addEventListener('click', () => {

  const name = document.querySelector('#name').value;
  const owner = document.querySelector('#owner').value;
  const address = document.querySelector('#address').value;
  const phone = document.querySelector('#phone').value;
  const fee = document.querySelector('#fee').value;

  if(!name || !owner || !address || !phone || !fee ) {
    return ;
  }

const carPark = {
    name,
    owner,
    address,
    phone,
    fee
} ;

var settings = {
    "async": true,
    "crossDomain": true,
    "method": "POST",
    "headers": {
      "Content-Type": "application/json",
      "Accept": "*/*",
      "Cache-Control": "no-cache",
      "Accept-Encoding": "gzip, deflate",
      "Content-Length": "141",
      "Connection": "keep-alive",
      "cache-control": "no-cache"
    },
    "processData": false,
    "body": JSON.stringify(carPark)
  }
  
  settings.headers.Authorization = localStorage.getItem('token') ;

  fetch('https://hng-car-park-api.herokuapp.com/api/v1/park', settings )
    .then(response => response.json())
    .then(data => {
        if(data.status) { window.location.replace('./carpark_manage.html'); return}
        return
   }).catch(err => console.log(err)) ;
});
