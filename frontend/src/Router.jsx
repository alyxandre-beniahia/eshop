import { createBrowserRouter, RouterProvider } from 'react-router-dom';
import Home from './pages/Main';
import Login from './pages/Login';
import Register from './pages/Register';
import Account from './pages/Account';
import Product from './pages/Product';
import { RouteLayout } from './Route';

const router = createBrowserRouter([
  {
    path: '/',
    element: <RouteLayout/>,
    children: [
      {path: '/', element: <Home/>},
      {path: '/login', element: <Login/>},
      {path: '/register', element: <Register/>},
      {path: '/account', element: <Account/>},
      {path: '/product',element: <Product/>},
    ]
  },
]);

function App() {
  return <RouterProvider router={router} />;
}

export default App;