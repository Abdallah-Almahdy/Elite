import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import api from "../../api/baseURL";
const initialState = {
  admin: null,
  loading: false,
  error: null,
  message: "",
};

export const fetchAdmin = createAsyncThunk(
  "admins/fetchAdmin",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get("/admins");
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

const adminSlice = createSlice({
  name: "admin",
  initialState,
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(fetchAdmin.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchAdmin.fulfilled, (state, action) => {
        state.loading = false;
        state.admin = action?.payload?.admins[0];
      })
      .addCase(fetchAdmin.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      });
  },
});

export default adminSlice.reducer;
