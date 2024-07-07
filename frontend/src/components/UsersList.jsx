import React, { useState, useEffect } from 'react'
import axios from 'axios'
import AuthService from '../services/AuthService'

const UsersList = () => {
  const [users, setUsers] = useState([])

  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const token = AuthService.getCurrentUser();
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        const response = await axios.get('http://localhost:8000/users')
        console.log(response.data)
        setUsers(response.data)
      } catch (error) {
        console.error('Error fetching users:', error)
      }
    }

    fetchUsers()
  }, [])

  return (
    <div className="container mx-auto py-8">
      <h1 className="text-3xl font-bold mb-4">Users List</h1>
      <ul className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        {users.map((user) => (
          <li
            key={user.id}
            className="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300"
          >
            <h2 className="text-xl font-semibold mb-2">{user.firstname}</h2>
            <h2 className="text-xl font-semibold mb-2">{user.lastname}</h2>
            <p className="text-gray-600">Email: {user.email}</p>
            {/* Add more user details as needed */}
          </li>
        ))}
      </ul>
    </div>
  )
}
        

export default UsersList
