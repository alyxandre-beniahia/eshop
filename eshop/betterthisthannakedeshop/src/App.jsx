import { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';
import Header from './components/Header';
import HomePage from './pages/Home';
import ProductsPage from './pages/ProductsPage';
import ShoppingCart from './components/ShoppingCart';

function App() {
  const [cartItems, setCartItems] = useState([]);

  useEffect(() => {
    const storedCartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    setCartItems(storedCartItems);
  }, []);

  const addToCart = (product, selectedSizeId) => {
    console.log(product)
    const existingCartItem = cartItems.find(
      (item) => item.id === product.id && item.sizeId === selectedSizeId
    );
  
    if (existingCartItem) {
      // If the product with the same size already exists in the cart, update its quantity
      const updatedCartItems = cartItems.map((item) =>
        item.id === product.id && item.sizeId === selectedSizeId
          ? { ...item, quantity: item.quantity + 1 }
          : item
      );
      setCartItems(updatedCartItems);
    } else {
      // If the product with the selected size is not in the cart, add it with a quantity of 1
      const updatedCartItems = [
        ...cartItems,
        { ...product, quantity: 1, sizeId: selectedSizeId },
      ];
      setCartItems(updatedCartItems);
    }
  };
  
  

  const removeFromCart = (productId, sizeId) => {
    const updatedCartItems = cartItems.filter(
      (item) => item.id !== productId || item.sizeId !== sizeId
    );
    setCartItems(updatedCartItems);
    localStorage.setItem('cartItems', JSON.stringify(updatedCartItems));
  };
  

  return (
    <Router>
      <div className="min-h-screen flex flex-col">
        <Header />
        <main className="flex-grow">
          <Routes>
            <Route path="/" element={<HomePage addToCart={addToCart} />} />
            <Route
              path="/products"
              element={<ProductsPage addToCart={addToCart} />}
            />
          </Routes>
        </main>
        <ShoppingCart
          cartItems={cartItems}
          removeFromCart={removeFromCart}
        />
      </div>
    </Router>
  );
}

export default App;
