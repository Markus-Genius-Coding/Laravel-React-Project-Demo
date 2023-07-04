import React, {useState, useEffect} from 'react';
import { Link, useParams } from "react-router-dom";
import Layout from "../../Layout"
import AuthService from "../../../services/Authservice";

function ProjectShow() {
    const [id, setId] = useState(useParams().id)
    const [project, setProject] = useState({name:'', description:'', created_at:''})
    useEffect(() => {
        $('#loader').show();
        $('#content').hide();
        axios.get(`/api/projects/${id}`,  { headers: {
                'Authorization' : JSON.parse(localStorage.getItem('authdata')).access_token,
            }})
            .then(function (response) {
                setProject(response.data)
                $('#loader').hide();
                $('#content').show()
            })
            .catch(function (error) {
                $('#loader').hide();
                console.log(error);
                if (error.response.status == 401 || error.response.status == 403) {
                    AuthService.logout()
                }
            })
    }, [])

    return (
        <Layout>
            <div className="container">
                <h2 className="text-center mt-5 mb-3">Show Project</h2>
                <div id="loader" className="text-center mt-5 mb-3">
                    <div className="spinner-border text-warning" role="status">
                        <span className="sr-only"></span>
                    </div>
                    <p>Loading . . . </p>
                </div>
                <div className="card" id="content">
                    <div className="card-header">
                        <Link
                            className="btn btn-outline-info float-right"
                            to="/project/list"> View All Projects
                        </Link>
                    </div>
                    <div className="card-body">
                        <b className="text-muted">Name:</b>
                        <p>{project.name}</p>
                        <b className="text-muted">Description:</b>
                        <p>{project.description}</p>
                        <b className="text-muted">Created at:</b>
                        <p>{project.created_at}</p>
                    </div>
                </div>
            </div>
        </Layout>
    );
}

export default ProjectShow;
