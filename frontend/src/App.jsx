import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Route, Routes, Navigate } from 'react-router-dom';
import Login from './pages/Login';
import Register from './pages/Register';
import BackofficeLayout from './components/BackofficeLayout';
import AuthService from './services/AuthService';

const App = () => {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [navigation, setNavigation] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [currentNav, setCurrentNav] = useState(null);


  useEffect(() => {
    const token = AuthService.getCurrentUser();
    if (token) {
      setIsAuthenticated(true);
    }
    setIsLoading(false);
  }, []);

  return (
    <Router>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route
          path="/backoffice"
          element={
            isAuthenticated ? (
              <BackofficeLayout onNavClick={setCurrentNav} />
            ) : (
              <Navigate to="/login" replace />
            )
          }
        />
      </Routes>
    </Router>
  );
};

export default App;
