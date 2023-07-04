import { Link } from "react-router-dom";
import Layout from "../../Layout"
import Swal from 'sweetalert2'
import React, { useState, useEffect } from 'react';
import AuthService from "../../../services/Authservice";

function ProjecCreate() {
    const [name, setName] = useState('');
    const [description, setDescription] = useState('')
    const [isSaving, setIsSaving] = useState(false)

    useEffect(() => {
        $('#loader').hide();
    }, [])

    const handleSave = () => {
        $('#loader').show();
        setIsSaving(true);
        axios.post('/api/projects', {
            name: name,
            description: description,
        }, {
            headers: {
                'Authorization' : JSON.parse(localStorage.getItem('authdata')).access_token,
            },
        })
            .then(function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Project saved successfully!',
                    showConfirmButton: false,
                    timer: 1500
                })
                .then((result) => {
                    window.location = "/project/list"
                })
                $('#loader').hide();
                setIsSaving(false);
                setName('')
                setDescription('')
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
                    if (error.response.status == 401 || error.response.status == 403) {
                        AuthService.logout()
                    }
                })
                setIsSaving(false)
            });
    }

    return (
        <Layout>
            <div className="container">
                <h2 className="text-center mt-5 mb-3">Create New Project</h2>
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
                                    onChange={(event)=>{setName(event.target.value)}}
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
                                    onChange={(event)=>{setDescription(event.target.value)}}
                                    className="form-control"
                                    id="description"
                                    rows="3"
                                    name="description"></textarea>
                            </div>
                            <button
                                disabled={isSaving}
                                onClick={handleSave}
                                type="button"
                                className="btn btn-outline-primary mt-3">
                                Save Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </Layout>
    );
}

export default ProjecCreate;
