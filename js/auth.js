let accesstoken = loa=localStorage.getItem('token');
if (!accesstoken) {
    location.replace('/admin/index.html');
}

// to verify the user profile
// attempt to get user details
let request = new Request(routes.user(), {
    headers: new Headers({
        'Accept': 'application/json',
        'Authorization': accesstoken,
    }),
});
fetch(request)
    .then(() => {
        const bearerToken = accesstoken;
    })
.catch(error => {
    // redirect to login
    return window.location.replace(`/admin/index.html`);
});