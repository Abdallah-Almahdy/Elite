import { configureStore } from "@reduxjs/toolkit";
import userSlice from "./reducers/userSlice";
import settingSlice from "./reducers/settingSlice";
import adminSlice from "./reducers/adminSlice";

export const store = configureStore({
  reducer: {
    user: userSlice,
    setting: settingSlice,
    admin: adminSlice,
  },
});
