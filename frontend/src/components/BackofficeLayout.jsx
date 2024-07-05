// BackofficeLayout.jsx
import React, { useState } from 'react';
import Sidebar from './Sidebar';
import Header from './Header';
import ProductList from './ProductList'; // Import the ProductList component

const BackofficeLayout = ({ onNavClick }) => {
  const [showSidebar, setShowSidebar] = useState(true);
  const [currentNav, setCurrentNav] = useState(null); // Add the currentNav state

  const toggleSidebar = () => {
    setShowSidebar(!showSidebar);
  };

  const handleNavClick = (navItem) => {
    setCurrentNav(navItem); // Update the currentNav state with the clicked navigation item
  };

  return (
    <div className="flex h-screen bg-gray-100">
      <Sidebar showSidebar={showSidebar} onNavClick={handleNavClick} />
      <div className={`flex-1 flex flex-col ${showSidebar ? 'ml-64' : ''}`}>
        <Header onToggleSidebar={toggleSidebar} />
        <main className="flex-1 p-6 overflow-y-auto">
          {currentNav === 'products' && <ProductList />}
          {/* Other components or content */}
        </main>
      </div>
    </div>
  );
};

export default BackofficeLayout;
