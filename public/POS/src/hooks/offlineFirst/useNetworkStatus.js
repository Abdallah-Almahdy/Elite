import { useEffect } from "react";

export const useNetworkStatus = () => {
  useEffect(() => {
    const handleOnline = () => console.log("Back online!");
    const handleOffline = () => console.log("Offline mode!");
    window.addEventListener("online", handleOnline);
    window.addEventListener("offline", handleOffline);
    return () => {
      window.removeEventListener("online", handleOnline);
      window.removeEventListener("offline", handleOffline);
    };
  }, []);
};
