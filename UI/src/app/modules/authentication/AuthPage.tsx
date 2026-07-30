import {Routes, Route, Outlet} from 'react-router-dom'
import DepartmentList from './components/departments/list-department/DepartmentList'
import AddDepartment from './components/departments/create-department/AddDepartment'
import EditDepartment from './components/departments/edit-department/EditDepartment'
import RoleList from './components/roles/list-roles/RoleList'
import AddRoles from './components/roles/create-roles/AddRoles'
import EditRoles from './components/roles/edit-roles/EditRoles'
import UserList from './components/users/list-user/UserList'
import AddUser from './components/users/create-user/AddUser'
import EditUser from './components/users/edit-user/EditUser'
import ViewUser from './components/users/view-user/ViewUser'

const AuthManagementModuleRoute = () => (
  <Routes>
    <Route element={<Outlet />}>
      <Route path='users' element={<UserList />} />
      <Route path='view-user/:id' element={<ViewUser />} />
      <Route path='create-user' element={<AddUser />} />
      <Route path='edit-user/:id' element={<EditUser />} />
      <Route path='departments' element={<DepartmentList />} />
      <Route path='create-department/:id' element={<AddDepartment />} />
      <Route path='edit-department/:id' element={<EditDepartment />} />
      <Route path='roles' element={<RoleList />} />
      <Route path='create-role' element={<AddRoles />} />
      <Route path='edit-roles/:id' element={<EditRoles />} />
    </Route>
  </Routes>
)

export default AuthManagementModuleRoute
