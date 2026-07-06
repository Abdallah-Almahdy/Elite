import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import api from "../../api/baseURL";
const initialState = {
  permissions: [],
  userPermissions: [],
  configs: [],
  userConfigs: {},
  invoiceSettings: [],
  loading: false,
  error: null,
  message: "",
  mainWarehouse: "",
  userPrintersConfig: [],
  sectionsPrintersConfig: [],
  userSectionsConfig: [],
  invoicePrintersConfig: [],
  userWarehouseConfig: [],
  selectedUser: {
    id: null,
    userName: "",
  },
  warehouseNames: [],
};

export const fetchPermissions = createAsyncThunk(
  "settings/fetchPermissions",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get(`/permissions`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchUserPermissions = createAsyncThunk(
  "settings/fetchUserPermissions",
  async ({ id }, { rejectWithValue }) => {
    try {
      const response = await api.get(`/getUserPermissions/${id}`);
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
export const fetchConfigs = createAsyncThunk(
  "settings/fetchConfigs",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get(`/inviceConfigSystemSettings`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchUserConfigs = createAsyncThunk(
  "settings/fetchUserConfigs",
  async ({id}, { rejectWithValue }) => {
    try {
      const response = await api.get(`/invice-config?user_id=${id}`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const sendSectionsPrintersConfig = createAsyncThunk(
  "settings/sendSectionsPrintersConfig",
  async ({ sectionsPrintersConfig }, { rejectWithValue }) => {
    try {
      const response = await api.post(
        "/updateSectionPrinterSettings",
        sectionsPrintersConfig,
      );
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchSectionsPrintersConfig = createAsyncThunk(
  "settings/fetchSectionsPrintersConfig",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get(`/sectionsPrinterSettings`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const sendUserSectionsConfig = createAsyncThunk(
  "settings/sendUserSectionsConfig",
  async ({ userSectionsConfig }, { rejectWithValue }) => {
    try {
      const response = await api.post(
        "/updateSectionUserSettings",
        userSectionsConfig,
      );
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchUserSectionsConfig = createAsyncThunk(
  "settings/fetchUserSectionsConfig",
  async ({id}, { rejectWithValue }) => {
    try {
      const response = await api.get(`/sectionUserSettings?user_id=${id}`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const sendInvoicePrintersConfig = createAsyncThunk(
  "settings/sendInvoicePrintersConfig",
  async ({ invoicePrintersConfig }, { rejectWithValue }) => {
    try {
      const response = await api.post(
        "/updateInvicePrintersSettings",
        invoicePrintersConfig,
      );
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchInvoicePrintersConfig = createAsyncThunk(
  "settings/fetchInvoicePrintersConfig",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get(`/invicePrintersSystemSettings`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchUserPrintersConfig = createAsyncThunk(
  "settings/fetchUserPrintersConfig",
  async ({id}, { rejectWithValue }) => {
    try {
      const response = await api.get(`/invicePrintersUserSettings?user_id=${id}`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const sendInvoiceWarehouseName = createAsyncThunk(
  "settings/sendInvoiceWarehouseName",
  async ({ invoiceWarehouseName }, { rejectWithValue }) => {
    try {
      const response = await api.post(
        "/updateDefaultWarehouse",
        invoiceWarehouseName,
      );
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchWarehouseNames = createAsyncThunk(
  "settings/fetchWarehouseNames",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get(`/getWarehouses`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const sendUserWarehouseConfig = createAsyncThunk(
  "settings/sendUserWarehouseConfig",
  async ({ userWarehouseConfig }, { rejectWithValue }) => {
    try {
      const response = await api.post(
        "/updateUserWarehouseSettings",
        userWarehouseConfig,
      );
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

export const fetchUserWarehouseConfig = createAsyncThunk(
  "settings/fetchUserWarehouseConfig",
  async ({id}, { rejectWithValue }) => {
    try {
      const response = await api.get(`/userWarehouseSettings?user_id=${id}`);
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

const settingSlice = createSlice({
  name: "setting",
  initialState,
  reducers: {
    resetUserPermissions: (state) => {
      state.userPermissions = [];
      state.userConfigs = [];
      state.userSectionsConfig = [];
      state.userWarehouseConfig = [];
      state.userPrintersConfig = [];
    },
  },
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
      .addCase(fetchUserPermissions.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchUserPermissions.fulfilled, (state, action) => {
        state.loading = false;
        state.userPermissions = action?.payload?.permissions;
        //state.selectedUser = action?.meta?.arg;
      })
      .addCase(fetchUserPermissions.rejected, (state, action) => {
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
      })
      .addCase(fetchConfigs.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchConfigs.fulfilled, (state, action) => {
        state.loading = false;
        state.configs = action?.payload.config[0];
        state.mainWarehouse = action?.payload?.mainWarehouse;
      })
      .addCase(fetchConfigs.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(fetchUserConfigs.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchUserConfigs.fulfilled, (state, action) => {
        state.loading = false;
        state.userConfigs = action?.payload.config;
      })
      .addCase(fetchUserConfigs.rejected, (state, action) => {
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
      .addCase(sendSectionsPrintersConfig.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(sendSectionsPrintersConfig.fulfilled, (state) => {
        state.loading = false;
      })
      .addCase(sendSectionsPrintersConfig.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(fetchSectionsPrintersConfig.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchSectionsPrintersConfig.fulfilled, (state, action) => {
        state.loading = false;
        state.sectionsPrintersConfig = action?.payload?.data;
      })
      .addCase(fetchSectionsPrintersConfig.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(sendUserSectionsConfig.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(sendUserSectionsConfig.fulfilled, (state) => {
        state.loading = false;
      })
      .addCase(sendUserSectionsConfig.rejected, (state, action) => {
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
      .addCase(sendInvoicePrintersConfig.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(sendInvoicePrintersConfig.fulfilled, (state) => {
        state.loading = false;
      })
      .addCase(sendInvoicePrintersConfig.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(fetchInvoicePrintersConfig.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchInvoicePrintersConfig.fulfilled, (state, action) => {
        state.loading = false;
        state.invoicePrintersConfig = action?.payload?.data[0];
      })
      .addCase(fetchInvoicePrintersConfig.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(sendInvoiceWarehouseName.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(sendInvoiceWarehouseName.fulfilled, (state) => {
        state.loading = false;
      })
      .addCase(sendInvoiceWarehouseName.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(fetchWarehouseNames.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchWarehouseNames.fulfilled, (state, action) => {
        state.loading = false;
        state.warehouseNames = action?.payload;
      })
      .addCase(fetchWarehouseNames.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(sendUserWarehouseConfig.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(sendUserWarehouseConfig.fulfilled, (state) => {
        state.loading = false;
      })
      .addCase(sendUserWarehouseConfig.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      })
      .addCase(fetchUserWarehouseConfig.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchUserWarehouseConfig.fulfilled, (state, action) => {
        state.loading = false;
        state.userWarehouseConfig = action?.payload;
      })
      .addCase(fetchUserWarehouseConfig.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      });
  },
});

export const { resetUserPermissions } = settingSlice.actions;

export default settingSlice.reducer;
