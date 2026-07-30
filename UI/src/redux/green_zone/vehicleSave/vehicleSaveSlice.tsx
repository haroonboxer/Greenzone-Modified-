// -- name: DriverSlice.tsx
// -- date: 03-18-2025.
// -- desc: Redux toolkit slice for the Driver components.
// -- author: Mohammad Omer Amiri.
// -- email: amiriomer6@gmail.com

import { createSlice, createAsyncThunk } from '@reduxjs/toolkit'
import type { PayloadAction } from '@reduxjs/toolkit'
import vehicleSaveServiceService from './vehicleSaveService'

type VehicleSaveState = {
  vehicleSaveIndex: any
  status: 'idle' | 'loading' | 'succeeded' | 'failed'
  error: string | null
  loading: boolean
}

const initialState: VehicleSaveState = {
  vehicleSaveIndex: { data: [] },
  status: 'idle',
  error: null,
  loading: false,
}

// Get VehicleSave from server
export const getVehicleSave = createAsyncThunk('api/vehicleSave/index', async (params: any, thunkAPI) => {
  try {
    return await vehicleSaveServiceService.getVehicleSave(params)
  } catch (error: any) {
    const message =
      (error.response && error.response.data && error.response.data.message) ||
      error.message ||
      error.toString()
    return thunkAPI.rejectWithValue(message)
  }
})


// store Vehicle Save
export const storeVehicleSave = createAsyncThunk(
  'api/vehicleSave/store',
  async (formData: any, thunkAPI) => {
    try {
      return await vehicleSaveServiceService.store(formData)
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

// Vehicle Save Update 
export const updateVehicleSave = createAsyncThunk(
  'api/vehicleSave/update',
  async ({ id, formData }: { id: number; formData: any }, thunkAPI) => {
    try {
      const response = await vehicleSaveServiceService.update(id, formData) // accepts plain object
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

export const vehicleSaveSlice = createSlice({
  name: 'vehicleSaveSlice',
  initialState,
  reducers: {
    reset: (state) => initialState,
  },
  extraReducers: (builder) => {
    builder
      .addCase(getVehicleSave.fulfilled, (state, action: PayloadAction<any>) => {
        state.vehicleSaveIndex = action.payload
      })
  },
})

export const { reset } = vehicleSaveSlice.actions
export default vehicleSaveSlice.reducer
