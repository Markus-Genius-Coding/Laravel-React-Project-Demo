import React, {useState, useEffect} from 'react';
import {Link, useParams} from "react-router-dom";
import Layout from "../../Layout"
import Swal from 'sweetalert2'

function ProjectEdit() {
    const [id, setId] = useState(useParams().id)
    const [name, setName] = useState('');
    const [description, setDescription] = useState('')
    const [isSaving, setIsSaving] = useState(false)


    useEffect(() => {
        $('#content').hide();
        axios.get(`/api/projects/${id}`,  { headers: {
            'Authorization' : JSON.parse(localStorage.getItem('authdata')).access_token,
        }})
            .then(function (response) {
                let project = response.data
                setName(project.name);
                setDescription(project.description);
            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'An error occured!',
                    showConfirmButton: false,
                    timer: 1500
                })
            })
            .finally(() => {
                $('#loader').hide();
                $('#content').show();
            })

    }, [])


    const handleSave = () => {
        $('#loader').show();
        setIsSaving(true);
        axios.patch(`/api/projects/${id}`, {
            name: name,
            description: description
        },  { headers: {
                'Authorization' : JSON.parse(localStorage.getItem('authdata')).access_token,
            }})
            .then(function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Project updated successfully!',
                    showConfirmButton: false,
                    timer: 1500
                })
                .then((result) => {
                    $('#loader').hide();
                    window.location = "/project/list"
                })
                setIsSaving(false);
            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'An error occured!',
                    showConfirmButton: false,
                    timer: 1500
                })
                .then((result) => {
                    $('#loader').hide();
                })
                setIsSaving(false)
            });
    }


    return (
        <Layout>
            <div className="container">
                <h2 className="text-center mt-5 mb-3">Edit Project</h2>
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
                            to="/project/list">View All Projects
                        </Link>
                    </div>
                    <div className="card-body">
                        <form>
                            <div className="form-group">
                                <label htmlFor="name">Name</label>
                                <input
                                    onChange={(event) => {
                                        setName(event.target.value)
                                    }}
                                    value={name}
                                    type="text"
                                    className="form-control"
                                    id="name"
                                    name="name"/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="description">Description</label>
                                <textarea
                                    value={description}
                                    onChange={(event) => {
                                        setDescription(event.target.value)
                                    }}
                                    className="form-control"
                                    id="description"
                                    rows="3"
                                    name="description"></textarea>
                            </div>
                            <button
                                disabled={isSaving}
                                onClick={handleSave}
                                type="button"
                                className="btn btn-outline-success mt-3">
                                Update Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </Layout>
    );
}

export default ProjectEdit;
