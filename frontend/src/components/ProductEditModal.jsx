import React, { useState, useEffect } from 'react';
import axios from 'axios';

const ProductEditModal = ({ product, onUpdate, onCancel }) => {
  const [editedProduct, setEditedProduct] = useState({ ...product });
  const [sizes, setSizes] = useState([]);
  const [stock, setStock] = useState(product.stock);
  const [discounts, setDiscounts] = useState([]);
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    console.log(product)
    setStock(product.stock);
    setEditedProduct({ ...product, stock: product.stock });
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
    if (e.target.name.startsWith('stock-')) {
      const sizeId = parseInt(e.target.name.split('-')[1], 10);
      const newStock = editedProduct.stock.map((item) => {
        if (item.size_id === sizeId) {
          return { ...item, quantity: parseInt(e.target.value, 10) };
        }
        return item;
      });
      setEditedProduct({ ...editedProduct, stock: newStock });
    } else {
      setEditedProduct({ ...editedProduct, [e.target.name]: e.target.value });
    }
  };

  const handleStockChange = (e, sizeId) => {
    const newQuantity = parseInt(e.target.value, 10);
    const updatedStock = stock.map((item) => {
      if (item.size_id === sizeId) {
        return { ...item, quantity: newQuantity };
      }
      return item;
    });
    setStock(updatedStock);
    setEditedProduct({ ...editedProduct, stock: updatedStock });
  };
  
  
  

  const handleSubmit = (e) => {
    e.preventDefault();
    const updatedProduct = {
      ...editedProduct,
      stock: stock.map((stockItem) => ({
        ...stockItem,
        quantity: stockItem.quantity,
      })),
    };
    onUpdate(updatedProduct);
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
            <label htmlFor="price" className="block font-bold mb-2">
              Prix
            </label>
            <input
              type="text"
              id="price"
              name="price"
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
          <label htmlFor="stock" className="block font-bold mb-2">
            Quantités
          </label>
          {stock.map((stockItem) => (
            <div key={stockItem.size_id}>
              <label htmlFor={`stock-${stockItem.size_id}`}>{stockItem.size_name}</label>
              <input
                type="number"
                id={`stock-${stockItem.size_id}`}
                name={`stock-${stockItem.size_id}`}
                value={stockItem.quantity}
                onChange={(e) => handleStockChange(e, stockItem.size_id)}
                className="border border-gray-400 p-2 w-full"
                required
              />
            </div>
          ))}
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
