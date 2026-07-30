import {createSlice, createAsyncThunk} from '@reduxjs/toolkit'
import type {PayloadAction} from '@reduxjs/toolkit'
import licenseService from './licenseService'
import {License} from 'app/modules/green_zone/License/__model'

type LicenseState = {
  licenseIndex: any
  licenseView: License | null
  status: 'idle' | 'loading' | 'succeeded' | 'failed'
  error: string | null
  loading: boolean
}

const initialState: LicenseState = {
  licenseIndex: {data: []},
  licenseView: null,
  status: 'idle',
  error: null,
  loading: false,
}

export const getLicense = createAsyncThunk('api/license/index', async (params: any, thunkAPI) => {
  try {
    return await licenseService.getLicense(params)
  } catch (error: any) {
    const message =
      (error.response && error.response.data && error.response.data.message) ||
      error.message ||
      error.toString()
    return thunkAPI.rejectWithValue(message)
  }
})

export const storeLicense = createAsyncThunk(
  'api/license/store',
  async (formData: FormData, {rejectWithValue}) => {
    try {
      return await licenseService.store(formData)
    } catch (error: any) {
      if (error.response && error.response.data) {
        // forward backend response (with key for localization)
        return rejectWithValue(error.response.data)
      }
      // fallback if no backend response
      return rejectWithValue({key: 'gzlicense.save_error'})
    }
  }
)

export const sentPrint = createAsyncThunk(
  'api/license/sentPrint',
  async (formData: FormData, thunkAPI) => {
    try {
      return await licenseService.sentPrint(formData)
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

export const viewGzLicense = createAsyncThunk(
  'api/license/view',
  async ({id}: {id: number}, thunkAPI) => {
    try {
      const response = await licenseService.view(id)
      return response
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

export const updateLicense = createAsyncThunk(
  'api/license/update',
  async ({id, formData}: {id: number; formData: FormData}, thunkAPI) => {
    try {
      const response = await licenseService.update(id, formData)
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

export const changeStatusOfPrint = createAsyncThunk(
  'printedCard/changeStatusOfPrint',
  async (data: {id: number; status: number}, thunkAPI) => {
    try {
      const numericId = Number(data.id)
      if (isNaN(numericId)) {
        return thunkAPI.rejectWithValue('Invalid License ID')
      }

      const formData = new FormData()
      formData.append('id', String(numericId))
      formData.append('printed', String(data.status)) // Backend expects "printed"

      const response = await licenseService.changeStatusOfPrint(formData)

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

export const licenseSlice = createSlice({
  name: 'license',
  initialState,
  reducers: {
    reset: (state) => initialState,
  },
  extraReducers: (builder) => {
    builder
      .addCase(getLicense.fulfilled, (state, action: PayloadAction<any>) => {
        state.licenseIndex = action.payload
      })
      .addCase(storeLicense.pending, (state) => {
        state.status = 'loading'
      })
      .addCase(storeLicense.fulfilled, (state, action: PayloadAction<any>) => {
        state.status = 'succeeded'
        state.licenseIndex.data.push(action.payload)
      })
      .addCase(storeLicense.rejected, (state, action: PayloadAction<any>) => {
        state.status = 'failed'
        state.error = action.payload
      })
      .addCase(viewGzLicense.pending, (state) => {
        state.loading = true
        state.error = null
        state.licenseView = null
      })
      .addCase(viewGzLicense.fulfilled, (state, action) => {
        state.loading = false
        state.error = null
        // API already returns object, no need for action.payload.data
        state.licenseView = action.payload
      })
      .addCase(viewGzLicense.rejected, (state, action) => {
        state.loading = false
        state.error = action.payload as string
        state.licenseView = null
      })
    // .addCase(changeStatus.pending, (state) => {
    //   state.status = 'loading'
    // })
  },
})

export const {reset} = licenseSlice.actions
export default licenseSlice.reducer
