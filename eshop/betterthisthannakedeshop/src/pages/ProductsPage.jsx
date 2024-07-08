// ProductsPage.jsx
import React, { useState, useEffect } from 'react';
import ProductModal from '../components/ProductModal';
import axios from 'axios';
import Slider from 'react-slick';

const ProductImageCarousel = ({ images }) => {
  const settings = {
    dots: true,
    infinite: true,
    speed: 500,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: 3000,
  };

  return (
    <Slider {...settings}>
      {images.map((imagePath, index) => (
        <div key={index}>
          <img
            src={`http://localhost:8000/${imagePath}`}
            alt={`Product Image ${index + 1}`}
            className="w-full h-64 object-cover"
          />
        </div>
      ))}
    </Slider>
  );
};

const ProductsPage = ({ addToCart }) => {
  const [products, setProducts] = useState([]);
  const [selectedProduct, setSelectedProduct] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  useEffect(()=> {
    axios.get('http://localhost:8000/products')
      .then(response => {
        console.log(response.data.products);
        setProducts(response.data.products);
      })
     .catch(error => {
        console.error('Error fetching products:', error);
      });
  }, []);


  const handleProductClick = product => {
    setSelectedProduct(product);
    setIsModalOpen(true);
  };

  const handleModalClose = () => {
    setSelectedProduct(null);
    setIsModalOpen(false);
  };

  return (
    <div className="container mx-auto py-8">
      <h2 className="text-2xl font-bold mb-4">All Products</h2>
      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        {products.map(product => (
          <div
          key={product.id}
          className="bg-white rounded-lg shadow-md overflow-hidden cursor-pointer"
          onClick={() => handleProductClick(product)}
        >
          <ProductImageCarousel images={product.images} />
          <div className="p-4">
            <h3 className="text-lg font-bold mb-2">{product.name}</h3>
            <p className="text-gray-700 mb-2">${product.price}</p>
            <div className="mb-4">
              <label htmlFor={`size-${product.id}`} className="block font-bold mb-2">
                Select Size:
              </label>
              <select
                id={`size-${product.id}`}
                className="border rounded py-2 px-3"
                defaultValue=""
              >
                <option value="" disabled>
                  Choose a size
                </option>
                {product.stock.map((stockItem) => (
                  <option key={stockItem.size_id} value={stockItem.size_id}>
                    {stockItem.size_name}
                  </option>
                ))}
              </select>
            </div>
            <button
              className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              onClick={(e) => {
                e.stopPropagation();
                const selectedSizeId = e.target.previousElementSibling.value;
                addToCart(product, selectedSizeId);
              }}
            >
              Add to Cart
            </button>
          </div>
        </div>
               
        ))}
      </div>
      <ProductModal
        product={selectedProduct}
        isOpen={isModalOpen}
        onClose={handleModalClose}
      />
    </div>
  );
};

export default ProductsPage;
