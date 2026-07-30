import { Route, Routes } from 'react-router-dom'
import NewVehicleList from './list/NewVehicleList'


const CardPrintRoutes = () => (
  <Routes>
    <Route path='list' element={<NewVehicleList />} />
  </Routes>
)

export default CardPrintRoutes
