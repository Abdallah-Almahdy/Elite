import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import api from "../../api/baseURL";
const initialState = {
  permissions: [],
  invoiceSettings: [],
  userWarehouseNames: [],
  userPrintersConfig: [],
  userSectionsConfig: [],
  warehouseName: "",
  loading: false,
  error: null,
  message: "",
  passwordMessage: ""
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
  "settings/fetchConfigs",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get(`/invice-config`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const checkPassword = createAsyncThunk(
  "settings/checkPassword",
  async ({password}, { rejectWithValue }) => {
    try {
      const response = await api.post("/check-password", password);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchUserWarehouseNames = createAsyncThunk(
  "settings/fetchUserWarehouseNames",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get("/userWarehouseSettings");
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchUserPrintersConfig = createAsyncThunk(
  "settings/fetchUserPrintersConfig",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get("/invicePrintersUserSettings");
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchUserSectionsConfig = createAsyncThunk(
  "settings/fetchUserSectionsConfig",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get("/sectionUserSettings");
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
      })
      .addCase(checkPassword.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(checkPassword.fulfilled, (state, action) => {
        state.loading = false;
        state.passwordMessage = action?.payload?.message;
      })
      .addCase(checkPassword.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(fetchUserWarehouseNames.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchUserWarehouseNames.fulfilled, (state, action) => {
        state.loading = false;
        state.userWarehouseNames = action?.payload;
      })
      .addCase(fetchUserWarehouseNames.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(fetchUserPrintersConfig.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchUserPrintersConfig.fulfilled, (state, action) => {
        state.loading = false;
        state.userPrintersConfig = action?.payload?.data[0];
      })
      .addCase(fetchUserPrintersConfig.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(fetchUserSectionsConfig.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchUserSectionsConfig.fulfilled, (state, action) => {
        state.loading = false;
        state.userSectionsConfig = action?.payload?.data;
      })
      .addCase(fetchUserSectionsConfig.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
  },
});

export default settingSlice.reducer;
