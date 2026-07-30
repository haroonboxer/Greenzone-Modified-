import axios from 'axios'

const getvehicle = async (params: any) => {
  const response = await axios.get(`api/vehicle/index`, {params})
  return response.data
}

const getExpiredVehicles = async (params: any) => {
  const response = await axios.get(`api/vehicle/expired`, {params})
  return response.data
}

const createButton = async (params: any) => {
  const response = await axios.get(`api/vehicle/createButton`, {params})
  return response.data
}

const store = async (formData: FormData) => {
  const response = await axios.post('api/vehicle/store', formData)
  return response.data
}

const viewVehicle = async (id: number, formData: any) => {
  const response = await axios.post(`api/vehicle/view/${id}`, formData)
  return response.data
}

const update = async (id: number, formData: any) => {
  const response = await axios.post(`api/vehicle/update/${id}`, formData)
  return response.data
}

const changeStatus = async (formData: FormData) => {
  const response = await axios.post('/api/vehicle/changeStatus', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
  return response
}
const vehicleeService = {
  getvehicle,
  store,
  getExpiredVehicles,
  viewVehicle,
  changeStatus,
  createButton,
  update,
}

export default vehicleeService
