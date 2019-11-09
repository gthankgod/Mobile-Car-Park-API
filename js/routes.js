class Routes {
    constructor() {
        this._origin = "https://hng-car-park-api.herokuapp.com/api/v1";
    }

    origin() {
        return this._origin;
    }

    loginAdmin() {
        return `${this._origin}/auth/login`;
    };

    loginUser() {
        return `${this._origin}/auth/login/user-email`;
    }

    user() {
        return `${this._origin}/user`;
    }
}

const routes = new Routes;