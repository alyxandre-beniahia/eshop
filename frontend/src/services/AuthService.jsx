import axios from 'axios';

const API_URL = 'http://localhost:8000/';

const AuthService = {
  login: async (credentials) => {
    try {
      const response = await axios.post(`${API_URL}login`, credentials);
      if (response.data.token) {
        localStorage.setItem('token', response.data.token);
      }
      return response.data;
    } catch (error) {
      console.error('Error logging in:', error);
      throw error;
    }
  },

  register: async (userData) => {
    try {
      const response = await axios.post(`${API_URL}users`, userData);
      return response.data;
    } catch (error) {
      console.error('Error registering user:', error);
      throw error;
    }
  },

  logout: () => {
    localStorage.removeItem('token');
  },

  getCurrentUser: () => {
    return localStorage.getItem('token');
  },
};

export default AuthService;
