axios.defaults.headers.post['Accept'] = 'application/json';
axios.defaults.headers.common['Authorization'] = bearerToken;

//fetch stats
axios.get(routes.overviewStats()).then(response  => {
    let data = response.data;
   $(`#existing_users`).text(data.users);
   $(`#new_users`).text(data.new_users);
   $(`#issues`).text(data.issues);
   $(`#car_parks`).text(data.parks.count);
}).catch(error => {
    console.error(error.response.data);
});