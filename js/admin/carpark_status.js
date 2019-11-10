const data = document.querySelector('#data');

var settings = {
    "async": true,
    "crossDomain": true,
    "method": "GET",
    "headers": {
      "Content-Type": "application/json",
      "Accept": "*/*",
    }
  }
  
  settings.headers.Authorization = localStorage.getItem('token') ;

  fetch('https://hng-car-park-api.herokuapp.com/api/v1/park/activated-parks', settings )
    .then(response => response.json())
    .then(carpark => {
        const { result } = carpark ;
       value = '';
       console.log(result);
       result.forEach(b => {
          const div = document.createElement('div');
          div.className = 'texts garden-head first';
           value = `
        <div class="width carpark">${b.name}</div>
        <div class="width" id="carparkOwner">${b.owner}</div>
        <div class="width" id="address">${b.address}</div>
        <div class="width" id="phone">${b.phone}</div>
        <div class="width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572539694/ion-checkmark-circle-sharp_dudg85.png"></div>
        <div class="width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572539892/Ellipse_496_oalgod.png"></div>
        <div class="width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572540004/ic-baseline-delete-forever_ogjvcv.png"></div>
       <hr>
        `;
        div.innerHTML = value;
        data.append(div);
       });
      
   }).catch(err => console.log(err)) ;