import React, { useEffect, useState } from 'react';
import axios from 'axios';
import AuthService from '../services/AuthService';

const ProductCreateModal = ({ onCreateProduct, onCancel }) => {
  const [formData, setFormData] = useState({
    name: '',
    brand: '',
    description: '',
    price: '',
    discount_id: '',
    quantity: '',
    images: [],
    category_id: ''
  });
  const [sizes, setSizes] = useState([]);
  const [discounts, setDiscounts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [sizeQuantities, setSizeQuantities] = useState({});


  useEffect(() => {
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

  const handleInputChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
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
          setFormData({ ...formData, images: uploadedImages });
        }
      };
  
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = async (e) => {
    console.log(formData);
    e.preventDefault();
    try {
      const token = AuthService.getCurrentUser();
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      const response = await axios.post('http://localhost:8000/products', { ...formData, sizeQuantities });
      console.log(response);
      onCreateProduct(response.data);
      setFormData({
        name: '',
        brand: '',
        description: '',
        price: '',
        discount_id: '',
        quantity: '',
        images: [],
        category_id:''
      });
    } catch (error) {
      console.error('Error creating product:', error);
    }
  };

  return (
  <div className="fixed inset-0 flex items-center justify-center z-50">
    <div className="fixed inset-0 flex items-center justify-center z-50">
  <div className="bg-white rounded-lg shadow-lg p-6 w-4/5 max-h-screen overflow-y-auto">
    <h2 className="text-2xl font-bold mb-4">Create Product</h2>
    <form onSubmit={handleSubmit} className="flex flex-wrap -mx-4">
      <div className="w-1/2 px-4 mb-4">
        <label htmlFor="name" className="block font-bold mb-2">
          Nom du produit
        </label>
        <input
          type="text"
          id="name"
          name="name"
          value={formData.name}
          onChange={handleInputChange}
          className="border border-gray-400 p-1 w-full rounded-md"
          required
        />
      </div>
      <div className="w-1/2 px-4 mb-4">
        <label htmlFor="brand" className="block font-bold mb-2">
          Marque
        </label>
        <input
          type="text"
          id="brand"
          name="brand"
          value={formData.brand}
          onChange={handleInputChange}
          className="border border-gray-400 p-1 w-full rounded-md"
          required
        />
      </div>
      <div className="w-full px-4 mb-4">
        <label htmlFor="description" className="block font-bold mb-2">
          Description
        </label>
        <input
          type="text"
          id="description"
          name="description"
          value={formData.description}
          onChange={handleInputChange}
          className="border border-gray-400 p-1 w-full rounded-md"
          required
        />
      </div>
      <div className="w-1/2 px-4 mb-4">
        <label htmlFor="price" className="block font-bold mb-2">
          Prix
        </label>
        <input
          type="integer"
          id="price"
          name="price"
          value={formData.price}
          onChange={handleInputChange}
          className="border border-gray-400 p-1 w-full rounded-md"
          required
        />
      </div>
      <div className="w-1/2 px-4 mb-4">
        <label htmlFor="discount_id" className="block font-bold mb-2">
          Promotions
        </label>
        <select
          id="discount_id"
          name="discount_id"
          value={formData.discount_id}
          onChange={handleInputChange}
          className="border border-gray-400 p-1 w-full rounded-md"
        >
          <option value="">Choisir une promotion</option>
          {discounts.map((discount) => (
            <option key={discount.id} value={discount.id}>
              {discount.name}
            </option>
          ))}
        </select>
      </div>
      <div className="w-full px-4 mb-4">
        <label className="block font-bold mb-2">Quantités par taille</label>
        <div className="flex flex-wrap -mx-2">
          {sizes.map((size) => (
            <div key={size.id} className="w-1/3 px-2 mb-4">
              <label htmlFor={`size-${size.id}`} className="block font-bold mb-2">
                {size.name}
              </label>
              <input
                type="number"
                id={`size-${size.id}`}
                name={`size-${size.id}`}
                value={sizeQuantities[size.id] || 0}
                onChange={(e) =>
                  setSizeQuantities({
                    ...sizeQuantities,
                    [size.id]: parseInt(e.target.value) || 0,
                  })
                }
                className="border border-gray-400 p-1 w-full rounded-md"
                min="0"
              />
            </div>
          ))}
        </div>
      </div>
      <div className="w-1/2 px-4 mb-4">
        <label htmlFor="categories" className="block font-bold mb-2">
          Catégories
        </label>
        <select
          id="category"
          name="category"
          value={formData.category_id}
          onChange={handleInputChange}
          className="border border-gray-400 p-1 w-full rounded-md"
        >
          <option value="">Choisir une catégorie</option>
          {categories.map((category) => (
            <option key={category.id} value={category.id}>
              {category.name}
            </option>
          ))}
        </select>
      </div>
      <div className="w-1/2 px-4 mb-4">
        <label htmlFor="images" className="block font-bold mb-2">
          Images
        </label>
        <input
          type="file"
          id="images"
          name="images"
          multiple
          onChange={handleImageUpload}
          className="border border-gray-400 p-1 w-full rounded-md"
        />
      </div>
      <div className="w-full px-4 mb-4 flex justify-end">
        <button
          type="button"
          onClick={onCancel}
          className="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2"
        >
          Cancel
        </button>
        <button
          type="submit"
          className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          Create
        </button>
      </div>
    </form>
  </div>
</div>

  </div>
  );
};

export default ProductCreateModal;
