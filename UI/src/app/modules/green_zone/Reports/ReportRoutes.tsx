import {Route, Routes} from 'react-router-dom'
import Report from './Report'


const ReportRoutes = () => (
  <Routes>
    <Route path='list' element={<Report />} />
  </Routes>
)

export default ReportRoutes
