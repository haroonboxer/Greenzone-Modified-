export interface License {
  id: number
  license_type: 'new' | 'extend' | 'renew'
  issue_date: string
  expire_date: string
  sn?: string | null
  reject_reason?: string | null
  status: number
  printed: number
  vehicle_id: number
  driver_id: number
  created_by: number
  created_department: number
  created_location: number
  deleted_at?: string | null
  created_at: string
  updated_at: string
  vehicle_type: string
  vehicle_color: string
  vehicle_platte_no: string
  vehicle_engine_no: string
  driver_name: string
  driver_nic: string
  driver_phone: string
  main_province_name: string
  main_district_name: string
  main_village: string
  current_province_name: string
  current_district_name: string
  current_village: string
  phone: string
  nic: string
  driver_f_name: string
  driver_g_f_name: string
  driver_photo: string
  front_photo: string
  back_photo: string
  vehicle_type_name: string

  // extra computed fields (if you return joins from backend)
  ownerName?: string
  createdLocation?: string
  createdDepartment?: string
  department_name?: string
  province_name?: string
}

export const defaultLicense: License = {
  id: 0,
  license_type: 'new',
  issue_date: '',
  expire_date: '',
  sn: null,
  reject_reason: null,
  status: 0,
  printed: 0,
  vehicle_id: 0,
  driver_id: 0,
  created_by: 0,
  created_department: 0,
  created_location: 0,
  deleted_at: null,
  created_at: '',
  updated_at: '',
  vehicle_type: '',
  vehicle_color: '',
  vehicle_platte_no: '',
  vehicle_engine_no: '',
  driver_name: '',
  driver_nic: '',
  driver_phone: '',
  main_province_name: '',
  main_district_name: '',
  main_village: '',
  current_province_name: '',
  current_district_name: '',
  current_village: '',
  phone: '',
  nic: '',
  driver_f_name: '',
  driver_g_f_name: '',
  driver_photo: '',
  front_photo: '',
  back_photo: '',
vehicle_type_name: '',
  // extras as empty
  ownerName: '',
  createdLocation: '',
  createdDepartment: '',
}
