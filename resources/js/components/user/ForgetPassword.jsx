import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

const ForgetPassword = () => {
    const [emailAddress, setEmailAddress] = useState('');
    const [otp, setOtp] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [showOtp, setShowOtp] = useState(false);
    const [showPasswordFields, setShowPasswordFields] = useState(false);
    const [showSuccess, setshowSuccess] = useState(false); 
    const [showFailed, setshowFailed] = useState(false);

    const handleEmailChange = (event) => {
        setEmailAddress(event.target.value);
    };

    const handleOtpChange = (event) => {
        setOtp(event.target.value);
    };

    const handlePasswordChange = (event) => {
        setPassword(event.target.value);
    };

    const handleConfirmPasswordChange = (event) => {
        setConfirmPassword(event.target.value);
    };

    const handleEmailSubmit = async (event) => {
        event.preventDefault();
        try {
            const response = await axios.post('/verify-email', { email: emailAddress });
            // console.log(response);
            setshowFailed(false);
            if(response.data.responseCode === 1){
                setShowOtp(true);
            }else{
                setshowFailed(true);
            }
        } catch (error) {
            setshowFailed(true);
            window.location.href='/forgot-password';
        }
    };

    const handleOtpSubmit = async (event) => {
        event.preventDefault();
        try {
            const response = await axios.post('/verify-otp', { email: emailAddress, otp: otp });
            // console.log(response);
            setshowFailed(false);
            if(response.data.responseCode === 1){
                setShowPasswordFields(true);
            }else{
                setshowFailed(true);
            }
        } catch (error) {
            setshowFailed(true);
            window.location.href='/forgot-password';
        }
    };

    const handlePasswordSubmit = async (event) => {
        event.preventDefault();
        try {
            const response = await axios.post('/change-pass', { email: emailAddress, password: password });
            // console.log(response);
            setshowFailed(false);
            setshowSuccess(true);
            window.location.href='/';

        } catch (error) {
            setshowFailed(true);
            window.location.href='/forgot-password';
        }
    };

    return (
        <div className="flex text-center justify-center items-center h-screen bg-gray-200">
            <div className="card card-compact bg-white shadow-xl w-full max-w-md p-6 border-t-4 border-blue-500">
                <div className="card-body text-center">
                    <h1 className="text-xl font-semibold mb-1">Forget Password</h1>
                    <p className="text-gray-600 mb-4">You forgot your password? Here you can easily retrieve a new password.</p>
                    {!showOtp ? (
                        <form onSubmit={handleEmailSubmit}>
                            <input type="email" placeholder="Email Address" value={emailAddress} onChange={handleEmailChange} autoComplete="off" className="input input-primary w-full" required={true}/>
                            <div className="card-actions flex justify-end mt-4">
                                <button className="btn btn-primary w-full">Verify Email</button>
                            </div>
                        </form>
                    ) : !showPasswordFields ? (
                        <form onSubmit={handleOtpSubmit}>
                            <input type="text" placeholder="Enter OTP" value={otp} onChange={handleOtpChange} autoComplete="off" className="input input-primary w-full" required={true}/>
                            <div className="card-actions flex justify-end mt-4">
                                <button className="btn btn-primary w-full">Verify OTP</button>
                            </div>
                        </form>
                    ) : (
                        <form onSubmit={handlePasswordSubmit}>
                            <input type="password" placeholder="Password" value={password} onChange={handlePasswordChange} autoComplete="off" className="input input-primary w-full" required={true}/>
                            <input type="password" placeholder="Confirm Password" value={confirmPassword} onChange={handleConfirmPasswordChange} autoComplete="off" className="input input-primary w-full mt-4" required={true}/>
                            <div className="card-actions flex justify-end mt-4">
                                <button className="btn btn-primary w-full">Reset Password</button>
                            </div>
                        </form>
                    )}
                    <div className="flex justify-between mt-4">
                        <div>
                            <Link to="/" className="text-lg text-blue-600">Login</Link>
                        </div>
                        <div>
                            <Link to="/register" className="text-lg text-blue-600">Register</Link>
                        </div>
                    </div>
                </div>
            </div>
            {showSuccess && (
            <div className="toast toast-top toast-center">
              <div className="alert alert-success">
                <span className="text-white">Password Reset Successful!</span>
              </div>
            </div>
            )}

            {showFailed && (
                <div className="toast toast-top toast-center">
                <div className="alert alert-error">
                    <span className="text-white">Wrong input!</span>
                </div>
                </div>
            )}
        </div>
    );
};

export default ForgetPassword;
