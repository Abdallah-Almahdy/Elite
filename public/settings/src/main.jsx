import { createRoot } from "react-dom/client";
import "./index.css";
import App from "./App.jsx";
import { BrowserRouter } from "react-router-dom";
import { store } from "./store/index.js";
import { Provider } from "react-redux";
import { SelectedScreensProvider } from "./contexts/SelectedScreensContext.jsx";
import { ScreensPermissionsProvider } from "./contexts/ScreensPermissionsContext.jsx";

createRoot(document.getElementById("root")).render(
    <Provider store={store}>
        <BrowserRouter basename="/dashboard/invoice-settings">
            <SelectedScreensProvider>
                <ScreensPermissionsProvider>
                    <App />
                </ScreensPermissionsProvider>
            </SelectedScreensProvider>
        </BrowserRouter>
    </Provider>,
);
