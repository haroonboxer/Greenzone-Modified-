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

// const viewVehicle = async (id: number, formData: any) => {

//   const response = await axios.post(`api/vehicle/view/${id}`, formData)
//   LoadDepartmentName(response.data.createdDepartment);
//     console.log(response.data);
//   return response.data
// }
 
// const LoadDepartmentName = async (id:string,formData:any)=>
// {
//   var response = await axios.get(`https://localhost:7161/api/HandlerAPIRequest/LoadUserProvince/${id}`);

// }
const viewVehicle = async (id: number, formData: any) => {
    const response = await axios.post(
        `api/vehicle/view/${id}`,
        formData
    );

    
    console.log("RESPONSE DATA:", response.data);
    console.log("Department:", response.data.createdDepartment);

    const departmentId =response.data.data.createdDepartment;
    const ProvinceId = response.data.data.createdLocation;
    if (departmentId) {
       const departmentResponse = await LoadDepartmentName(departmentId);
      response.data.data.createdDepartment = departmentResponse;
    }
    if(ProvinceId)
    {
      response.data.data.createdLocation = await LoadProvinceName(ProvinceId);
    }
    return response.data;
};


const LoadDepartmentName = async (id: string) => {
    const response = await axios.get(
        `https://localhost:7161/api/HandlerAPIRequest/LoadUserDepartment?Id=${id}`
    );

    return response.data;
};
const LoadProvinceName = async (id:number) =>
{
        const response = await axios.get(
        `https://localhost:7161/api/HandlerAPIRequest/LoadUserProvince?id=${id}`
    );
    return response.data;
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
