import axios from 'axios'

const getVehicleSave = async (params: any) => {
  const response = await axios.get(`api/vehicleSave/index`, { params })
  return response.data
}

const createButton = async (params: any) => {
  const response = await axios.get(`api/vehicleSave/createButton`, { params })
  return response.data
}

const store = async (formData: FormData) => {
  const response = await axios.post('api/vehicleSave/store', formData)
  return response.data
}

const viewVehicleSave = async (id: number, formData: any) => {
  const response = await axios.post(`api/vehicleSave/view/${id}`, formData)
  return response.data
}

const editBoss = async (id: number) => {
  const response = await axios.get(`api/vehicleSave/edit/${id}`)
  return response.data
}

const update = async (id: number, formData: any) => {
  const response = await axios.post(`api/vehicleSave/update/${id}`, formData)
  return response.data
}

const changeStatus = async (formData: FormData) => {
  const response = await axios.post('/api/vehicleSave/changeStatus', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
  return response
}
const vehicleSaveService = {
  getVehicleSave,
  store,
  viewVehicleSave,
  editBoss,
  changeStatus,
  createButton,
  update
}

export default vehicleSaveService
