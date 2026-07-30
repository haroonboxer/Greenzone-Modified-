import {createSlice, createAsyncThunk} from '@reduxjs/toolkit'
import type {PayloadAction} from '@reduxjs/toolkit'
import reportService from './reportService'

type ReportState = {
  reportIndex: any
  companies: Array<{id: string; company_dr: string}>
  monthlyStats: Array<{month: number; year: number; count: number}>
  status: 'idle' | 'loading' | 'succeeded' | 'failed'
  error: string | null
  loading: boolean
}

const initialState: ReportState = {
  reportIndex: {},
  companies: [],
  monthlyStats: [],
  status: 'idle',
  error: null,
  loading: false,
}

export const fetchReport = createAsyncThunk('report/getReport', async (params: any, thunkAPI) => {
  try {
    return await reportService.getReport(params)
  } catch (error: any) {
    const message =
      (error.response && error.response.data && error.response.data.message) ||
      error.message ||
      error.toString()
    return thunkAPI.rejectWithValue(message)
  }
})

export const fetchCompanies = createAsyncThunk('report/listCompany', async (_, thunkAPI) => {
  try {
    return await reportService.getCompanies()
  } catch (error: any) {
    const message =
      (error.response && error.response.data && error.response.data.message) ||
      error.message ||
      error.toString()
    return thunkAPI.rejectWithValue(message)
  }
})

export const fetchMonthlyCompanyStats = createAsyncThunk(
  'report/monthlyCompanyStats',
  async (_, thunkAPI) => {
    try {
      return await reportService.getMonthlyCompanyStats()
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

export const reportSlice = createSlice({
  name: 'report',
  initialState,
  reducers: {
    reset: () => initialState,
  },
  extraReducers: (builder) => {
    builder
      .addCase(fetchReport.pending, (state) => {
        state.status = 'loading'
        state.loading = true
        state.error = null
      })
      .addCase(fetchReport.fulfilled, (state, action: PayloadAction<any>) => {
        state.status = 'succeeded'
        state.loading = false
        state.reportIndex = action.payload
      })
      .addCase(fetchReport.rejected, (state, action: PayloadAction<any>) => {
        state.status = 'failed'
        state.loading = false
        state.error = action.payload
      })
      .addCase(fetchCompanies.fulfilled, (state, action: PayloadAction<any>) => {
        state.companies = action.payload
      })
      .addCase(fetchMonthlyCompanyStats.pending, (state) => {
        state.loading = true
        state.error = null
      })
      .addCase(fetchMonthlyCompanyStats.fulfilled, (state, action: PayloadAction<any>) => {
        state.loading = false
        state.monthlyStats = action.payload
      })
      .addCase(fetchMonthlyCompanyStats.rejected, (state, action: PayloadAction<any>) => {
        state.loading = false
        state.error = action.payload
      })
  },
})

export const {reset} = reportSlice.actions
export default reportSlice.reducer
