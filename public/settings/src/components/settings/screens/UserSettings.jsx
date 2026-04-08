import { FaUser } from "react-icons/fa";
import { useState, useEffect } from "react";
import { useSelector } from "react-redux";
import useScreenSettingsPreferenceLogic from "../../../hooks/settings/useScreenSettingsPreferenceLogic";
import { useInvoiceSettings } from "../../../contexts/InvoiceSettingsContext";
import { IoCloseSharp } from "react-icons/io5";

export default function UserSettings() {
  // const users = useSelector((state)=> state.user?.users);
  const { updateScreenSettings } = useInvoiceSettings();
  const users = useSelector((state) => state.user?.users);

  const {
    userName,
    userNameResult,
    userNameShowResult,
    setUserName,
    errors,
    handleSelectUser,
    handleSearchUserName,
    setUserNameResult,
    setUserNameShowResult,
  } = useScreenSettingsPreferenceLogic();
  // useEffect(() => {
  //         const results = handleSearchUserName(userName);
  //         setUserNameResult(results);
  //         if (results?.length > 0) {
  //           setUserNameShowResult(true);
  //         }
  //       }, [ userName]);
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
            value={userName}
            onChange={(e) => {
              const value = e.target.value;
              setUserName(value);
              updateScreenSettings((prev) => ({
                ...prev,
                userName: value,
              }));
            }}
          >
            <option value="" disabled className="bg-white">
              يرجى اختيار اسم المستخدم
            </option>
            {users?.map((user) => (
              <option key={user?.id} value={user.name} className="bg-white">
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
                  onClick={() => {
                    handleSelectUser(user.name);
                  }}
                >
                  {user.name}
                </div>
              ))}
            </div>
          )}
          {errors.userName && (
            <p className="text-red-500 text-sm font-bold">{errors.userName}</p>
          )}
          <button
            className="absolute left-3 top-1/4"
            onClick={() => {
              setUserName("");
              updateScreenSettings((prev) => ({
                ...prev,
                userName: "",
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
