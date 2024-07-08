// Header.jsx
import React from 'react';
import { Link } from 'react-router-dom';

const Header = () => {
  return (
    <header className="bg-gray-800 py-4">
      <div className="container mx-auto flex justify-between items-center">
        <h1 className="text-white text-2xl font-bold">
          <Link to="/">betterthisthannaked</Link>
        </h1>
        <nav>
          <ul className="flex space-x-4">
            <li>
              <Link to="/products" className="text-white hover:text-gray-300">
                All Products
              </Link>
            </li>
          </ul>
        </nav>
      </div>
    </header>
  );
};

export default Header;
