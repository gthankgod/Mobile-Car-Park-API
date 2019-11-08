let adminToken = loa=localStorage.getItem('token');
if (!adminToken) {
    location.replace('/admin/login.html');
}

// to verify the user profile
// attempt to get user details
let request = new Request(routes.user(), {
    headers: new Headers({
        'Accept': 'application/json',
        
    }),
});
fetch(request)
.catch(error => {
   console.log(error);
});