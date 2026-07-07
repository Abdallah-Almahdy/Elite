import { createSlice } from "@reduxjs/toolkit";
import {
  saveDraftOffline,
  getOfflineDrafts,
  removeDRafts,
} from "../../services/indexedDB";

const initialState = {
  offlineDrafts: [],
  currentDraft: null,
};

const draftSlice = createSlice({
  name: "draft",
  initialState,
  reducers: {
    setOfflineDrafts: (state, action) => {
      state.offlineDrafts = action.payload;
    },
    setCurrentDraft: (state, action) => {
      state.currentDraft = action.payload;
    },
    removeDraftFromState: (state, action) => {
      state.offlineDrafts = state.offlineDrafts.filter(
        (d) => d.id !== action.payload,
      );
      if (state.currentDraft?.id === action.payload) state.currentDraft = null;
    },
  },
});

export const { setOfflineDrafts, setCurrentDraft, removeDraftFromState } =
  draftSlice.actions;

export const loadDrafts = () => async (dispatch) => {
  const drafts = await getOfflineDrafts();
  dispatch(setOfflineDrafts(drafts));
};

export const saveDraft = (draft) => async (dispatch) => {
  await saveDraftOffline(draft);
  const drafts = await getOfflineDrafts();
  dispatch(setOfflineDrafts(drafts));
  dispatch(setCurrentDraft(draft));
};

export const deleteDraft = (draftId) => async (dispatch) => {
  await removeDRafts(draftId);
  dispatch(removeDraftFromState(draftId));
};

export default draftSlice.reducer;
