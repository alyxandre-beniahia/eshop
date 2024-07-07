import React, { useState, useEffect } from 'react';
import axios from 'axios';

const ProductEditModal = ({ product, onUpdate, onCancel }) => {
  const [editedProduct, setEditedProduct] = useState({ ...product });
  const [sizes, setSizes] = useState([]);
  const [discounts, setDiscounts] = useState([]);
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    console.log(product)
    axios.get('http://localhost:8000/sizes')
    .then(response => {
      setSizes(response.data.data);
    })
    .catch(error => {
      console.error('Error fetching sizes:', error);
    });
    axios.get('http://localhost:8000/discounts')
      .then(response => {
        setDiscounts(response.data);
      })
      .catch(error => {
        console.error('Error fetching discounts:', error);
      });
    axios.get('http://localhost:8000/categories')
    .then(response => {
        setCategories(response.data.categories);
      })
      .catch(error => {
        console.error('Error fetching categories:', error);
      });
  }, []);

  const handleChange = (e) => {
    setEditedProduct({ ...editedProduct, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onUpdate(editedProduct);
  };
  const handleImageUpload = (e) => {
    const files = e.target.files;
    const uploadedImages = [];
  
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      const reader = new FileReader();
  
      reader.onloadend = () => {
        uploadedImages.push({
          path: reader.result,
          is_primary: i === 0, // Set the first image as primary
        });
  
        if (uploadedImages.length === files.length) {
          setEditedProduct({ ...editedProduct, images: uploadedImages });
        }
      };
  
      reader.readAsDataURL(file);
    }
  };

  return (
    <div className="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
      <div className="bg-white p-6 rounded-lg">
        <h2 className="text-xl font-bold mb-4">Modifier</h2>
        <form onSubmit={handleSubmit}>
          <div className="mb-4">
            <label htmlFor="name" className="block font-bold mb-2">
              Nom du produit
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
          <div className="mb-4">
            <label htmlFor="brand" className="block font-bold mb-2">
              Marque
            </label>
            <input
              type="text"
              id="brand"
              name="brand"
              value={editedProduct.brand}
              onChange={handleChange}
              className="border border-gray-400 p-2 w-full"
              required
            />
          </div>
          <div className="mb-4">
            <label htmlFor="description" className="block font-bold mb-2">
              Description
            </label>
            <input
              type="text"
              id="description"
              name="description"
              value={editedProduct.description}
              onChange={handleChange}
              className="border border-gray-400 p-2 w-full"
              required
            />
          </div>
          <div className="mb-4">
            <label htmlFor="quantity" className="block font-bold mb-2">
              Prix
            </label>
            <input
              type="integer"
              id="quantity"
              name="quantity"
              value={editedProduct.price}
              onChange={handleChange}
              className="border border-gray-400 p-2 w-full"
              required
            />
          </div>
          <div className="mb-4">
            <label htmlFor="discount_id" className="block font-bold mb-2">
                Promotions
            </label>
            <select
                id="discount_id"
                name="discount_id"
                value={editedProduct.discount_id}
                onChange={handleChange}
                className="border border-gray-400 p-2 w-full"
            >
                <option value="">Choisir une promotion</option>
                {discounts.map(discount => (
                <option key={discount.id} value={discount.id}>
                    {discount.name}
                </option>
                ))}
            </select>
        </div>
        <div className="mb-4">
            <label htmlFor="discount_id" className="block font-bold mb-2">
                Tailles
            </label>
            <select
                id="size_id"
                name="size_id"
                value={editedProduct.size_id}
                onChange={handleChange}
                className="border border-gray-400 p-2 w-full"
            >
            <option value="">Choisir une taille</option>
                {sizes.map(size => (
                    <option key={size.id} value={size.id}>
                        {size.name}
                    </option>
                ))}
            </select>
        </div>
        <div className="mb-4">
            <label htmlFor="price" className="block font-bold mb-2">
              Quantités
            </label>
            <input
              type="text"
              id="price"
              name="price"
              value={editedProduct.quantity}
              onChange={handleChange}
              className="border border-gray-400 p-2 w-full"
              required
            />
          </div>
        <div className="mb-4">
            <label htmlFor="categories" className="block font-bold mb-2">
                Catégories
            </label>
            <select
                id="category"
                name="category"
                value={editedProduct.category_id}
                onChange={handleChange}
                className="border border-gray-400 p-2 w-full"
            >
            <option value="">Choisir une catégories</option>
                {categories.map(category => (
                    <option key={category.id} value={category.id}>
                        {category.name}
                    </option>
                ))}
            </select>
        </div>
        <div className="mb-4">
            <label htmlFor="images" className="block font-bold mb-2">
              Images
            </label>
            <input
              type="file"
              id="images"
              name="images"
              multiple
              onChange={handleImageUpload}
              className="border border-gray-400 p-2 w-full"
            />
          </div>
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
