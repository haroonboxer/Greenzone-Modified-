import axios from 'axios'

const getLicense = async (params: any) => {
  const response = await axios.get(`api/license/index`, {params})
  return response.data
}

const store = async (formData: any) => {
  const response = await axios.post('api/license/store', formData)
  return response.data
}

const sentPrint = async (formData: any) => {
  const response = await axios.post('api/license/sentPrint', formData)
  return response.data
}

const view = async (id: number) => {
  const response = await axios.post(`/api/license/view/${id}`)
  return response.data
}

const update = async (id: number, formData: any) => {
  const response = await axios.post(`api/license/update/${id}`, formData)
  return response.data
}

const changeStatusOfPrint = async (formData: FormData) => {
  const response = await axios.post('/api/workshopLicense/changeStatusOfPrint', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
  return response
}

const licenseService = {
  getLicense,
  store,
  view,
  update,
  changeStatusOfPrint,
  sentPrint,
}
export default licenseService
