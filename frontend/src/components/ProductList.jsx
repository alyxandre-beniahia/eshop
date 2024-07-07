import React, { useEffect, useState } from 'react';
import axios from 'axios';
import ProductEditModal from './ProductEditModal';
import ProductCreateModal from './ProductCreateModal';
import AuthService from '../services/AuthService';

const ProductList = () => {
  const [products, setProducts] = useState([]);
  const [editingProduct, setEditingProduct] = useState(null);
  const [showCreateModal, setShowCreateModal] = useState(false);

  useEffect(() => {
    axios.get('http://localhost:8000/products')
      .then(response => {
        console.log(response.data.products);
        setProducts(response.data.products);
      })
      .catch(error => {
        console.error('Error fetching products:', error);
      });
  }, [showCreateModal]);

  const handleEditClick = (product) => {
    setEditingProduct(product);
  };

  const handleCreateProduct = (newProduct) => {
    setProducts([...products, newProduct]);
    setShowCreateModal(false);
  };

  const handleUpdateProduct = (updatedProduct) => {
    const token = AuthService.getCurrentUser();
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    axios.put(`http://localhost:8000/products?id=${updatedProduct.id}`, updatedProduct)
      .then(response => {
        setProducts(products.map(p => (p.id === updatedProduct.id ? updatedProduct : p)));
        setEditingProduct(null);
      })
      .catch(error => {
        console.error('Error updating product:', error);
      });
  };

  const handleDeleteClick = (product) => {
    if (window.confirm('Are you sure you want to delete this product?')) {
      const token = AuthService.getCurrentUser();
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      axios.delete(`http://localhost:8000/products?id=${product.id}`)
        .then(response => {
          setProducts(products.filter(p => p.id!== product.id));
        })
        .catch(error => {
          console.error('Error deleting product:', error);
        });
    }
  };


  return (
    <div>
      <div>
        <h2 className="text-2xl font-bold mb-4">Product List</h2>
        <button
          className="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4"
          onClick={() => setShowCreateModal(true)}
        >
          Create Product
        </button>
        {showCreateModal && (
          <ProductCreateModal
            onCreateProduct={handleCreateProduct}
            onCancel={() => setShowCreateModal(false)}
          />
        )}
      </div>
      <h2 className="text-2xl font-bold mb-4">Product List</h2>
      <table className="w-full table-auto">
        <thead>
          <tr>
            <th className='px-4 py-2'>Images</th>
            <th className="px-4 py-2">Nom du produit</th>
            <th className="px-4 py-2">Marque</th>
            <th className="px-4 py-2">Prix</th>
            <th className="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          {products.map(product => (
            <tr key={product.id}>
              <td className="border px-4 py-2">
                {product.images && product.images.length > 0 ? (
                  <div className="flex">
                    {product.images.map((imagePath, index) => (
                      <img
                        key={index}
                        src={`http://localhost:8000/${imagePath}`}
                        alt={`Product ${product.id} Image ${index + 1}`}
                        className="w-20 h-20 object-cover mr-2"
                      />
                    ))}
                  </div>
                ) : (
                  <span>No images available</span>
                )}
              </td>
              <td className="border px-4 py-2">{product.name}</td>
              <td className="border px-4 py-2">{product.brand}</td>
              <td className="border px-4 py-2">{product.price} €</td>
              <td className="border px-4 py-2">
                <button
                  className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                  onClick={() => handleEditClick(product)}
                >
                  Modifier
                </button>
                <button className="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2"
                        onClick={()=> handleDeleteClick(product)}>
                  Supprimer
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {editingProduct && (
        <ProductEditModal
          product={editingProduct}
          onUpdate={handleUpdateProduct}
          onCancel={() => setEditingProduct(null)}
        />
      )}
    </div>
  );
};

export default ProductList;
