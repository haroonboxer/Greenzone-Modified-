export interface Vehicle {
  vehicle_type: string
  vehicle_color: string
  vehicle_platte_no: string
  vehicle_engine_no: string
  vehicle_source: string
  front_photo: string
  back_photo: string
  status: string
  createdBy: string
  createdLocation: string
  createdDepartment: string
  created_at: string
}

export const defaultVehicle: Vehicle = {
  vehicle_type: '',
  vehicle_color: '',
  vehicle_platte_no: '',
  vehicle_engine_no: '',
  vehicle_source: '',
  front_photo: '',
  back_photo: '',
  status: '0',
  createdBy: '',
  createdLocation: '',
  createdDepartment: '',
  created_at: '',
}

export interface VehicleView {
  record: {
    vehicle_type: string
    vehicle_color: string
    vehicle_platte_no: string
    vehicle_engine_no: string
    vehicle_source: string
    front_photo: string
    back_photo: string
    status: string
    createdBy: string
    createdLocation: string
    createdDepartment: string
    created_at: string
    
  }
}

export const defaultVehicleView: VehicleView = {
  record: {
    vehicle_type: '',
    vehicle_color: '',
    vehicle_platte_no: '',
    vehicle_engine_no: '',
    vehicle_source: '',
    front_photo: '',
    back_photo: '',
    status: '0',
    createdBy: '',
    createdLocation: '',
    createdDepartment: '',
    created_at: '',
  },
}
