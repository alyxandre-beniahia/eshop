import { Outlet } from 'react-router-dom'

export const RouteLayout = () => {
  return (
    <>
    <main>
        <Outlet/>
    </main>
    </>
  )
}