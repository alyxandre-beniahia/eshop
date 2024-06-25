import { createBrowserRouter, RouterProvider } from 'react-router-dom';
import Home from './pages/Home';
import Form from './pages/Formulaire';
import Collections from './pages/Collections';
import RegisterPage from './pages/RegisterPage';
import LoginPage from './pages/LoginPage';
import ProfilePage from './pages/ProfilePage';
import Favorites from './pages/Favorites';
import Account from './pages/Account';
import Login from './pages/LoginPage';
import Register from './pages/RegisterPage';
import { RouteLayout } from './Route';
import './scss/main.css';

const router = createBrowserRouter([
  {
    path: '/',
    element: <RouteLayout/>,
    children: [
      {path: '/', element: <Home/>},
      {path: '/formulaire', element: <Form/>},
      {path: '/collections', element: <Collections/>},
      {path: '/favorites', element: <Favorites/>},
      {path: '/account', element: <Account/>},
      {path: '/login', element: <Login/>},
      {path: '/register', element: <Register/>},
      {path: '/register',element: <RegisterPage/>},
      {path: '/login', element: <LoginPage/>},
      {path: '/profilePage', element: <ProfilePage/>},
    ]
  },
]);

function App() {
  return <RouterProvider router={router} />;
}

export default App;