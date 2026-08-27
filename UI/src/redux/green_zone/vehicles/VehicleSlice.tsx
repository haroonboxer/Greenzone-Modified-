// -- name: BossSlice.tsx
// -- date: 03-18-2025.
// -- desc: Redux toolkit slice for the Boss components.
// -- author: Mohammad Omer Amiri.
// -- email: amiriomer6@gmail.com

import {createSlice, createAsyncThunk} from '@reduxjs/toolkit'
import type {PayloadAction} from '@reduxjs/toolkit'
import VehicleService from './VehicleService'
import {Vehicle, VehicleView} from 'app/modules/green_zone/vehicle/__model'

type VehicleState = {
  vehicleIndex: any
  vehicleView: VehicleView | null
  status: 'idle' | 'loading' | 'succeeded' | 'failed'
  error: string | null
  loading: boolean
}

const initialState: VehicleState = {
  vehicleIndex: {data: []},
  vehicleView: null,
  status: 'idle',
  error: null,
  loading: false,
}

// Get vehicle from server
export const getVehicle = createAsyncThunk('api/vehicle/index', async (params: any, thunkAPI) => {
  try {
    return await VehicleService.getvehicle(params)
  } catch (error: any) {
    const message =
      (error.response && error.response.data && error.response.data.message) ||
      error.message ||
      error.toString()
    return thunkAPI.rejectWithValue(message)
  }
})

// Get expired vehicles from server
export const getExpiredVehicles = createAsyncThunk(
  'api/vehicle/expired',
  async (params: any, thunkAPI) => {
    try {
      return await VehicleService.getExpiredVehicles(params)
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

export const editVehicle = createAsyncThunk(
  'api/vehicle/index',
  async (params: number | {id: number; data?: FormData}, thunkAPI) => {
    try {
      if (typeof params === 'number') {
        // GET request case
        const response = await VehicleService.getvehicle(params)
        return response.data // Return the first vehicle (assuming ID lookup)
      } else {
        // PUT request case
        const {id, data} = params
        // return await VehicleService.updateVehicle(id, data);
      }
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

// Get status for button
export const createButton = createAsyncThunk(
  'api/vehicle/createButton',
  async (params: any, thunkAPI) => {
    try {
      return await VehicleService.createButton(params)
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

// store Boss
export const storeVehicle = createAsyncThunk(
  'api/vehicle/store',
  async (formData: any, thunkAPI) => {
    try {
      
      return await VehicleService.store(formData)
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

// View Vehicle
export const viewVehicle = createAsyncThunk(
  'api/vehicle/view',
  async ({id, formData}: any, thunkAPI) => {
    try {
      const data = await VehicleService.viewVehicle(id, formData)
     
      return data;
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

// Vehicle Update
export const updateVehicle = createAsyncThunk(
  'api/vehicle/update',
  async ({id, formData}: {id: number; formData: FormData}, thunkAPI) => {
    try {
      const response = await VehicleService.update(id, formData)
      return response.data
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

export const changeStatus = createAsyncThunk(
  'vehicle/changeStatus',
  async (formData: FormData, thunkAPI) => {
    try {
      const response = await VehicleService.changeStatus(formData)
      return response.data
    } catch (error: any) {
      const message = error.response?.data?.message || error.message || error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

// // In your redux slice
// export const changeStatus = createAsyncThunk(
//   'company/changeStatus',
//   async (data: { id: string; status: number }, thunkAPI) => {
//     try {
//       const numericId = Number(data.id) // Ensure the ID is a number
//       if (isNaN(numericId)) {
//         return thunkAPI.rejectWithValue('Invalid Company ID')
//       }

//       const response = await companyService.changeStatus({
//         id: numericId, // Send the numeric ID
//         status: data.status,
//       })

//       return response.data
//     } catch (error: any) {
//       const message =
//         (error.response && error.response.data && error.response.data.message) ||
//         error.message ||
//         error.toString()
//       return thunkAPI.rejectWithValue(message)
//     }
//   }
// )

export const VehicleSlice = createSlice({
  name: 'vehicle',
  initialState,
  reducers: {
    reset: (state) => initialState,
  },
  extraReducers: (builder) => {
    builder
      .addCase(getVehicle.fulfilled, (state, action: PayloadAction<any>) => {
        state.vehicleIndex = action.payload
      })
      .addCase(getExpiredVehicles.fulfilled, (state, action: PayloadAction<any>) => {
   
        state.vehicleIndex = action.payload
      })
      .addCase(viewVehicle.fulfilled, (state, action: PayloadAction<any>) => {
        state.vehicleView = action.payload.data // Access the nested data property
        state.status = 'succeeded'
      })
  },
})

export const {reset} = VehicleSlice.actions
export default VehicleSlice.reducer
