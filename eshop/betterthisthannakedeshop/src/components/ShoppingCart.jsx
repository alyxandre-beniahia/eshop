// ShoppingCart.jsx
import React from 'react';

const ShoppingCart = ({ cartItems, removeFromCart }) => {
  return (
    <div className="fixed right-0 top-0 h-screen w-80 bg-white p-4 shadow-md">
      <h2 className="text-xl font-bold mb-4">Shopping Cart</h2>
      {cartItems.length === 0 ? (
        <p>Your cart is empty.</p>
      ) : (
        <ul>
          {cartItems.map((item) => (
            <li key={`${item.id}-${item.sizeId}`} className="flex justify-between items-center mb-2">
            <span>
              {item.name} (Size: {item.stock.find((s) => s.size_id === item.sizeId)?.size_name || 'N/A'}, Quantity: {item.quantity})
            </span>
            <span>${item.price * item.quantity}</span>
            <button
              className="text-red-500 hover:text-red-700"
              onClick={() => removeFromCart(item.id, item.sizeId)}
            >
              Remove
            </button>
          </li>          
          ))}
        </ul>
      )}
    </div>
  );
};

export default ShoppingCart;
