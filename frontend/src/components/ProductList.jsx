import React, { useEffect, useState } from 'react';
import axios from 'axios';
import ProductEditModal from './ProductEditModal';

const ProductList = () => {
  const [products, setProducts] = useState([]);
  const [editingProduct, setEditingProduct] = useState(null);

  useEffect(() => {
    axios.get('http://localhost:8000/products')
      .then(response => {
        console.log(response.data.products);
        setProducts(response.data.products);
      })
      .catch(error => {
        console.error('Error fetching products:', error);
      });
  }, []);

  const handleEditClick = (product) => {
    setEditingProduct(product);
  };

  const handleUpdateProduct = (updatedProduct) => {
    axios.put(`http://localhost:8000/products/${updatedProduct.id}`, updatedProduct)
      .then(response => {
        setProducts(products.map(p => (p.id === updatedProduct.id ? updatedProduct : p)));
        setEditingProduct(null);
      })
      .catch(error => {
        console.error('Error updating product:', error);
      });
  };

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">Product List</h2>
      <table className="w-full table-auto">
        <thead>
          <tr>
            <th className="px-4 py-2">Nom du produit</th>
            <th className="px-4 py-2">Marque</th>
            <th className="px-4 py-2">Prix</th>
            <th className="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          {products.map(product => (
            <tr key={product.id}>
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
                <button className="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">
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
