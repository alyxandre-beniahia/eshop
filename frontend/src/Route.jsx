import Navbar from './components/navbar'
import { Outlet } from 'react-router-dom'
import Footer from './components/footer'
import './scss/main.css';

export const RouteLayout = () => {
  return (
    <>
    <Navbar/>
    <main>
        <Outlet/>
    </main>
    <Footer/>
    </>
  )
}