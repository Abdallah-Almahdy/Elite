import { createContext, useContext, useState } from "react";

const UIPreferencesContext = createContext();

export const UIPreferencesProvider = ({ children }) => {
  const [preference, setPreference] = useState(
    localStorage.getItem("uiPreference") || "textWrap",
  );

  const updatePreference = (value) => {
    setPreference(value);
    localStorage.setItem("uiPreference", value);
  };

  return (
    <UIPreferencesContext.Provider value={{ preference, updatePreference }}>
      {children}
    </UIPreferencesContext.Provider>
  );
};

export const useUIPreferences = () => useContext(UIPreferencesContext);
