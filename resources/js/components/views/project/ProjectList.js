import React,{ useState, useEffect} from 'react';
import { Link } from "react-router-dom";
import Layout from "../../Layout"
import Swal from 'sweetalert2'

function ProjectList() {
    const  [projectList, setProjectList] = useState([])

    useEffect(() => {
        $('#create_btn').hide();
        fetchProjectList()
    }, [])

    const fetchProjectList = () => {
        axios.get('/api/projects', {
            headers: {
                'Authorization' : JSON.parse(localStorage.getItem('authdata')).access_token,
            }
        })
            .then(function (response) {
                console.log(response.data)
                setProjectList(response.data);
            })
            .catch(function (error) {
                console.log(error);
            })
            .finally(() => {
                $('#loader').hide();
                $('#create_btn').show();
            })
    }

    const handleDelete = (id) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#loader').show();
                $('#create_btn').hide();
                axios.delete(`/api/projects/${id}`,  { headers: {
                        'Authorization' : JSON.parse(localStorage.getItem('authdata')).access_token,
                    }})
                    .then(function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Project deleted successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        fetchProjectList()
                    })
                    .catch(function (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'An error occured!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            $('#loader').hide();
                            $('#create_btn').show();
                        })
                    });
            }
        })
    }

    return (
        <Layout>
            <div className="container">
                <h2 className="text-center mt-5 mb-3">Laravel Project Manager</h2>
                <div className="card">

                    <div className="card-header" id="create_btn">
                        <Link
                            className="btn btn-outline-primary"
                            to="/project/create">Create New Project
                        </Link>
                    </div>
                    <div id="loader" className="text-center mt-5 mb-3">
                        <div className="spinner-border text-warning" role="status">
                            <span className="sr-only"></span>
                        </div>
                        <p>Loading . . . </p>
                    </div>
                    <div className="card-body">
                        <table className="table table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th width="240px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {projectList.map((project, key)=>{
                                return (
                                    <tr key={key}>
                                        <td>{project.name}</td>
                                        <td>{project.description}</td>
                                        <td>
                                            <Link
                                                to={`/project/show/${project.id}`}
                                                className="btn btn-outline-info mx-1">
                                                Show
                                            </Link>
                                            <Link
                                                className="btn btn-outline-success mx-1"
                                                to={`/project/edit/${project.id}`}>
                                                Edit
                                            </Link>
                                            <button
                                                onClick={()=>handleDelete(project.id)}
                                                className="btn btn-outline-danger mx-1">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                )
                            })}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </Layout>
    );
}

export default ProjectList;
