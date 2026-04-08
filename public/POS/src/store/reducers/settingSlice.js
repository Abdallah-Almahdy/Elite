import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import api from "../../api/baseURL";
const initialState = {
  permissions: [],
  invoiceSettings: [],
  warehouseName: "",
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

export const fetchConfigs = createAsyncThunk(
  "settings/sendConfigs",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get("/invice-config");
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
      .addCase(fetchConfigs.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchConfigs.fulfilled, (state, action) => {
        state.loading = false;
        state.invoiceSettings = action?.payload?.config;
        state.warehouseName = action?.payload?.mainWarehouse;
      })
      .addCase(fetchConfigs.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      });
  },
});

export default settingSlice.reducer;
