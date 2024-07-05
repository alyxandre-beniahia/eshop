import React from 'react';
import { FaBars, FaSearch, FaBell } from 'react-icons/fa';

const Header = ({ onToggleSidebar }) => {
  return (
    <header className="bg-white shadow-md py-4 px-6 flex justify-between items-center z-10">
      <div className="flex items-center">
        <button
          className="mr-4 text-blue-600 hover:text-blue-800 focus:outline-none"
          onClick={onToggleSidebar}
        >
          <FaBars className="h-6 w-6" />
        </button>
      </div>
      <div className="flex items-center">
        <FaBell className="text-gray-400 mr-4" />
        <div className="relative">
          <img
            src="https://via.placeholder.com/40"
            alt="User Avatar"
            className="rounded-full w-10 h-10"
          />
          <span className="absolute top-0 right-0 h-3 w-3 bg-green-500 rounded-full"></span>
        </div>
      </div>
    </header>
  );
};

export default Header;
