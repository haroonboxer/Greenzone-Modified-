// -- name: PrintedCardSlice.tsx
// -- date: 06-04-2025.
// -- desc: Redux toolkit slice for the PrintedCard components.
// -- author: Omer Amiri.
// -- email: amiriomer6@gmail.com

import { createSlice, createAsyncThunk } from '@reduxjs/toolkit'
import type { PayloadAction } from '@reduxjs/toolkit'
import cardPrintService from './cardPrintService'
import { PrintedCard } from 'app/modules/green_zone/card-print/list/__model'

type PrintedCardState = {
  CardIndex: any
  CardView: PrintedCard | null
  status: 'idle' | 'loading' | 'succeeded' | 'failed'
  error: string | null
  loading: boolean
}

const initialState: PrintedCardState = {
  CardIndex: { data: [] },
  CardView: null,
  status: 'idle',
  error: null,
  loading: false,
}

// Get Printed Card from server
export const getPrintedCard = createAsyncThunk(
  'api/card/index',
  async (params: any, thunkAPI) => {
    try {
      const data =  await cardPrintService.getPrintedCard(params);
 
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

// Store printed card
export const storePrintedCard = createAsyncThunk(
  'api/card/store',
  async (formData: FormData, thunkAPI) => {
    try {
      return await cardPrintService.store(formData)
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)

// View Printed Card
export const viewPrintedCard = createAsyncThunk(
  'api/card/view',
  async ({ id }: { id: number }, thunkAPI) => {
    try {
      const response = await cardPrintService.view(id)
      return response // ✅ fixed: already data, no `.data` needed
    } catch (error: any) {
      const message =
        (error.response && error.response.data && error.response.data.message) ||
        error.message ||
        error.toString()
      return thunkAPI.rejectWithValue(message)
    }
  }
)


// Printed Card Update
export const updatePrintedCard = createAsyncThunk(
  'api/card/update',
  async ({ id, formData }: { id: number; formData: FormData }, thunkAPI) => {
    try {
      const response = await cardPrintService.update(id, formData)
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
  'printedCard/changeStatus',
  async (data: { id: number; status: number }, thunkAPI) => {
    try {
      const numericId = Number(data.id)
      if (isNaN(numericId)) {
        return thunkAPI.rejectWithValue('Invalid printed card ID')
      }

      const response = await cardPrintService.changeStatus({
        id: numericId,
        status: data.status,
      })

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


export const changeStatusOfLicense = createAsyncThunk(
  'card/changeStatusOfLicense',
  async (
    data: { id: number; status: number; reason?: string }, // updated type
    thunkAPI
  ) => {
    try {
      const numericId = Number(data.id)
      if (isNaN(numericId)) {
        return thunkAPI.rejectWithValue('Invalid License ID')
      }

      const response = await cardPrintService.changeStatusOfLicense({
        id: numericId,
        status: data.status,
        reason: data.reason, // include reason if exists
      })

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


export const CardPrintSlice = createSlice({
  name: 'cardPrint',
  initialState,
  reducers: {
    reset: (state) => initialState,
  },
  extraReducers: (builder) => {
    builder
      .addCase(getPrintedCard.fulfilled, (state, action: PayloadAction<any>) => {
        state.CardIndex = action.payload
      })
      .addCase(storePrintedCard.pending, (state) => {
        state.status = 'loading'
      })
      .addCase(storePrintedCard.fulfilled, (state, action: PayloadAction<any>) => {
        state.status = 'succeeded'
        state.CardIndex.data.push(action.payload)
      })
      .addCase(storePrintedCard.rejected, (state, action: PayloadAction<any>) => {
        state.status = 'failed'
        state.error = action.payload
      })
      .addCase(viewPrintedCard.pending, (state) => {
        state.loading = true
      })
      .addCase(viewPrintedCard.fulfilled, (state, action) => {
        state.loading = false
        state.CardView = action.payload
      })
      .addCase(viewPrintedCard.rejected, (state, action) => {
        state.loading = false
        state.error = action.payload as string
      })
  },
})

export const { reset } = CardPrintSlice.actions
export default CardPrintSlice.reducer
