import {configureStore} from '@reduxjs/toolkit'
import departmentSlice from './authentication/department/departmentSlice'
import roleSlice from './authentication/roles/roleSlice'
import userManagementSlice from './authentication/user/userManagementSlice'
import provinceSlice from './authentication/province/provinceSlice'
import gzLicenseSlice from './green_zone/license/licenseSlice'
import  reportSlice  from './green_zone/reports/reportSlice'
import  VehicleSlice  from './green_zone/vehicles/VehicleSlice'
import  driverSlice  from './green_zone/driver/driverSlice'
import  CardPrintSlice  from './green_zone/Card/CardPrintSlice'
import vehicleSaveSlice  from './green_zone/vehicleSave/vehicleSaveSlice'

export const store = configureStore({
  reducer: {
    departments: departmentSlice,
    role: roleSlice,
    systems: roleSlice,
    permissions: roleSlice,
    userManagement: userManagementSlice,
    province: provinceSlice,
    gzLicense: gzLicenseSlice,
    report: reportSlice,
    vehicle: VehicleSlice,
    driver: driverSlice,
    card: CardPrintSlice,
    vehicleSave: vehicleSaveSlice,
  },
})

export type RootState = ReturnType<typeof store.getState>
export type AppDispatch = typeof store.dispatch
