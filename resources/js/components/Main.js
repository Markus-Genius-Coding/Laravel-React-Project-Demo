import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Home from "./views/home/Home";
import ProjectList from "./views/project/ProjectList"
import ProjectCreate from "./views/project/ProjectCreate"
import ProjectEdit from "./views/project/ProjectEdit"
import ProjectShow from "./views/project/ProjectShow"
import Register from "./views/account/Register";
import Login from "./views/account/Login";

function Main() {
    return (
        <Router>
            <Routes>
                <Route path="/"  element={<Home/>} />
                <Route path="/register"  element={<Register/>} />
                <Route path="/login"  element={<Login/>} />

                <Route exact path="/project/list"  element={<ProjectList/>} />
                <Route path="/project/create"  element={<ProjectCreate/>} />
                <Route path="/project/edit/:id"  element={<ProjectEdit/>} />
                <Route path="/project/show/:id"  element={<ProjectShow/>} />
            </Routes>
        </Router>
    );
}

export default Main;

if (document.getElementById('app')) {
    ReactDOM.render(<Main />, document.getElementById('app'));
}
