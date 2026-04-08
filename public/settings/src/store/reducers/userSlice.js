import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
// import {
//   saveUserOffline,
//   getOfflineUsers,
//   removeUsers,
// } from "../../services/indexedDB";
// import { sendUserToBackend } from "../../api/baseURL";
import api from "../../api/baseURL";
const initialState = {
  offlineUsers: [],
  users: [],
  user: null,
  loading: false,
  error: null,
  message: null,
};

export const fetchClientsNames = createAsyncThunk(
  "users/fetchClientsNames",
  async (_, { rejectWithValue }) => {
    try {
      const response = await api.get("/users");
      return response?.data;
    } catch (error) {
      return rejectWithValue(error.response?.data || error.message);
    }
  },
);

const userSlice = createSlice({
  name: "user",
  initialState,
  reducers: {
    setSelectedUser: (state, action) => {
      state.user = action.payload;
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(fetchClientsNames.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchClientsNames.fulfilled, (state, action) => {
        state.loading = false;
        state.users = action?.payload?.users;
      })
      .addCase(fetchClientsNames.rejected, (state, action) => {
        state.loading = false;
        state.error = action?.payload;
      });
  },
});

export const { setSelectedUser } = userSlice.actions;

export default userSlice.reducer;
