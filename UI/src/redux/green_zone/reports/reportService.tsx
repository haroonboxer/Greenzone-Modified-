import axios from 'axios'

const getReport = async (params: any) => {
  const response = await axios.get('api/report/index', {params})
  return response.data
}

const getCompanies = async () => {
  const response = await axios.get('api/report/listCompany')
  return response.data
}

const getMonthlyCompanyStats = async () => {
  const response = await axios.get('api/report/monthlyCompanyStats')
  return response.data
}

const reportService = {
  getReport,
  getCompanies,
  getMonthlyCompanyStats,
}

export default reportService
