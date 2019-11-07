const carpark = document.querySelector('#carpark');
const carparkOwners = document.querySelector('#carparkOwners');
const address = document.querySelector('#address');
const phone = document.querySelector('#phone');

const firsts = document.querySelector('#carparks');

var settings = {
    "async": true,
    "crossDomain": true,
    "method": "GET",
    "headers": {
      "Content-Type": "application/json",
      "Accept": "*/*",
    }
  }
  
  settings.headers.Authorization = `Bearer ${localStorage.getItem('token')}` ;

  fetch('https://hng-car-park-api.herokuapp.com/api/v1/park', settings )
    .then(response => response.json())
    .then(carpark => {
        const {result } = carpark ;
        console.log(result);
       value = '';
       result.forEach(b => {
           value = `
        <div class="width carpark">${b.name}</div>
        <div class="width" id="carparkOwner">${b.owner}</div>
        <div class="width" id="address">${b.address}</div>
        <div class="width" id="phone">${b.phone}</div>
        <div class="width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572539694/ion-checkmark-circle-sharp_dudg85.png"></div>
        <div class="width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572539892/Ellipse_496_oalgod.png"></div>
        <div class="width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572540004/ic-baseline-delete-forever_ogjvcv.png"></div>
        `;
        firsts.innerHTML = value;
       });
      
   }).catch(err => console.log(err)) ;