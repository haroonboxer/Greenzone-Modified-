import axios from 'axios'

const getDriver = async (params: any) => {
  const response = await axios.get(`api/driver/index`, { params })
  return response.data
}

const createButton = async (params: any) => {
  const response = await axios.get(`api/driver/createButton`, { params })
  return response.data
}

const store = async (formData: FormData) => {
  const response = await axios.post('api/driver/store', formData)
  return response.data
}

const viewDriver = async (id: number, formData: any) => {
  const response = await axios.post(`api/driver/view/${id}`, formData)
  return response.data
}

const editBoss = async (id: number) => {
  const response = await axios.get(`api/driver/edit/${id}`)
  return response.data
}

const update = async (id: number, formData: any) => {
  const response = await axios.post(`api/driver/update/${id}`, formData)
  return response.data
}

const changeStatus = async (formData: FormData) => {
  const response = await axios.post('/api/driver/changeStatus', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
  return response
}
const driverService = {
  getDriver,
  store,
  viewDriver,
  editBoss,
  changeStatus,
  createButton,
  update
}

export default driverService
