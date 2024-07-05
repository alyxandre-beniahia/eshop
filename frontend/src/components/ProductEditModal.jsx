import React, { useState } from 'react';

const ProductEditModal = ({ product, onUpdate, onCancel }) => {
  const [editedProduct, setEditedProduct] = useState({ ...product });

  const handleChange = (e) => {
    setEditedProduct({ ...editedProduct, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onUpdate(editedProduct);
  };

  return (
    <div className="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
      <div className="bg-white p-6 rounded-lg">
        <h2 className="text-xl font-bold mb-4">Edit Product</h2>
        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <label htmlFor="name" className="block font-bold mb-2">
              Name
            </label>
            <input
              type="text"
              id="name"
              name="name"
              value={editedProduct.name}
              onChange={handleChange}
              className="border border-gray-400 p-2 w-full"
            />
          </div>
          {/* Add more input fields for other product properties */}
          <div className="flex justify-end">
            <button
              type="button"
              onClick={onCancel}
              className="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2"
            >
              Annuler
            </button>
            <button
              type="submit"
              className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Sauvegarder
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default ProductEditModal;
