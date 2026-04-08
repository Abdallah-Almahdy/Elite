import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import api from "../../api/baseURL";
const initialState = {
  permissions: [],
  invoiceSettings: [],
  loading: false,
  error: null,
  message: "",
};

export const fetchPermissions = createAsyncThunk(
  "settings/fetchPermissions",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get("/permissions");
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const sendPermissions = createAsyncThunk(
  "settings/sendPermissions",
  async ({ permissions }, { rejectWithValue }) => {
    try {
      const response = await api.post("/permissions", permissions);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const sendConfigs = createAsyncThunk(
  "settings/sendConfigs",
  async ({ invoiceSettings }, { rejectWithValue }) => {
    try {
      const response = await api.post("/invice-config", invoiceSettings);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

const settingSlice = createSlice({
  name: "setting",
  initialState,
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(fetchPermissions.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchPermissions.fulfilled, (state, action) => {
        state.loading = false;
        state.permissions = action?.payload?.permissions;
      })
      .addCase(fetchPermissions.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(sendPermissions.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(sendPermissions.fulfilled, (state, action) => {
        state.loading = false;
        state.permissions = action?.payload?.permissions;
        state.message = action?.payload?.message;
      })
      .addCase(sendPermissions.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(sendConfigs.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(sendConfigs.fulfilled, (state, action) => {
        state.loading = false;
        state.invoiceSettings = action?.payload?.permissions;
      })
      .addCase(sendConfigs.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      });
  },
});

export default settingSlice.reducer;
