import { FaUser } from "react-icons/fa";
import { useState, useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import useScreenSettingsPreferenceLogic from "../../../hooks/settings/useScreenSettingsPreferenceLogic";
import { useInvoiceSettings } from "../../../contexts/InvoiceSettingsContext";
import { IoCloseSharp } from "react-icons/io5";
import { fetchUserPermissions } from "../../../store/reducers/settingSlice";
import { resetUserPermissions } from "../../../store/reducers/settingSlice";
import { useScreensPermissions } from "../../../contexts/ScreensPermissionsContext";

export default function UserSettings({
  userName,
  userId,
  setUserName,
  setUserId,
  errors,
  userNameShowResult,
}) {
  const dispatch = useDispatch();
  const { updateScreenSettings } = useInvoiceSettings();
  const users = useSelector((state) => state.user?.users);

  const { setScreenSettings } = useScreensPermissions();

  const savedData = JSON.parse(localStorage.getItem("Screens Settings"));

  const [highlightIndex, setHighlightIndex] = useState(-1);
  return (
    <div className="w-full bg-white rounded-3xl">
      <div className="flex items-center border-b">
        <FaUser className="text-2xl text-blue-700 mr-5" />
        <h1 className=" p-5 pr-2 font-semibold text-xl">اعدادات المستخدم</h1>
      </div>
      <div className="flex items-center gap-x-10 p-3 text-lg py-5">
        <h2>اسم المستخدم :</h2>
        <div className="relative w-[50%]">
          <select
            className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
            value={savedData?.userId ?? userId ?? ""}
            onChange={async (e) => {
              const value = e.target.value;
              const selectedUser = users?.find(
                (user) => user?.id === Number(value),
              );
              setUserName(selectedUser?.name ?? "");
              setUserId(Number(value));
              await dispatch(
                fetchUserPermissions({
                  id: selectedUser.id,
                  userName: selectedUser?.id,
                }),
              );
              updateScreenSettings((prev) => ({
                ...prev,
                userName: selectedUser?.name,
                userId: selectedUser?.id,
              }));
              setScreenSettings((prev) => ({
                ...prev,
                userId: selectedUser?.id,
                userName: selectedUser?.name,
              }));
            }}
          >
            <option value="" disabled className="bg-white">
              يرجى اختيار اسم المستخدم
            </option>
            {users?.map((user) => (
              <option key={user?.id} value={user.id} className="bg-white">
                {user.name}
              </option>
            ))}
          </select>
          {userNameShowResult && userName.length > 0 && (
            <div
              //   ref={clientResultRef}
              className="border bg-white absolute w-full z-50 max-h-60 overflow-y-auto shadow"
            >
              {users?.map((user, index) => (
                <div
                  key={user?.id}
                  className={`p-2 cursor-pointer ${
                    index === highlightIndex
                      ? "bg-blue-100"
                      : "hover:bg-gray-100"
                  }`}
                  // onClick={() => {
                  //   handleSelectUser(user);
                  //   console.log(user)
                  // }}
                >
                  {user.name}
                </div>
              ))}
            </div>
          )}
          {errors?.userName && (
            <p className="text-red-500 text-sm font-bold">{errors?.userName}</p>
          )}
          <button
            className="absolute left-3 top-1/4"
            onClick={async () => {
              setUserName("");
              setUserId("");
              dispatch(resetUserPermissions());
              localStorage.removeItem("Screens Settings");
              updateScreenSettings((prev) => ({
                ...prev,
                userName: "",
                userId: "",
              }));
            }}
          >
            <IoCloseSharp />
          </button>
        </div>
      </div>
      <hr className="w-[95%]" />
    </div>
  );
}
