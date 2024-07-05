import React from 'react';
import { FaHome, FaBoxOpen, FaChartBar, FaCog } from 'react-icons/fa';

const Sidebar = ({ showSidebar, onNavClick }) => {
  const handleNavClick = (navItem) => {
    onNavClick(navItem);
  };

  return (
    <div
      className={`bg-gray-800 text-gray-100 flex flex-col justify-between h-screen w-64 px-4 py-6 fixed left-0 top-0 transition-transform duration-300 ${
        showSidebar ? 'translate-x-0' : '-translate-x-full'
      }`}
    >
      {showSidebar && (
        <div>
          <div className="flex items-center justify-between mb-8">
            <span className="text-2xl font-bold">Panel Adlinistrateur</span>
        </div>
          <nav>
            <ul>
              <li className="mb-4">
                <a
                  href="#"
                  className="flex items-center text-gray-300 hover:text-white"
                  onClick={() => handleNavClick('dashboard')}
                >
                  <FaHome className="mr-2" />
                  Tableau de bord
                </a>
              </li>
              <li className="mb-4">
                <a
                  href="#"
                  className="flex items-center text-gray-300 hover:text-white"
                  onClick={() => handleNavClick('products')}
                >
                  <FaBoxOpen className="mr-2" />
                  Produits
                </a>
              </li>
              <li className="mb-4">
                <a
                  href="#"
                  className="flex items-center text-gray-300 hover:text-white"
                  onClick={() => handleNavClick('reports')}
                >
                  <FaChartBar className="mr-2" />
                  Commandes
                </a>
              </li>
              <li className="mb-4">
                <a
                  href="#"
                  className="flex items-center text-gray-300 hover:text-white"
                  onClick={() => handleNavClick('settings')}
                >
                  <FaCog className="mr-2" />
                  Clients
                </a>
              </li>
            </ul>
          </nav>
        </div>
      )}
      <div>
        {/* User profile or other footer content */}
      </div>
    </div>
  );
};

export default Sidebar;
