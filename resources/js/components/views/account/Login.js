import React, {useState, useRef} from "react";
import Form from "react-validation/build/form";
import Input from "react-validation/build/input";
import Layout from "../../Layout"

import AuthService from "../../../services/Authservice";
import {Link} from "react-router-dom";
import CheckButton from "react-validation/build/button";

const required = (value) => {
    if (!value) {
        return (
            <div className="alert alert-danger" role="alert">
                This field is required!
            </div>
        );
    }
};

const Login = () => {
    const form = useRef();
    const checkBtn = useRef();

    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [successful, setSuccessful] = useState(false);
    const [message, setMessage] = useState("");

    const onChangeEmail = (e) => {
        const email = e.target.value;
        setEmail(email);
    };

    const onChangePassword = (e) => {
        const password = e.target.value;
        setPassword(password);
    };

    const handleLogin = (e) => {
        e.preventDefault();

        setMessage("");
        setSuccessful(false);

        form.current.validateAll();

        if (checkBtn.current.context._errors.length === 0) {
            AuthService.login(email, password).then(
                (response) => {
                    setSuccessful(true);
                    window.location = "/project/list"
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
                            <h1 className="mt-2 mx-auto">Login now!</h1>
                            <img
                                src="//ssl.gstatic.com/accounts/ui/avatar_2x.png"
                                alt="profile-img"
                                className="profile-img-card"
                            />

                            <Form onSubmit={handleLogin} ref={form}>
                                <div className="mx-2 my-2">

                                    <div className="form-group">
                                        <label htmlFor="email">Email</label>
                                        <Input
                                            type="text"
                                            className="form-control"
                                            name="email"
                                            value={email}
                                            onChange={onChangeEmail}
                                            validations={[required]}
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
                                            validations={[required]}
                                        />
                                    </div>

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

                                    <div
                                        className="mt-2 form-group align-content-center d-flex justify-content-center">
                                        <button className="btn btn-primary btn-block">Sign in</button>
                                    </div>
                                </div>
                                <CheckButton style={{display: "none"}} ref={checkBtn}/>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
};

export default Login;
