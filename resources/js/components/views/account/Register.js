import React, {useState, useRef} from "react";
import {Link} from "react-router-dom";
import Form from "react-validation/build/form";
import Input from "react-validation/build/input";
import CheckButton from "react-validation/build/button";
import {isEmail} from "validator";
import Layout from "../../Layout"

import AuthService from "../../../services/Authservice";

const required = (value) => {
    if (!value) {
        return (
            <div className="alert alert-danger" role="alert">
                This field is required!
            </div>
        );
    }
};

const validEmail = (value) => {
    if (!isEmail(value)) {
        return (
            <div className="alert alert-danger" role="alert">
                This is not a valid email.
            </div>
        );
    }
};

const validpassword = (value) => {
    if (value.length < 6 || value.length > 40) {
        return (
            <div className="alert alert-danger" role="alert">
                The password must be between 6 and 40 characters.
            </div>
        );
    }
};

const Register = () => {
    const form = useRef();
    const checkBtn = useRef();

    const [email, setEmail] = useState("");
    const [firstname, setFirstname] = useState("");
    const [lastname, setLastname] = useState("");
    const [password, setPassword] = useState("");
    const [confirmedPassword, setConfirmedPassword] = useState("");
    const [successful, setSuccessful] = useState(false);
    const [message, setMessage] = useState("");

    const onChangeFirstname = (e) => {
        const firstname = e.target.value;
        setFirstname(firstname);
    };

    const onChangeLastname = (e) => {
        const lastname = e.target.value;
        setLastname(lastname);
    };

    const onChangeEmail = (e) => {
        const email = e.target.value;
        setEmail(email);
    };

    const onChangePassword = (e) => {
        const password = e.target.value;
        setPassword(password);
    };

    const onChangeConfirmedPassword = (e) => {
        const password = e.target.value;
        setConfirmedPassword(password);
    };

    const handleRegister = (e) => {
        e.preventDefault();

        setMessage("");
        setSuccessful(false);

        form.current.validateAll();

        if (checkBtn.current.context._errors.length === 0) {
            AuthService.register(firstname, lastname, email, password, confirmedPassword).then(
                (response) => {
                    setMessage('Your registration was successful. You are able to login now!');
                    setSuccessful(true);
                },
                (error) => {
                    const resMessage =
                        (error.response &&
                            error.response.data &&
                            error.response.data.status &&
                            error.response.data.status.msg) ||
                        error.message ||
                        error.toString();

                    setMessage(resMessage);
                    setSuccessful(false);
                }
            );
        }
    };

    return (
        <Layout>
            <div className="container min-vh-100">
                <div className="row align-items-center  min-vh-100">
                    <div className="col-3 mx-auto my-auto">
                        <div className="card card-container">
                            <h1 className="mt-2 mx-auto">Sign up now!</h1>
                            <img
                                src="//ssl.gstatic.com/accounts/ui/avatar_2x.png"
                                alt="profile-img"
                                className="profile-img-card"
                            />

                            <Form onSubmit={handleRegister} ref={form}>

                                {message && (
                                    <div className="form-group mt-2">
                                        <div
                                            className={successful ? "alert alert-success" : "alert alert-danger"}
                                            role="alert"
                                        >
                                            {message}
                                        </div>
                                    </div>
                                )}

                                {!successful && (
                                    <div className="mx-2 my-2">

                                        <div className="form-group">
                                            <label htmlFor="firstname">Firstname</label>
                                            <Input
                                                type="text"
                                                className="form-control"
                                                name="firstname"
                                                value={firstname}
                                                onChange={onChangeFirstname}
                                                validations={[required]}
                                            />
                                        </div>

                                        <div className="form-group">
                                            <label htmlFor="lastname">Lastname</label>
                                            <Input
                                                type="text"
                                                className="form-control"
                                                name="lastname"
                                                value={lastname}
                                                onChange={onChangeLastname}
                                                validations={[required]}
                                            />
                                        </div>

                                        <div className="form-group">
                                            <label htmlFor="email">Email</label>
                                            <Input
                                                type="text"
                                                className="form-control"
                                                name="email"
                                                value={email}
                                                onChange={onChangeEmail}
                                                validations={[required, validEmail]}
                                            />
                                        </div>

                                        <div className="form-group">
                                            <label htmlFor="password">Password</label>
                                            <Input
                                                type="password"
                                                className="form-control"
                                                name="password"
                                                value={password}
                                                onChange={onChangePassword}
                                                validations={[required, validpassword]}
                                            />
                                        </div>

                                        <div className="form-group">
                                            <label htmlFor="confirmedPassword">Password confirmed</label>
                                            <Input
                                                type="password"
                                                className="form-control"
                                                name="confirmedPassword"
                                                value={confirmedPassword}
                                                onChange={onChangeConfirmedPassword}
                                                validations={[required, validpassword]}
                                            />
                                        </div>

                                        <div
                                            className="mt-2 form-group align-content-center d-flex justify-content-center">
                                            <button className="btn btn-primary btn-block">Sign Up</button>
                                        </div>

                                        <div className="form-group mt-4">
                                            <hr></hr>
                                            <div className="align-content-center d-flex justify-content-center">
                                                <p>
                                                    Already have an account?
                                                </p>
                                            </div>
                                            <div className="align-content-center d-flex justify-content-center">
                                                <Link to="/login">
                                                    <button className="btn btn-outline-success btn-block">Sign In</button>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {successful == true && (
                                    <div
                                        className="mt-2 mb-2 form-group align-content-center d-flex justify-content-center">
                                        <Link to="/login">
                                            <button className="btn btn-success btn-block">Sign in now!
                                            </button>
                                        </Link>
                                    </div>
                                )}

                                <CheckButton style={{display: "none"}} ref={checkBtn}/>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
};

export default Register;
