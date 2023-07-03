import axios from "axios";

const API_URL = "api/user/"

const register = (firstname, lastname, email, password, confirmedPassword) => {
    return axios.post(API_URL + "register", {
        firstname: firstname,
        lastname: lastname,
        email: email,
        password: password,
        confirmed_password: confirmedPassword,
    });
};

const login = (email, password) => {
    return axios
        .post(API_URL + "login", {
            email: email,
            password: password,
        })
        .then(response => {
            console.log(response)
            if (response.data.userdata && response.data.authdata) {
                localStorage.setItem("userdata", JSON.stringify(response.data.userdata));
                localStorage.setItem("authdata", JSON.stringify(response.data.authdata));
            }

            return response.data;
        });
    ;
};

const AuthService = {
    register,
    login,
}

export default AuthService;
