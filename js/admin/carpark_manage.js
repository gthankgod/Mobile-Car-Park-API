const firsts = document.querySelector('#carparks');
const data = document.querySelector('#data');
const mobile = document.querySelector('.mobile');

let settings = {
    "async": true,
    "crossDomain": true,
    "method": "GET",
    "headers": {
      "Content-Type": "application/json",
      "Accept": "*/*",
    }
  }
  
  const token = localStorage.getItem('token') ;

  settings.headers.Authorization = token ;

  fetch('https://hng-car-park-api.herokuapp.com/api/v1/park/my-parks', settings )
    .then(response => response.json())
    .then(carpark => {
        const { result } = carpark ;
       value = '';
       result.forEach(b => {
          const div = document.createElement('div');
          div.className = 'texts garden-head first';
           desktopValue = `
        <div class="width carpark">${b.name}</div>
        <div class="width" id="carparkOwner">${b.owner}</div>
        <div class="width" id="address">${b.address}</div>
        <div class="width" id="phone">${b.phone}</div>
        <div class="width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572539694/ion-checkmark-circle-sharp_dudg85.png"></div>
        <div class="width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572539892/Ellipse_496_oalgod.png"></div>
        <div class="width"><img class="pointer delete" src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572540004/ic-baseline-delete-forever_ogjvcv.png"></div>
        `;
        div.innerHTML = desktopValue;
        div.addEventListener('click', (e) => {
          console.log(e.target.className)
          if(e.target.className.includes('delete')) {
            let settings = {
              "async": true,
              "crossDomain": true,
              "method": "DELETE",
              "headers": {
                "Content-Type": "application/json",
                "Accept": "*/*",
              }
            };
            settings.headers.Authorization = token;
            fetch(`https://hng-car-park-api.herokuapp.com/api/v1/park/${b.id}`,settings);
            e.target.parentElement.parentElement.remove();
          }
        })
        data.append(div);
        // mobileValue = `
        //             <div class="mobile-display texts">
        //                         <div class="mobile-width sub-heads">Name of car park</div>
        //                         <div class="mobile-width">${b.name}</div>
        //                     </div>
        //                     <hr>
        //                     <div class="mobile-display texts">
        //                         <div class="mobile-width sub-heads">Name of car park's owner</div>
        //                         <div class="mobile-width">Mr. Charles</div>
        //                     </div>
        //                     <hr>
        //                     <div class="mobile-display texts">
        //                         <div class="mobile-width sub-heads">Address</div>
        //                         <div class="mobile-width">Lekki Phase 1</div>
        //                     </div>
        //                     <hr>
        //                     <div class="mobile-display texts">
        //                         <div class="mobile-width sub-heads">Phone Number</div>
        //                         <div class="mobile-width">08176253638</div>
        //                     </div>
        //                     <hr>
        //                     <div class="mobile-display texts">
        //                         <div class="mobile-width sub-heads">Active</div>
        //                         <div class="mobile-width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572539694/ion-checkmark-circle-sharp_dudg85.png"></div>
        //                         <div class="mobile-width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572539892/Ellipse_496_oalgod.png"></div>
        //                     </div>
        //                     <hr>
        //                     <div class="mobile-display texts">
        //                         <div class="mobile-width sub-heads">Deactivate</div>
        //                         <div class="mobile-width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572539892/Ellipse_496_oalgod.png"></div>
        //                         <div class="mobile-width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572540097/Vector_2_rsr984.png"></div>
        //                     </div>
        //                     <hr>
        //                     <div class="mobile-display texts">
        //                         <div class="mobile-width sub-heads"></div>
        //                         <div class="mobile-width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572540004/ic-baseline-delete-forever_ogjvcv.png"></div>
        //                         <div class="mobile-width"><img class="pointer"  src="https://res.cloudinary.com/dfjzditzc/image/upload/v1572540004/ic-baseline-delete-forever_ogjvcv.png"></div>
        //                     </div>
        //                     <hr>
        // `
       });
      
   }).catch(err => console.log(err)) ;