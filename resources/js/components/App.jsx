import React from 'react';
import { HashRouter, Route, Routes } from "react-router-dom";
import Login from './user/Login.jsx';
import Dashboard from './dashboard/Dashboard.jsx';
import Registration from './user/Registration.jsx';
import ForgetPassword from './user/ForgetPassword.jsx';
import Index from './dashboard/Index.jsx';

function App() {
    return (
        <HashRouter>
        <Routes>
          <Route path="/" element={<Login/>}/>
          <Route path="/dashboard" element={<Dashboard/>}>
              <Route path="/dashboard" element={<Index />} />
          </Route>
          <Route path="/register" element={<Registration/>}/>
          <Route path='/forgot-password' element={<ForgetPassword/>}/>
        </Routes>
    </HashRouter>
    );
}

export default App;