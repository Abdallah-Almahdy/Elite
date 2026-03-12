import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import {
  saveUserOffline,
  getOfflineUsers,
  removeUsers,
} from "../../services/indexedDB";
import { sendUserToBackend } from "../../api/baseURL";
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
    setOfflineUsers: (state, action) => {
      state.offlineUsers = action.payload;
    },
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

export const { setOfflineUsers, setSelectedUser } = userSlice.actions;

// Thunk: Save user (online/offline)
export const saveUser = (user) => async (dispatch) => {
  if (navigator.onLine) {
    try {
      await sendUserToBackend(user);
      dispatch(fetchClientsNames());
    } catch (err) {
      await saveUserOffline(user);
      const offlineUsers = await getOfflineUsers();
      dispatch(setOfflineUsers(offlineUsers));
    }
  } else {
    await saveUserOffline(user);
    const offlineUsers = await getOfflineUsers();
    dispatch(setOfflineUsers(offlineUsers));
  }
};

// Thunk: Sync offline users when online
export const syncOfflineUsers = () => async (dispatch) => {
  const offlineUsers = await getOfflineUsers();
  for (const user of offlineUsers) {
    try {
      await sendUserToBackend(user);
      await removeUsers(user.id);
    } catch (err) {
      console.log("Sync failed, will retry later", err);
    }
  }
  const updatedOfflineUsers = await getOfflineUsers();
  dispatch(setOfflineUsers(updatedOfflineUsers));
};

export default userSlice.reducer;
