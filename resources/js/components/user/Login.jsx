import React, { useState } from 'react';
import axios from 'axios';
import { Link, useNavigate } from 'react-router-dom';

const Login = () => {
    const navigate = useNavigate(); 
    const [login, setLogin] = useState({
        email: '',
        password: '',
    });
    const [showSuccess, setshowSuccess] = useState(false); 
    const [showFailed, setshowFailed] = useState(false);

    const handleInput = (event) => {
        setLogin({ ...login, [event.target.name]: event.target.value });
    };

    const handleSubmit = async (event) => {
        event.preventDefault();
        try {
            const response = await axios.post('/login', login);
            console.log(response);
            setshowSuccess(true); // Show toast on successful login
            navigate('/dashboard');
            // window.location.href = '/dashboard';
        } catch (error) {
          setshowFailed(true);
        }
    };

    return (
        <div className="flex text-center justify-center items-center h-screen bg-gray-200">
          <div className="card card-compact bg-white shadow-xl w-full max-w-md p-6 border-t-4 border-blue-500">
            <div className="card-body text-center">
              <h1 className="text-xl font-semibold mb-1">Login</h1>
              <p className="text-gray-600 mb-4">Sign in to start your session</p>
              <form onSubmit={handleSubmit}>
              <input type="email" placeholder="Email Address" onChange={handleInput} name="email" id="email" autoComplete="off" className="input input-primary w-full" required={true}/>
              <input type="password" placeholder="Password" onChange={handleInput} name="password" id="password" autoComplete="off" className="input input-primary w-full mt-4" required={true}/>
              <div className="flex justify-between mt-4">
                <div>
                <Link to="/forgot-password" className="text-xs text-blue-600">Forgot Password?</Link>
                </div>
                <div>
                <Link to="/register" className="text-xs text-blue-600">Register</Link>
                </div>
              </div>
              <div className="card-actions flex justify-end mt-4">
                <button className="btn btn-primary w-full">Sign in</button>
              </div>
              </form>
            </div>
          </div>

          {showSuccess && (
            <div className="toast toast-top toast-center">
              <div className="alert alert-success">
                <span className="text-white">Login successful!</span>
              </div>
            </div>
          )}

          {showFailed && (
            <div className="toast toast-top toast-center">
              <div className="alert alert-error">
                <span className="text-white">Login Failed!</span>
              </div>
            </div>
          )}
        </div>
    );
};

export default Login;
