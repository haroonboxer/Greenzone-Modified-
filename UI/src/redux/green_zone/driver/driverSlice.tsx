// -- name: DriverSlice.tsx
// -- date: 03-18-2025.
// -- desc: Redux toolkit slice for the Driver components.
// -- author: Mohammad Omer Amiri.
// -- email: amiriomer6@gmail.com

import { createSlice, createAsyncThunk } from '@reduxjs/toolkit'
import type { PayloadAction } from '@reduxjs/toolkit'
import driverService from './driverService'
import { Driver } from 'app/modules/green_zone/driver/__model'

type DriverState = {
  driverIndex: any
  driverView: Driver | null
  status: 'idle' | 'loading' | 'succeeded' | 'failed'
  error: string | null
  loading: boolean
}

const initialState: DriverState = {
  driverIndex: { data: [] },
  driverView: null,
  status: 'idle',
  error: null,
  loading: false,
}

// Get driver from server
export const getDriver = createAsyncThunk('api/driver/index', async (params: any, thunkAPI) => {
  try {
    const data = await driverService.getDriver(params);
    console.log(data);
    return data;
  } catch (error: any) {
    const message =
      (error.response && error.response.data && error.response.data.message) ||
      error.message ||
      error.toString()
    return thunkAPI.rejectWithValue(message)
  }
})


// Get status for button
export const createButton = createAsyncThunk('api/driver/createButton', async (params: any, thunkAPI) => {
  try {
    return await driverService.createButton(params)
  } catch (error: any) {
    const message =
      (error.response && error.response.data && error.response.data.message) ||
      error.message ||
      error.toString()
    return thunkAPI.rejectWithValue(message)
  }
})

// store Driver
export const storeDriver = createAsyncThunk(
  'api/driver/store',
  async (formData: any, thunkAPI) => {
    try {
      return await driverService.store(formData)
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)


// View Driver
export const viewDriver = createAsyncThunk(
  'api/driver/view',
  async ({ id, formData }: any, thunkAPI) => {
    try {
      return await driverService.viewDriver(id, formData)
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

// Driver Update 
export const updateDriver = createAsyncThunk(
  'api/driver/update',
  async ({ id, formData }: { id: number; formData: FormData }, thunkAPI) => {
    try {
      const response = await driverService.update(id, formData)
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
  'driver/changeStatus',
  async (formData: FormData, thunkAPI) => {
    try {
      const response = await driverService.changeStatus(formData)
      return response.data
    } catch (error: any) {
      // Extract error code and message from backend
      const message = error.response?.data?.message || error.message || 'Unknown error'
      const errorCode = error.response?.data?.error_code || null

      // Reject with an object, not just a string
      return thunkAPI.rejectWithValue({ message, errorCode })
    }
  }
)

export const driverSlice = createSlice({
  name: 'driverSlice',
  initialState,
  reducers: {
    reset: (state) => initialState,
  },
  extraReducers: (builder) => {
    builder
      .addCase(getDriver.fulfilled, (state, action: PayloadAction<any>) => {
        state.driverIndex = action.payload
      })
      .addCase(viewDriver.fulfilled, (state, action: PayloadAction<any>) => {
        state.driverView = action.payload
      })
  },
})

export const { reset } = driverSlice.actions
export default driverSlice.reducer
