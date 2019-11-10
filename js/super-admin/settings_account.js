const updateAccount = () => {

    const url = "https://hng-car-park-api.herokuapp.com/api/v1/user";

    const fname = document.forms['settings_account']['fname'].value;
    const lname = document.forms['settings_account']['lname'].value;
    const email = document.forms['settings_account']['email'].value;
    const phone = ' 08066668888';
    const data = {
        first_name: fname,
        last_name: lname,
        email: email,
        phone: phone
    };
    makePutRequest(url, data)
};


const makePutRequest = (url, data) => {

    return fetch(url, {
        method: "PUT",
        headers: authHeaders(),
        body: JSON.stringify(data)
    }).then(response => {
        if (response.ok){
            swal.fire('Account Updated");
            return response.json()
        } else {
            swal.fire('Account Update failed")
        }
    }).catch(error => {
        swal.fire("Error occurred");
    })
};


const authHeaders = () => {
    let token = localStorage.getItem('token');

    return {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    }
};

const changePassword = () => {
    passwordPutRequest();
};


const passwordPutRequest = () => {
    const url = "https://hng-car-park-api.herokuapp.com/api/v1/user/password";
    const { value: data } = Swal.fire({
        title: 'Change Password',
        html:
            '<input id="old_password" class="swal2-input" placeholder="Old Password">' +
            '<input id="new_password" class="swal2-input" placeholder="New Password">' +
            '<input id="new_password_confirmation" class="swal2-input" placeholder="Confirm New password">',
        focusConfirm: false,
        preConfirm: () => {
            return [
                document.getElementById('old_password').value,
                document.getElementById('new_password').value,
                document.getElementById('new_password_confirmation').value
            ]
        }
    }).then(values => {
        const data = values.value;
        const old_password = data[0];
        const new_password = data[1];
        const new_password_confirmation = data[2];
        const pass = {
            old_password: old_password,
            new_password: new_password,
            new_password_confirmation: new_password_confirmation
        };

        makePutRequest(url, pass);
    })
}