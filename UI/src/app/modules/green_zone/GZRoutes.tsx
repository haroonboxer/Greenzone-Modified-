import {Route, Routes} from 'react-router-dom'
import VehicleList from './vehicle/list/VehicleList'
import VehicleSave from './new-vehicle/list/NewVehicleList'
import ExpiredVehicles from './expired-vehicles/list/expiredVehicles'
import GreenZoneView from './view/GreenZoneView'

const GZRoutes = () => (
  <Routes>
    <Route path='list' element={<VehicleList />} />
    <Route path='vehicleSave' element={<VehicleSave />} />
    <Route path='expiredVehicles' element={<ExpiredVehicles />} />
    <Route path='view/:id' element={<GreenZoneView />} />
  </Routes>
)

export default GZRoutes
