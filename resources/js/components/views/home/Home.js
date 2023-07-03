import React,{ useState, useEffect} from 'react';
import { Link } from "react-router-dom";
import Layout from "../../Layout"
import Swal from 'sweetalert2'

function Home() {
    const  [projectList, setProjectList] = useState([])

    useEffect(() => {
        checkAuth()
    }, [])

    const checkAuth = () => {

        if (localStorage.getItem('authdata') != null && JSON.parse(localStorage.getItem('authdata')).access_token != null) {
            window.location = "/project/list"
        } else {
            window.location = "/login"
        }

    }
    return (<Layout></Layout>);
}

export default Home;
