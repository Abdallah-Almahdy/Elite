import React from "react";
import { useSelectedScreens } from "../contexts/SelectedScreensContext";

export default function useScreensLogic() {
  const { selectedScreens, setSelectedScreens } = useSelectedScreens();

  const handleSelectScreen = (screen) => {
    setSelectedScreens((prev) => ({
      ...prev,
      [screen.name]: !prev[screen.name],
    }));
    
  };
  return {
    handleSelectScreen,
  };
}
