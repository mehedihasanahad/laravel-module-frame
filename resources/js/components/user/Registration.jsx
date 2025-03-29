import React, { useEffect, useState } from 'react';
import { AllDivision,Districts,Upzila } from '../../APIRequest/APIRequest';
import axios from 'axios';
import { Link,useNavigate } from 'react-router-dom';

const Registration = () => {
    const [showSuccess, setshowSuccess] = useState(false); 
    const [showFailed, setshowFailed] = useState(false);
    let [divisions,SetDivision]=useState([]);
    const [selectedDivision, setSelectedDivision] = useState('');
    const [districts, setDistricts] = useState([]);
    const [selectedDistrict, setselectedDistrict] = useState('');
    const [upzila, setupzila] = useState([]);
    const [formData, setFormData] = useState({
      name: '',
      email: '',
      division_id: '',
      district_id: '',
      upzila_id: '',
      mobile: '',
      national_id: '',
      terms_and_condition: 'off' 
    });

    useEffect(()=>{
        (async()=>{
          let res = await AllDivision();
          SetDivision(res.divisions);
        })()
    },[])

    const handleDivisionChange = async (event) => {
      const selectedDivisionId = event.target.value;
      setSelectedDivision(selectedDivisionId);
      try {
        const res = await Districts(selectedDivisionId);
        setDistricts(res.data);
      } catch (error) {
        console.error("Error fetching districts:", error);
      }
    };

    const handleDistrictChange = async (event) => {
      const selectedDistrictId = event.target.value;
      setselectedDistrict(selectedDistrictId);
      try {
        const res = await Upzila(selectedDistrictId);
        setupzila(res.data);
      } catch (error) {
        console.error("Error fetching districts:", error);
      }
    };

    const handleInputChange = (event) => {
      const { name, value } = event.target;
      setFormData({ ...formData, [name]: value });
    };
    

    const handleSubmit = async (event) => {
      event.preventDefault();
      try {
        const response = await axios.post('/register', formData);
        if(response.status===200){
          setshowSuccess(true);
          window.location.href='/';
        }
      } catch (error) {
        setshowFailed(true);
      }
    };

    return (
        <div className="flex text-center justify-center items-center h-screen bg-gray-200">
        <div className="card card-compact bg-white shadow-xl w-full max-w-md p-6 border-t-4 border-blue-500">
          <div className="card-body text-center">
            <h1 className="text-xl font-semibold mb-1">Sign UP</h1>
            <p className="text-gray-600 mb-4">Register a new membership</p>
            <form onSubmit={handleSubmit}>
            <input type="text" placeholder="Full Name" name="name" id="name" autoComplete="off" onChange={handleInputChange} className="input input-primary w-full mb-4" required={true}/>
            <input type="email" placeholder="Email Address" name="email" id="email" autoComplete="off" onChange={handleInputChange} className="input input-primary w-full mb-4" required={true}/>
            <select className="select select-primary w-full mb-4" onChange={(event) => { handleDivisionChange(event); handleInputChange(event); }} name='division_id'>
            <option disabled selected>Select Division</option>
            {
              divisions.map((division, index) => (
                <option key={index} value={division.id}>{division.area_nm}</option>
              ))
            }
            </select>
            <select className="select select-primary w-full mb-4" onChange={(event) => { handleDistrictChange(event); handleInputChange(event); }} name='district_id'>
            <option disabled selected>Select District</option>
              {
                districts.map((data) => (
                  <option key={data.id} value={data.id}>{data.area_nm}</option>
                ))
              }
            </select>
            <select className="select select-primary w-full mb-4" name='upzila_id' onChange={handleInputChange}>
              <option disabled selected>Select Upzila</option>
              {
                upzila.map((data) => (
                  <option key={data.id} value={data.id}>{data.area_nm}</option>
                ))
              }
            </select>
            <input type="tel" placeholder="Mobile Number" name="mobile" id="mobile" onChange={handleInputChange} autoComplete="off" className="input input-primary w-full mb-4" required={true}/>
            <input type="number" placeholder="NID" name="national_id" id="national_id" autoComplete="off" onChange={handleInputChange} className="input input-primary w-full mb-4" required={true}/>
            <div className="form-control mb-4">
                <label className="flex items-center cursor-pointer">
                <input type="checkbox" name="terms_and_condition" checked={formData.terms_and_condition === 'on'} onChange={handleInputChange} className="form-checkbox h-5 w-5 text-primary" />
                    <span className="ml-2 text-sm">I agree to all statements in Terms of service.</span>
                </label>
            </div>
            <div className="card-actions flex justify-end mt-4">
              <button className="btn btn-primary w-full">Register</button>
            </div>
            </form>
            <div>
                <Link to="/" className="text-xs text-blue-600">Already Have an account?</Link>
            </div>
          </div>
        </div>
        {showSuccess && (
            <div className="toast toast-top toast-center">
              <div className="alert alert-success">
                <span className="text-white">Successful! Check your mail for account credential.</span>
              </div>
            </div>
          )}

          {showFailed && (
            <div className="toast toast-top toast-center">
              <div className="alert alert-error">
                <span className="text-white">Registration Failed!</span>
              </div>
            </div>
          )}
      </div>
    );
};

export default Registration;
